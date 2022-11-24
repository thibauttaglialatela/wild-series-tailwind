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

#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('category/_index.html.twig', [
            'categories' => $categories,
        ]);
    }

#[Route('/new', name: 'new')]
public function new(Request $request, CategoryRepository $categoryRepository):Response
{
$category = new Category();
$form = $this->createForm(CategoryType::class, $category);
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()){
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
}
