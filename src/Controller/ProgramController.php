<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\CommentType;
use App\Form\ProgramType;
use App\Form\SearchProgramType;
use App\Repository\CommentRepository;
use App\Repository\ProgramRepository;
use App\Repository\SeasonRepository;
use App\Repository\UserRepository;
use App\Service\CommentAverage;
use App\Service\ProgramDuration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/program', name: 'program_')]
class ProgramController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProgramRepository $programRepository, Request $request): Response
    {
        $form = $this->createForm(SearchProgramType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData();
            $programs = $programRepository->findLikeName($search['search']);
        } else {
            $programs = $programRepository->findAll();
        }

        return $this->renderForm('program/index.html.twig', [
            'programs' => $programs,
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(TranslatorInterface $translator,
                        MailerInterface     $mailer,
                        Request             $request,
                        ProgramRepository   $programRepository,
                        SluggerInterface    $slugger
    ): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $program->setOwner($this->getUser());
            $programRepository->save($program, true);
            $this->addFlash('green', $translator->trans('A program was added.'));
            $email = (new Email())
                ->to('user@hotmail.fr')
                ->subject('new program added')
                ->text('A new program has been added to Wild series')
                ->html($this->renderView('program/ProgramEmail.html.twig', [
                    'program' => $program,
                ]));

            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                // some error prevented the email sending; display an
                // error message or try to resend the message
            }
            return $this->redirectToRoute('program_index', [], Response::HTTP_CREATED);
        }
        return $this->renderForm('program/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{slug}', name: 'show', methods: ['GET'])]
    public function show(Program $program, SeasonRepository $seasonRepository, ProgramDuration $duration): Response
    {
        $seasons = $seasonRepository->findBy(['program' => $program]);
        if (count($seasons) > 0) {
            $programDuration = $duration->calculate($program);
        } else {
            $programDuration = 0;
        }
        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
            'program_duration' => $programDuration,
        ]);
    }

    #[Route('/{slug}/season/{seasonId}', name: 'season_show', requirements: ['seasonId' => '\d+'])]
    #[ParamConverter("program", class: "App\Entity\Program", options: ['mapping' => ["slug" => "slug"]])]
    #[ParamConverter("season", class: "App\Entity\Season", options: ["mapping" => ["seasonId" => "id"]])]
    public function showSeason(Program $program, Season $season): Response
    {
        $episodes = $season->getEpisodes();
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes,
        ]);
    }


    #[Route('/{slug}/season/{seasonId}/episode/{episode_slug}', name: 'episode_show', requirements: ['seasonId' => '\d+', 'episodeId' => '\d+'])]
    #[Entity('season', options: ['id' => 'seasonId'])]
    #[ParamConverter('episode', class: 'App\Entity\Episode', options: ['mapping' => ['episode_slug' => 'slug']])]
    public function showEpisode(CommentAverage $commentAverage, Program $program, Season $season, Episode $episode, Request $request, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setEpisode($episode);
            $comment->setAuthor($this->getUser());
            $commentRepository->save($comment, true);
            return $this->redirectToRoute('program_episode_show', ['slug' => $program->getSlug(), 'seasonId' => $season->getId(), 'episode_slug' => $episode->getSlug()], Response::HTTP_SEE_OTHER);
        }
        $comments = $commentRepository->findBy(['episode' => $episode->getId()], ['createdAt' => 'ASC']);
        $rates = $commentRepository->findAllRates($episode);
        $arrayRates = array_column($rates, 'rate');
        $averageRates = $commentAverage->calculate($arrayRates);

        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
            'comments' => $comments,
            'form' => $form->createView(),
            'average_rates' => $averageRates
        ]);

    }

    #[Route('/{slug}/edit', name: "edit")]
    public function edit(Request             $request,
                         Program             $program,
                         ProgramRepository   $programRepository,
                         SluggerInterface    $slugger,
                         TranslatorInterface $translator
    ): Response
    {

        $form = $this->createForm(ProgramType::class, $program);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($program->getTitle());
            $program->setSlug($slug);
            $programRepository->save($program, true);
            $this->addFlash('green', $translator->trans('The program was edited.'));

            return $this->redirectToRoute('program_show', ['slug' => $program->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('program/edit.html.twig', [
            'form' => $form,
            'program' => $program,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST', 'DELETE'])]
    public function delete(Program             $program,
                           ProgramRepository   $programRepository,
                           Request             $request,
                           TranslatorInterface $translator
    ): Response
    {
        $token = $request->get('_token');
        if (!is_string($token)) {
            throw new InvalidCsrfTokenException('error on the Csrf token');
        }
        if ($this->isCsrfTokenValid('delete' . $program->getId(), $token)) {
            $programRepository->remove($program, true);
            $this->addFlash('red', $translator->trans('Be careful, a program has been deleted!'));
        }
        return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/watchlist', name: 'watchlist', methods: ['GET', 'POST'])]
    public function addToWatchlist(Program $program, UserRepository $userRepository, TranslatorInterface $translator): JsonResponse
    {
        if (!$program) {
            throw $this->createNotFoundException(
                $translator->trans('This program does not exist!')
            );
        }
        /** @var \App\Entity\User */
        $user = $this->getUser();
        if ($user->isInWatchlist($program)) {
            $user->removeFromWatchlist($program);
        } else {
            $user->addToWatchlist($program);
        }
        $userRepository->save($user, true);

        return $this->json([
            'isInWatchlist' => $this->getUser()->isInWatchlist($program)
        ]);

    }
}
