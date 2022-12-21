<?php

namespace App\Controller;

use App\Entity\Actor;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

    #[Route('/{id}', name: 'show')]
    public function show(Actor $actor): Response
    {
        return $this->render('actor/show.html.twig', [
            'actor' => $actor,
        ]);
    }
//    TODO: créer les méthodes new, edit et delete
}
