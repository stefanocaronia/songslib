<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Song;
use App\Form\SongType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

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
    public function new(Request $request, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        $form = $this->createForm(SongType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $song = $form->getData();
            $em->persist($song);
            $song->setCreatedAt(new \DateTime());
            $song->setUpdatedAt(new \DateTime());
            $em->flush();

            $this->addFlash('success', $translator->trans('Song.Added'));

            return $this->redirectToRoute('homepage');
        }
        return $this->render('song/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Song edit
     * @Route("/{id}/edit", name="_edit")
     */
    public function edit(Song $song, Request $request, EntityManagerInterface $em, TranslatorInterface $translator): Response
    {
        if (!$song) {
            $this->addFlash('error', $translator->trans('Song.Notfound'));
            return $this->redirectToRoute('homepage');
        }

        $form = $this->createForm(SongType::class, $song);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', $translator->trans('Song.Saved'));
            return $this->redirectToRoute('homepage');
        }

        if (!$form->isSubmitted()) {
            $form->setData($song);
        }

        return $this->render('song/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Song delete
     * @Route("/{id}/delete", name="_delete", methods={ "DELETE" })
     */
    public function delete(Song $song, Request $request, EntityManagerInterface $em, TranslatorInterface $translator): JsonResponse
    {
        $em->remove($song);
        $em->flush();

        $this->addFlash('success', $translator->trans('Song.Deleted'));

        return new JsonResponse([
            'success' => true,
        ]);
    }
}