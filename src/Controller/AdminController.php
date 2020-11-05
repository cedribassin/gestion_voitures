<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Form\VoitureType;
use App\Entity\RechercheVoiture;
use App\Form\RechercheVoitureType;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(VoitureRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $rechercheVoiture = new RechercheVoiture();

        $form = $this->createForm(RechercheVoitureType::class, $rechercheVoiture);
        $form->handleRequest($request);

        $voitures = $paginator->paginate(
            //On récupère la query créé dans le Repository
            $repository->findAllWithPagination($rechercheVoiture),
            $request->query->getInt('page', 1), /*page number*/
            6 /*limit per page*/
        );
        return $this->render('voiture/voitures.html.twig', [
            "voitures"=>$voitures,
            "form"=>$form->createView(),
            "admin"=>true
        ]);
    }

    /**
     * @Route("/admin/creation", name="ajoutVoiture")
     * @Route("/admin/{id}", name="modifVoiture", methods="GET|POST")
     */
    public function modificationVoiture(Voiture $voiture = null, Request $request, EntityManagerInterface $entityManager ): Response
    {
        if(!$voiture){
            $voiture = new Voiture();
        }

        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($voiture);
            $entityManager->flush();
            $this->addFlash('success', "Laction a été réalisée");
            return $this->redirectToRoute("admin");
        }
        return $this->render('admin/modifVoiture.html.twig', [
            "voiture"=>$voiture,
            "form"=>$form->createView(),
            "isModification"=>$voiture->getId() !== null
        ]);
    }

    /**
     * @Route("/admin/{id}", name="suppressionVoiture", methods="delete")
     */
    //Fonction permettant de supprimer un aliment (en rajoutant methods="delete" dans les routes, on va pouvoir distinguer
    // son url de celle présente dans la fonction ajoutEtModification())
    public function suppressionVoiture(Voiture $voiture, Request $request, EntityManagerInterface $entityManager)
    {
        //On vérifie que le token de la requete est valide en vérifiant si les info sont identiques à celles pour générer 
        // le token dans la vue adminAliments.html.twig.php, et si c'est la cas alors on peut réaliser les actions en BDD
        if ($this->isCsrfTokenValid("SUP". $voiture->getId(), $request->get('_token'))){
        //On prépare la requête de suppression avec remove() et on envoie en BDD avec flush()
        $entityManager->remove($voiture);
        $entityManager->flush();
        $this->addFlash("success","La suppression a été effectuée");
        return $this->redirectToRoute("admin");
        }
    }

}
