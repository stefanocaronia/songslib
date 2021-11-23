<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Song;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("artist", name="artist")
 */
class ArtistController extends AbstractController
{
    /**
     * Artists Ajax List
     * @Route("/ajax-list", name="_ajax_list", options={"expose"=true})
     */
    public function index(Request $request): JsonResponse
    {
        $q = $request->query->get('q');
        $artists = $this->getDoctrine()->getRepository(Artist::class)->findAllMatching($q, $limit = 10);

        return $this->json([
            'items' => $artists
        ], 200, [], ['groups' => ['ajax']]);
    }
}