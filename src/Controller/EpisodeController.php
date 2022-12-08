<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\EpisodeType;
use App\Repository\EpisodeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/season/{season_id}/program/{program_id}/new', name: 'new', methods: ['GET', 'POST'])]
    #[ParamConverter('season', class: 'App\Entity\Season',options: ['mapping' => ['season_id' => 'id']])]
    #[ParamConverter('program', class: 'App\Entity\Program', options: ['mapping' => ['program_id' => 'id']])]
    public function new(Request $request, EpisodeRepository $episodeRepository, Season $season, Program $program): Response
    {
        $episode = new Episode();
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $episode->setSeason($season);
            $episodeRepository->save($episode, true);

            return $this->redirectToRoute('program_season_show', ['seasonId' => $season->getId(), 'programId' => $program->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('episode/new.html.twig', [
            'episode' => $episode,
            'form' => $form,
            'season' => $season,
            'program' => $program,
        ]);
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(Episode $episode): Response
    {
        return $this->render('episode/show.html.twig', [
            'episode' => $episode,
        ]);
    }

    #[Route('/program/{program_id}/season/{season_id}/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    #[ParamConverter('program', class: 'App\Entity\Program', options: ['mapping' => ['program_id' => 'id']])]
    #[ParamConverter('season', class: 'App\Entity\Season', options: ['mapping' => ['season_id' => 'id']])]
    public function edit(Request $request, Episode $episode, EpisodeRepository $episodeRepository, Program $program, Season $season): Response
    {
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $episodeRepository->save($episode, true);

            return $this->redirectToRoute('episode_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('episode/edit.html.twig', [
            'episode' => $episode,
            'program' => $program,
            'form' => $form,
            'season' => $season,
        ]);
    }

    #[Route('/{id}/program/{programId}/season/{seasonId}', name: 'delete', methods: ['POST', 'DELETE'])]
    #[ParamConverter('program', class: 'App\Entity\Program', options: ['mapping' => ['programId' => 'id']])]
    #[ParamConverter('season', class: 'App\Entity\Season', options: ['mapping' => ['seasonId' => 'id']])]
    public function delete(Request $request, Episode $episode, EpisodeRepository $episodeRepository, Program $program, Season $season): Response
    {
        if ($this->isCsrfTokenValid('delete'.$episode->getId(), $request->request->get('_token'))) {
            $episodeRepository->remove($episode, true);
        }

        return $this->redirectToRoute('program_season_show', ['programId' => $program->getId(), 'seasonId' => $season->getId()], Response::HTTP_SEE_OTHER);
    }
}
