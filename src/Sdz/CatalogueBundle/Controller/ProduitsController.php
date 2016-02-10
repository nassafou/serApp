<?php
namespace Sdz\CatalogueBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sdz\CatalogueBundle\Entity\Cproduits;
use Sdz\CatalogueBundle\Form\CproduitsType;

class ProduitsController extends Controller
{
    public function listerAction()
    {
        $em       = $this->getDoctrine()->getManager();//cré l'entity manager
        $produits = $em->getRepository('CatalogueBundle:Cproduits')->findAll();//récupération des données de la base   
        return      $this->render('CatalogueBundle:Default:lister.html.twig', array('produits' => $produits));
    }
    
     public function ajouterAction()
    {
        $em       = $this->getDoctrine()->getManager();//Entity manager
        $produits = new Cproduits(); //objet
        $form     = $this->createForm(New CproduitsType(), $produits);//formulaire
        $request  = $this->get('request');//requete
        if( $request->getMethod() == 'POST')//Condition si c'est un post ou c'est un get
        {
                $form->bind($request);//lier la requete au formulaire
            if( $form->isValid())   //Condition si le formulaire
            {
                $em->getDoctrine()->getManger();//persisté
                $em->persist($produits);
                $em->flush();// enrégistrer le produits
                return $this->redirect($this->generateUrl('catalogue_accueil'));//retourné a la page d'accueil
            }   
        }    
        return $this->render('CatalogueBundle:Default:ajouter.html.twig', array('form' => $form->createView()));
    }
    
     public function modifierAction($id)
    {
        if(!isset( $id))
        {
           throw   $this->createNotFoundException('Le '.$id.'est inexitant'); 
        }
        $em      = $this->getDoctrine()->getManager();
        $produit = $em->getRepository('CatalogueBundle:Cproduits')->find($id);// récupération du produit
        $form    = $this->createForm(New CproduitsType(), $produit);// génération du formulaire 
        $request = $this->get('request');//request
        if(        $request->getMethod() == 'POST') //vérification de la condition de post ou get
        {
                   $form->bind($request);// lié le formulaire a la requete   
            if(    $form->isValid())//vérification de la validation du formulaire
            {
                   $em = $this->getDoctrine()->getManager();//entity
                   //$em->persist($produit);// persisté
                   $em->flush();//enrégistrement
                   return $this->redirect($this->generateUrl('catalogue_accueil'));//redirection vers la page d'accueil
            }
        }
        return     $this->render('CatalogueBundle:Default:modifier.html.twig', array('id'     => $id,
                                                                                     'form'   => $form->createView()));
    }
    
    public function supprimerAction($id)
    {
        if(!isset(    $id))//vérification de la validité
        {
            throw     $this->createNotFoundException('Id introuvable');
        }
        
        $em = $this->getDoctrine()->getmanager();
        $produit = $em->getRepository('CatalogueBundle:Cproduits')->find($id);
        $em->remove($produit);
        $em->flush();
         return $this->redirect($this->generateUrl('catalogue_accueil'));
        
        
    }
    
     public function AsupprimerAction($id)
    {
        
        if(!isset(    $id))//vérification de la validité
        {
            throw     $this->createNotFoundException('Id introuvable');
        }
          $form    =  $this->createFormBuilder()->getForm();//générer un formulaire
          $em      =  $this->getDoctrine()->getManager();// entity
          $produit =  $em->getRepository('CatalogueBundle:Cproduits')->find($id);//recupération de la données
          
          $request =  $this->get('request');//création de la requete
          //var_dump($request);
          //die();
        if($request->getMethod() == 'POST')
        {
                $form->bind($request);//lié la requete au formulaire
                //var_dump($form);
                //die();
            if( $form->isValid())//Vérification de la validité du formulaire
            {
                $em = $this->getDoctrine()->getManager();//entity
                $produit = $em->getRepository('CatalogueBundle:Cproduit')->find($id);
                //var_dump($produit);
                //die();
                $em->remove($produit);//supprimer
                $em->flush();//enrégistré
                //$message = 'élément supprimé';
                return $this->redirect($this->generateUrl('catalogue_accueil'));
            }
        }
        //si la requete est de type GET généré le formulaire de confirmation 
        return      $this->render('CatalogueBundle:Default:supprimer.html.twig', array('form' => $form,
                                                                                      'id'   => $id,
                                                                                      'produit' => $produit));
    }
}
