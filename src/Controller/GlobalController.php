<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class GlobalController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(): Response
    {
        return $this->render('global/accueil.html.twig');
    }

    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder): Response
    {
        $utilisateur = new Utilisateur();
        $form=$this->createForm(InscriptionType::class, $utilisateur);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $passwordCrypt = $encoder->encodePassword($utilisateur, $utilisateur->getPassword());
            $utilisateur->setPassword($passwordCrypt);
            $utilisateur->setRoles("ROLE_USER");
            $entityManager->persist($utilisateur);
            $entityManager->flush();
            return $this->redirectToRoute("accueil");
        }
        return $this->render('global/inscription.html.twig', [
            "form"=>$form->createView()
        ]);
    }

    /**
     * @Route("/login", name="connexion")
     */
    public function login(AuthenticationUtils $util): Response
    {
        return $this->render('global/login.html.twig', [
            "lastUserName"=> $util->getLastUsername(),
            "error"=>$util->getLastAuthenticationError()
        ]);
    }

    /**
     * @Route("/logout", name="deconnexion")
     */
    public function logout()
    {
    }


}
