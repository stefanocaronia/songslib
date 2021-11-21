<?php

namespace App\Controller;

use App\Entity\Song;
use App\Form\SongType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("song", name="song")
 */
class SongController extends AbstractController
{
    /**
     * Songs index
     * @Route("", name="_index")
     */
    public function index(Request $request): Response
    {
        $songs = $this->getDoctrine()->getRepository(Song::class)->findAll();

        return $this->render('song/index.html.twig', [
            'songs' => $songs,
        ]);
    }

    /**
     * Song show
     * @Route("/{id}/show", name="_show")
     */
    public function show(Request $request): Response
    {
        $song = $this->getDoctrine()->getRepository(Song::class)->find($request->get('id'));

        return $this->render('song/show.html.twig', [
            'song' => $song,
        ]);
    }

    /**
     * Song new
     * @Route("/new", name="_new")
     */
    public function new(Request $request): Response
    {
        $form = $this->createForm(SongType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

//            $this->addFlash('success', $translator->trans('Contact.Added'));

            return $this->redirectToRoute('homepage');
            // return $this->redirectToRoute('edit', ['id' => $contact->getId()]);
        }
        return $this->render('song/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Song edit
     * @Route("/{id}/edit", name="_edit")
     */
    public function edit(Request $request): Response
    {
        $form = $this->createForm(SongType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

//            $this->addFlash('success', $translator->trans('Contact.Added'));

            return $this->redirectToRoute('homepage');
            // return $this->redirectToRoute('edit', ['id' => $contact->getId()]);
        }
        return $this->render('songs.html.twig', [
            'form' => $form->createView(),
            'songs' => [],
        ]);
    }
}