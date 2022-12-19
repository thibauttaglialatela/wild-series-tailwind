<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findBy([],['name' => 'ASC']);
        return $this->render('category/_index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, CategoryRepository $categoryRepository): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->save($category, true);
            return $this->redirectToRoute('Home_page', [], Response::HTTP_CREATED);
        }
        return $this->renderForm('category/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{categoryName}', name: 'show', methods: ['GET'])]
    public function show(string $categoryName, CategoryRepository $categoryRepository, ProgramRepository $programRepository): Response
    {
        $category = $categoryRepository->findOneBy(['name' => $categoryName]);
        if (!$category) {
            throw $this->createNotFoundException(
                'aucune catégorie nommée ' . $categoryName . ' trouvée'
            );
        }
//récupére tous les programmes de cette catégorie
        $programByCategory = $programRepository->findBy(['category' => $category]);
        return $this->render('category/show.html.twig', [
            'program_by_category' => $programByCategory,
            'category' => $category,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit', methods: ['POST', 'GET'])]
    public function edit(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->save($category, true);
            return $this->redirectToRoute('category_show', ['categoryName' => $category->getName()], Response::HTTP_SEE_OTHER);
        }
        return $this->renderForm('category/edit.html.twig', [
            'form' => $form,
            'category' => $category,
        ]);

    }

    #[Route('/{id}', name: 'delete', methods: ['POST', 'DELETE'])]
    public function delete(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        $token = $request->get('_token');
        if(!is_string($token)) {
            throw new InvalidCsrfTokenException('error of the Csrf token');
        }
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $token)){
            $categoryRepository->remove($category, true);
        }
        return $this->redirectToRoute('Home_page', [],Response::HTTP_SEE_OTHER);
    }
}
