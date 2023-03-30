<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;

class ContactController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;      
    }
     /**
     * @Route("/", name="app_contact")
     */
    public function indexAction(): Response
    {
        $contacts = $this->entityManager->getRepository(Contact::class)->findAll();
        return $this->render('index.html.twig', [
            'contacts'=> $contacts]);
    }

    /**
     * @Route("/create", name="app_contact_create")
     */
    public function createAction(Request $request): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($contact);
            $this->entityManager->flush();

            $this->addFlash('success', 'Contact créé avec succès !');

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('create.html.twig', [
            'form'=> $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_contact_edit", methods={"GET", "POST"})
     */
    public function editAction(Request $request): Response
    {
        $contactId = $request->get('id');
        $contact = $this->entityManager->getRepository(Contact::class)->find($contactId);
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($contact);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_contact_edit', ['id'=> $contact->getId()]);
        }

        return $this->render('edit.html.twig', [
            'form'=> $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="app_contact_delete")
     */
    public function deleteAction(Request $request): Response
    {
        $contactId = $request->get('id');
        $contact = $this->entityManager->getRepository(Contact::class)->find($contactId);

        if (null === $contact) {
            $this->addFlash('error', sprintf('Impossible de supprimer le contact avec l\'id %s !', $contactId));

            return $this->redirectToRoute('app_contact');

        }

        $this->entityManager->remove($contact);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_contact');
    }

}
