<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comment', name: 'comment_')]
class CommentController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(CommentRepository $commentRepository): Response
    {
        return $this->render('comment/index.html.twig', [
            'comments' => $commentRepository->findAll(),
        ]);
    }

    #[Route('/{id}/program/{program_slug}/season/{season_id}/episode/{episode_slug}/edit', name: 'edit', methods: ['GET', 'POST'])]
    #[ParamConverter('program', class: 'App\Entity\Program', options: ['mapping' => ['program_slug' => 'slug']])]
    #[ParamConverter('season', class: 'App\Entity\Season', options: ['mapping' => ['season_id' => 'id']])]
    #[ParamConverter('episode', class: 'App\Entity\Episode', options: ['mapping' => ['episode_slug' => 'slug']])]
    public function edit(Request $request, Comment $comment, CommentRepository $commentRepository, Program $program, Season $season, Episode $episode): Response
    {
        $this->denyAccessUnlessGranted('edit', $comment);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentRepository->save($comment, true);

            return $this->redirectToRoute('program_episode_show', ['slug' => $program->getSlug(), 'seasonId' => $season->getId(), 'episode_slug' => $episode->getSlug()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form,
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }

    #[Route('/{id}/program/{program_slug}/season/{season_id}/episode/{episode_slug}/delete', name: 'delete', methods: ['POST'])]
    #[ParamConverter('program', class: 'App\Entity\Program', options: ['mapping' => ['program_slug' => 'slug']])]
    #[ParamConverter('season', class: 'App\Entity\Season', options: ['mapping' => ['season_id' => 'id']])]
    #[ParamConverter('episode', class: 'App\Entity\Episode', options: ['mapping' => ['episode_slug' => 'slug']])]
    public function delete(Request           $request,
                           Comment           $comment,
                           CommentRepository $commentRepository,
                           Program           $program,
                           Season            $season,
                           Episode           $episode
    ): Response
    {
        $this->denyAccessUnlessGranted('delete', $comment);
        if ($this->isCsrfTokenValid('delete' . $comment->getId(), $request->request->get('_token'))) {
            $commentRepository->remove($comment, true);
        }

        return $this->redirectToRoute('program_episode_show', ['slug' => $program->getSlug(), 'seasonId' => $season->getId(), 'episode_slug' => $episode->getSlug()], Response::HTTP_SEE_OTHER);
    }
}
