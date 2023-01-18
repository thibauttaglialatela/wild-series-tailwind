<?php

namespace App\Controller;

use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: "Home_page")]
public function index(ProgramRepository $programRepository): Response
    {
        $lastProgramAdded = $programRepository->findBy([], ['id' => 'DESC'], 3);
        return $this->render('home/index.html.twig', [
            'website' => 'Wild Series',
            'last_program_added' => $lastProgramAdded,
        ]);
    }

}