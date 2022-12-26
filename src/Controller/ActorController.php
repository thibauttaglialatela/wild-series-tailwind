<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Form\ActorType;
use App\Repository\ActorRepository;
use App\Repository\ProgramRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

#[Route('/actor', name: 'actor_')]
class ActorController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ManagerRegistry $managerRegistry): Response
    {
        return $this->render('actor/index.html.twig', [
            'actors' => $managerRegistry->getManager()->getRepository(Actor::class)->findAll(),
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, ActorRepository $actorRepository): Response
    {
        $actor = new Actor();
        $form = $this->createForm(ActorType::class, $actor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $actorRepository->save($actor, true);
            $this->addFlash('green', 'Un(e) acteur(rice) a bien été ajoutée.');
            return $this->redirectToRoute('actor_index', [], Response::HTTP_CREATED);
        }
        return $this->renderForm('actor/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{id}', name: 'show')]
    public function show(Actor $actor): Response
    {
        return $this->render('actor/show.html.twig', [
            'actor' => $actor,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Request $request, Actor $actor, ActorRepository $actorRepository): Response
    {
        $form = $this->createForm(ActorType::class, $actor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $actorRepository->save($actor, true);
            return $this->redirectToRoute('actor_show', ['id' => $actor->getId()], Response::HTTP_PERMANENTLY_REDIRECT);
        }
        return $this->renderForm('actor/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['POST'])]
    public function delete(Actor $actor, ActorRepository $actorRepository, Request $request): Response
    {
        $token = $request->get('_token');
        if (!is_string($token)) {
            throw new InvalidCsrfTokenException('error on the Csrf token');
        }
        if ($this->isCsrfTokenValid('delete'.$actor->getId(), $token)) {
            $actorRepository->remove($actor, true);
            $this->addFlash('red', 'Un acteur a été supprimé !');
        }
        return $this->redirectToRoute('actor_index', [], Response::HTTP_SEE_OTHER);
    }
}
