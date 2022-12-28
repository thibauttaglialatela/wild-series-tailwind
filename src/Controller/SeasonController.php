<?php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Season;
use App\Form\SeasonType;
use App\Repository\SeasonRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

#[Route('/season', name: 'season_')]
class SeasonController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(SeasonRepository $seasonRepository): Response
    {
        return $this->render('season/index.html.twig', [
            'seasons' => $seasonRepository->findAll(),
        ]);
    }

    #[Route('/program/{program_slug}/new', name: 'new', methods: ['GET', 'POST'])]
    #[ParamConverter("program", class: "App\Entity\Program", options: ['mapping' => ['program_slug' => 'slug']])]
    public function new(Request $request, SeasonRepository $seasonRepository, Program $program): Response
    {
        $season = new Season();
        $form = $this->createForm(SeasonType::class, $season);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $season->setProgram($program);
            $seasonRepository->save($season, true);
            $this->addFlash('green', 'Une saison a bien été enregistrée.');

            return $this->redirectToRoute('program_show', ['slug' => $program->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('season/new.html.twig', [
            'season' => $season,
            'form' => $form,
            'program' => $program,
        ]);
    }

    #[Route('/{id}/program/{program_slug}', name: 'show', methods: ['GET'])]
    #[ParamConverter('program', class: 'App\Entity\Program',options: ['mapping' => ['program_slug' => 'slug']])]
    public function show(Season $season, Program $program): Response
    {

        return $this->render('season/show.html.twig', [
            'season' => $season,
            'program' => $program
        ]);
    }

    #[Route('/{id}/program/{program_slug}/edit', name: 'edit', methods: ['GET', 'POST'])]
    #[ParamConverter('program', options: ['mapping' => ['program_slug' => 'slug']])]
    public function edit(Request $request, Season $season, SeasonRepository $seasonRepository, Program $program): Response
    {
        $form = $this->createForm(SeasonType::class, $season);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $seasonRepository->save($season, true);
            $this->addFlash('green', 'La saison a été éditée.');

            return $this->redirectToRoute('program_season_show', ['slug'=> $program->getSlug(), 'seasonId'=>$season->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('season/edit.html.twig', [
            'season' => $season,
            'program' => $program,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Request $request, Season $season, SeasonRepository $seasonRepository): Response
    {
        $token = $request->get('_token');
        if (!is_string($token)) {
            throw new InvalidCsrfTokenException('error');
        }
        if ($this->isCsrfTokenValid('delete'.$season->getId(), $token)) {
            $seasonRepository->remove($season, true);
            $this->addFlash('red', 'La saison a été supprimé !');
        }

        return $this->redirectToRoute('program_show', ['id' => $season->getProgram()->getId()], Response::HTTP_SEE_OTHER);
    }
}
