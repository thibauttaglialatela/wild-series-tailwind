<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Form\ActorType;
use App\Repository\ActorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Translation\LocaleSwitcher;
use Symfony\Contracts\Translation\TranslatorInterface;

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
    #[IsGranted('ROLE_CONTRIBUTOR')]
    public function new(Request $request, ActorRepository $actorRepository, SluggerInterface $slugger, TranslatorInterface $translator): Response
    {
        $actor = new Actor();
        $form = $this->createForm(ActorType::class, $actor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($actor->getFirstname() . ' ' . $actor->getLastname());
            $actor->setSlug($slug);
            $actorRepository->save($actor, true);
            $this->addFlash('green', $translator->trans('An actor has been add'));
            return $this->redirectToRoute('actor_index', [], Response::HTTP_CREATED);
        }
        return $this->renderForm('actor/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/{slug}', name: 'show')]
    public function show(Actor $actor, LocaleSwitcher $localeSwitcher): Response
    {
        $currentLocale = $localeSwitcher->getLocale();

        return $this->render('actor/show.html.twig', [
            'actor' => $actor,
            'current_locale' => $currentLocale,
        ]);
    }

    #[Route('/{slug}/edit', name: 'edit')]
    #[IsGranted('ROLE_CONTRIBUTOR')]
    public function edit(Request $request, Actor $actor, ActorRepository $actorRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ActorType::class, $actor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($actor->getFirstname() . ' ' . $actor->getLastname());
            $actor->setSlug($slug);
            $actorRepository->save($actor, true);
            return $this->redirectToRoute('actor_show', ['slug' => $actor->getSlug()], Response::HTTP_PERMANENTLY_REDIRECT);
        }
        return $this->renderForm('actor/edit.html.twig', [
            'form' => $form,
            'actor' => $actor,
        ]);
    }

    #[Route('/delete/{slug}', name: 'delete', methods: ['POST'])]
    public function delete(Actor $actor, ActorRepository $actorRepository, Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, $translator->trans('Only an administrator can delete an actor\'s file.'));
        $token = $request->get('_token');
        if (!is_string($token)) {
            throw new InvalidCsrfTokenException('error on the Csrf token');
        }
        if ($this->isCsrfTokenValid('delete'.$actor->getSlug(), $token)) {
            $actorRepository->remove($actor, true);
            $this->addFlash('red', $translator->trans('An actor has been deleted!'));
        }
        return $this->redirectToRoute('actor_index', [], Response::HTTP_SEE_OTHER);
    }
}
