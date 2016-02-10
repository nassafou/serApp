<?php

namespace Skl\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Skl\BlogBundle\Entity\Article;
use Skl\BlogBundle\Form\ArticleType;
use Skl\BlogBundle\Form\ProduitsType;
use Skl\BlogBundle\Entity\Produits;
use Skl\BlogBundle\Form\RechercheType;

class ProduitsController extends Controller
{
    /*Affichage des articles a la page d'accueil
     */
    public function indexAction()
    {
         // Appel de l'entity
         $em       = $this->getDoctrine()->getManager();
         //Recupération des données de la base
         $articles = $em->getRepository('BlogBundle:Produits')->findAll();
         // envoie a la vue
         $message  = "ici";
         //var_dump($articles);
         //die();
        return $this->render('BlogBundle:Produits:index.html.twig', array('produits' => $articles,
                                                                          'message'  => $message));
    }
    
    
    public function voirAction($id)
    {
        // Vérification de la validité de l'id
        if(!isset($id)){
            throw $this->createNotFoundException('Not found id ');
        }
        // entity
        $em      = $this->getDoctrine()->getManager();
        //recuperation de l'article à voir
        $article = $em->getRepository('BlogBundle:Produits')->find($id);
        
        return $this->render('BlogBundle:Produits:voir.html.twig', array('id'      => $id,
                                                                         'article' => $article));
    }
    
    public function ajouterAction()
    {
        //entiy
        $em      = $this->getDoctrine()->getManager();
        //création de l'objet
        $article = new Produits();
        //Creation du formulaire
        $form    = $this->createForm(new ProduitsType(), $article );
        //créationd de la requete
        $request = $this->get('request');
        // vérifier si la requete est GET ou POST
        if($request->getMethod() == 'POST')
        {
            //lié le formulaire
            $form->bind($request);
            // Vérification
            if($form->isValid())
            {
            //persist
            $em->persist($article);
            $em->flush();   
            // redirect
            return $this->redirect($this->generateUrl('accueil'));
            }
        }
        return $this->render('BlogBundle:Produits:ajouter.html.twig', array('form' => $form->createView()));
    }
    
    public function modifierAction($name)
    {
        //Vérification
        if(!isset( $id))
        {   //redirection
            return $this->redirect($this->generateUrl('ajouter'));
        }
        $em      = $this->getDoctrine()->getManager();
        // recupération de l'id
        $article = $em->getReposite('BlogBundle:Produits')->find($id);
        //Formulaire
        $form    = $this->createForm(new ProduitsType(), $article);
        $request = $this-get('request');
        //vérification
        if($request->getMethod()== 'POST')
        {     //lié le formulaire et la requete
              $form->bind($request);
              // Vérification de la validation du formulaire
            if($form->isValid()){
              //persit
              $em->persist($article);
              $em->flush();
              // accueil
              return $this->redirect($this->generateUrl('accueil'));
            }
        }
        return $this->render('BlogBundle:Produits:ajouter.html.twig', array( 'form' => $form->createView(),
                                                                             'id'   => $id  ));
    }
    
    public function supprimerAction(Produits $article)
    {
        $form    = $this->createFormBuilder()->getForm();
        // vérification
        if(!isset($id)){
            throw  $this->createNotFoundException('404, not Found');
        }
        $request = $this->getRequest();
        if($request->getMethod()== 'POST'){
            
            if($form->isValid())
            {
                $em      = $this->getDoctrine()->getManager();
                $article = $em->getRepository('BlogBundle:Produits')->find($id);
                $em->remove($article);
                $em->flush();
                // On définit un message flash
                $this->get('session')->getFlashBag()->add('info','Article bien supprimé');
                //Puis on redirige vers l'accueil
                return $this->redirect($this->generateUrl('accueil'));
            }
        }
        // si la réponse est en get, on affiche une page de confirmation avant de supprimé
        return $this->render('BlogBundle:Produits:suprimer.html.twig', array('article' => $article,
                                                                             'form'    => $form->createView()));
    }
    public function menuAction($nombre)
    {
        $em    = $this->getDoctrine()->getManager();
        $liste = $em->getRepository('BlogBundle:Produits')->findAll();
        
        return $this->render('BlogBundle:Produits:menu.html.twig', array('liste_articles' => $liste));
        
    }
    
    public function listerAction(Produits $produits)
    {
        $em   = $this->getDoctrine()->getManager();
        $form = $this->createForm( new RechercheType(), $produits); 
        return  $this->render('BlogBundle:Produits:lister.html.twig', array('form'     => $form->createView(),
                                                                           'produits' => $produits));   
    }
    
     public function rechercheAction()
    {
        //$em = $this->getDoctrine()->getManager();
        //$liste = $em->getRepository('BlogBundle:Produits')->recherche($chaine);
        $form = $this->createForm( new RechercheType()); 
        return  $this->render('BlogBundle:Produits:recherche.html.twig', array('form' => $form->createView() ));   
    }
    
    public function rechercheTraitementAction()
    {
            $form = $this->createForm( new RechercheType());    
        if( $this->get('request')->getMethod() == 'POST'){
            $form->bind($this->get('request'));
            echo $form['recherche']->getData();
            //die();
            $em = $this->getDoctrine()->getManager();
            $produits = $em->getRepository('BlogBundle:Produits')->recherche($form['recherche']->getData());
        }else{
            throw $this->createNotFoundException('La page n\'exist' );
        }
       return $this->render('BlogBundle:Produits:index.html.twig', array('produits' => $produits));
    } 
    
    public function testAction()
    {
       //On recupere le service d'abord
       $antispam = $this->container->get('blog.antispam');
       $text     =" tttt@gmail.com; hhhhj@yahoo.fr;  hhigigv@gygugy.vb" ;
       if($antispam->isSpam($text))
       {
        throw new \Exception('ce text est un spam');
       } 
    }
    
    public function test2Action()
    {
        //entity
        $em         = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('BlogBundle:Article')
                         ->myFindAll();
        
        return $this->render('BlogBundle:Blog:test.html.twig', array( 'liste' => $repository));
        
    }
}
