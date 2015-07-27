<?php

namespace Login\LoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Login\LoginBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    //Action qui permet la connexion de l'utilisateur

    public function indexAction(Request $request)
    {
        //var_dump($request->getMethod());
        if($request->getMethod()=='GET')
            return $this->render('LoginLoginBundle:Default:login.html.twig');
        elseif ($request->getMethod() == 'POST') {
            $username = $request->get('username');
            $password = $request->get('password');
            $em = $this->getDoctrine()->getEntityManager();
            $repository = $em->getRepository('LoginLoginBundle:User');
            $user = $repository->findOneBy(array('userName' => $username, 'password' => $password));
            if ($user) {
                return $this->render('LoginLoginBundle:Default:bienvenue.html.twig', array('name' => $user->getFirstName()));
            }
            else {
                return $this->render('LoginLoginBundle:Default:index.html.twig', array('erreur' => "Erreur d'authetification"));
            }
        }
        else {
            return $this->render('LoginLoginBundle:Default:index.html.twig');
        }
    }
    //action qui permet l inscription
    public function inscriptionAction(Request $request){
        if($request->getMethod()=='POST')
        {
            $username = $request->get('pseudonyme');
            $prenom = $request->get('prenom');
            $motdepasse = $request->get('motdepasse');
            $utilisateur = new User();
            $utilisateur->setFirstName($prenom);
            $utilisateur->setUserName($username);
            $utilisateur->setPassword($motdepasse);
            $em=$this->getDoctrine()->getManager();
            $em->persist($utilisateur);
            $em->flush();
        }

        return $this->render('LoginLoginBundle:Default:inscription.html.twig');


    }


    public function deconnexionAction(Request $request){
            $session=$this->getRequest()->getSession();
            $session->clear();
        return $this->render('LoginLoginBundle:Default:login.html.twig');

    }
    public function listerAction()
    {
        var_dump($this->get('session')->isStarted());
        $user=$this->get('security.context')->getToken()->getUser();
        var_dump($user);

        $em=$this->getDoctrine()->getManager();
        $username = $em->getRepository('LoginLoginBundle:User')->findBy(array('userName'=>$user));
        var_dump($username);
        return $this->render('LoginLoginBundle:Default:lister.html.twig', array(
            'user' => $username));

    }



}
