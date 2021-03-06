<?php

namespace Skl\BlogBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends EntityRepository
{
    
    
     public function recherche($chaine)
    {
        // création d'un objet
        
        
        $qb = $this->createQueryBuilder('u')
                   ->select('u')
                   ->where('u.nom like :chaine')
                   ->andWhere('u.disponible = 1')
                   ->orderBy('u.id ')
                   ->setParameter('chaine', $chaine);
                   
        return $qb->getQuery()->getResult();
        
        
    }
    
    public function myFindAll()
    {
        $qb = $this->createQueryBuilder('a')
                    ->getQuery()
                    ->getResult();
                    
            return $qb;
    }
    
    public function myFindOne($id)
    {
        $qb = $this->_em->createQueryBuilder();
        
        $qb->select('a')
           ->from('BlogBundle:Article', 'a')
           ->where('a.id = :id')
           ->setParametrer('id', $id);
           
           return $qb->getQuery()
                     ->getResult();
    }
    
    public function findAuteurAndDate($auteur, $annee)
    {
        $qb = $this->createQueryBuilder('a')
                   ->select('a')
                   ->from('BlogBundle:Article', 'a')
                   ->where('a.auteur = :auteur')
                   ->setParameter('auteur', $auteur)
                   ->andWhere('a.date < :annee')
                   ->setParameter('annee', $annee)
                   ->orderBy('a.date', 'DESC');
                   
                   return $qb->getQuery()
                             ->getResult();
    }
    
    public function getArticleAvecCommentaires()
    {
        $qb = $this->createQueryBuilder('a')
                   ->leftJoin('a.commentaies', 'c')
                   ->addSelect('c');
        
        return $qb->getQuery()
                  ->getResult();
        
    }
}