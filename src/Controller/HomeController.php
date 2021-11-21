<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * Songs index
     * @Route("/", name="homepage")
     */
    public function index(Request $request): Response
    {
        return $this->redirectToRoute('song_index');
    }
}