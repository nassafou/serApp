<?php

namespace Skl\BlogBundle\Beta;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class BetaListener
{
    /*La date de fin de la version bêta
     *Avant cette date, on affichera un compte à rebours (J-3) par exemple
     *Après cette date on n'affichera plus le <<beta>>
     */
    protected $dateFin;
    
    public function __construct($dateFin)
    {
        $this->dateFin = new \DateTime($dateFin);
    }
    
    public function onKernelResponse(FilterResponseEvent $event)
    {
        // On teste si la requete est bien la requete principale
        if(HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()){
            return;
        }
        // On recupere la réponse que le noyau à inseré dans l'évenement
        $response = $event->getResponse();
        
        //Ici on modifie comme  on veut la réponse
        $joursRestant = $this->dateFin->diff(new \DateTime())->days;
        
        if( $joursRestant > 0 ){
            // On utilise notre méthode<<reine>> (appel la methode display)
            $response = $this->displayBeta($event->getResponse(), $joursRestant);
        }
        
        //Puis on insere la réponse modifiée dans l'évenement
        $event->setResponse($response);
    }
    
    //Méthode pour ajouter le <<bêta>> à une réponse
    protected function displayBeta(Response $response, $joursRestant)
    {
        $content = $response->getContent();
        
        //Code à rajouter
        $html = '<span style="color: red; font-size: 0.5em;"> -Beta J-'.(int) $joursRestant.' !</span>';
        
        // Insertion du code dans la page, dans le <h1> du header
        $content = preg_replace('#<h1>(.*?)</h1>#iU','<h1>$1'.$html.'</h>',
                                $content,
                                1);
        
        //Modification du content dans la réponse
        $response->setContent($content);
        
        return $response;
        
    }
 }