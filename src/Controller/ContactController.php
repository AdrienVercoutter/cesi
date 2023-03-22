<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;


class ContactController extends AbstractController
{
    /**
     * @Route("/", name="app_contact")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($contact);
            $entityManager->flush();

            $this->addFlash('success', 'Contact créé avec succès !');

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('index.html.twig', [
            'form'=> $form->createView ()]);
    }
}
