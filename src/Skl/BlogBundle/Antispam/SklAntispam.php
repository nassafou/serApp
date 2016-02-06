<?php
namespace Skl\BlogBundle\Antispam;



class SklAntispam
{
    /**
     *Ce service vérifie si un contenu text est un spam ou non
     *Un text est considéré comme spam a partir de 3 liens
     *ou a adresses e-mail dans son contenu
     *@param string $text
     */
    protected $mailer;
    protected $locale;
    protected $nbForSpam;
    
    
    public function _construct(\Swift_Mailer $mailer, $locale, $nbForSpam)
    {
        $this->mailer    = $mailer;
        $this->locale    = $locale;
        $this->nbForSpam = (int) $nbForSpam;
        
    }
    
    /*La seule methode public est isSpam()
     *Elle retourne true si le message donnée en argument (variable $text) est identifié comme spam
     *sinon false
     *
     */
    public function isSpam($text)
    {
        return ($this->countLinks($text) + $this->countMails($text)) >= $this->nbForSpam;
    }
    
    /**
     *Compte les URL de $text
     *@param string $text
     */
    private function countLinks($text)
    {
        //verifie s'il y a des URL dans $text
        // cherche s'il y a http ou https ou ftp:// dans le texte
        preg_match_all(
                       '#(http|https|ftp)://([A-Z0-9][A-Z0-9_-]*(?:.[A-Z0-9][A-Z0-9_-]*)+):?(d+)?/?#i',
                       $text,
                       $matches  
        ) ;
        // retourne le nombre de fois ou la recherche a été valide
       return count($matches[0]); 
    }
    private function countMails($text)
    {
        //Vérifie s'il y a des mails dans le texte
        //cherche s'il y a texte suivi de @ et suivi de texte 
        preg_match_all(
                       '#[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}#i',
                        $text,
                        $matches
                       
                       );
        // retourne le  nombre de fois ou la recherche a été valide 
        return count($matches[0]);
    }
}