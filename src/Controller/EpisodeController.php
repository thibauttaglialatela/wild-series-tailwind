<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\CommentType;
use App\Form\EpisodeType;
use App\Repository\EpisodeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/episode', name: 'episode_')]
class EpisodeController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(EpisodeRepository $episodeRepository): Response
    {
        return $this->render('episode/index.html.twig', [
            'episodes' => $episodeRepository->findAll(),
        ]);
    }

    #[Route('/season/{season_id}/program/{program_slug}/new', name: 'new', methods: ['GET', 'POST'])]
    #[ParamConverter('season', class: 'App\Entity\Season', options: ['mapping' => ['season_id' => 'id']])]
    #[ParamConverter('program', class: 'App\Entity\Program', options: ['mapping' => ['program_slug' => 'slug']])]
    public function new(Request $request, EpisodeRepository $episodeRepository, Season $season, Program $program, SluggerInterface $slugger, MailerInterface $mailer): Response
    {
        $episode = new Episode();
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $episode->setSeason($season);
            $slug = $slugger->slug($episode->getTitle());
            $episode->setSlug($slug);
            $episodeRepository->save($episode, true);
            $this->addFlash('green', "Un épisode a bien été ajouté");
            $email = (new Email())
                ->to('user@example.com')
                ->subject('Nouvel épisode ajouté')
                ->html($this->renderView('episode/EpisodeEmail.html.twig', [
                    'episode' => $episode,
                    'program' => $program,
                    'season' => $season,
                ]));
            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $exception) {
                //error message
            }
            return $this->redirectToRoute('program_season_show', ['seasonId' => $season->getId(), 'slug' => $program->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('episode/new.html.twig', [
            'episode' => $episode,
            'form' => $form,
            'season' => $season,
            'program' => $program,
        ]);
    }

    #[Route('/{slug}', name: 'show', methods: ['GET'])]
    public function show(Episode $episode): Response
    {
        return $this->render('episode/show.html.twig', [
            'episode' => $episode,
        ]);
    }

    #[Route('/program/{program_slug}/season/{season_id}/{slug}/edit', name: 'edit', methods: ['GET', 'POST'])]
    #[ParamConverter('program', class: 'App\Entity\Program', options: ['mapping' => ['program_slug' => 'slug']])]
    #[ParamConverter('season', class: 'App\Entity\Season', options: ['mapping' => ['season_id' => 'id']])]
    public function edit(Request $request, Episode $episode, EpisodeRepository $episodeRepository, Program $program, Season $season): Response
    {
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $episodeRepository->save($episode, true);
            $this->addFlash('green', 'L\'épisode a bien été édité');

            return $this->redirectToRoute('program_season_show', ['seasonId' => $season->getId(), 'slug' => $program->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('episode/edit.html.twig', [
            'episode' => $episode,
            'program' => $program,
            'form' => $form,
            'season' => $season,
        ]);
    }

    #[Route('/{slug}/program/{program_slug}/season/{seasonId}', name: 'delete', methods: ['POST', 'DELETE'])]
    #[ParamConverter('program', class: 'App\Entity\Program', options: ['mapping' => ['program_slug' => 'slug']])]
    #[ParamConverter('season', class: 'App\Entity\Season', options: ['mapping' => ['seasonId' => 'id']])]
    public function delete(Request $request, Episode $episode, EpisodeRepository $episodeRepository, Program $program, Season $season): Response
    {
        $token = $request->get('_token');
        if (!is_string($token)) {
            throw new InvalidCsrfTokenException('erreur');
        }
        if ($this->isCsrfTokenValid('delete' . $episode->getId(), $token)) {
            $episodeRepository->remove($episode, true);
            $this->addFlash('red', "Attention un épisode a été supprimé !");
        }

        return $this->redirectToRoute('program_season_show', ['programId' => $program->getId(), 'seasonId' => $season->getId()], Response::HTTP_SEE_OTHER);
    }

}
