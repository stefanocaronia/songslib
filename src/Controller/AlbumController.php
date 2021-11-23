<?php

namespace App\Controller;

use App\Entity\Album;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("album", name="album")
 */
class AlbumController extends AbstractController
{
    /**
     * Albums Ajax List
     * @Route("/ajax-list", name="_ajax_list", options={"expose"=true})
     */
    public function index(Request $request): JsonResponse
    {
        $q = $request->query->get('q');
        $artists = $this->getDoctrine()->getRepository(Album::class)->findAllMatching($q, $limit = 10);

        return $this->json([
            'items' => $artists
        ], 200, [], ['groups' => ['ajax']]);
    }
}