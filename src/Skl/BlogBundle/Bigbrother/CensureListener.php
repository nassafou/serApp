<?php
// src/Sdz/BlogBundle/Bigbrother/CensureListener.php
namespace Sdz\BlogBundle\Bigbrother;
use Symfony\Component\Security\Core\User\UserInterface;
class CensureListener
{
// Liste des id des utilisateurs à surveiller
protected $liste;
protected $mailer;
public function __construct(array $liste, \Swift_Mailer $mailer)
{
$this->liste = $liste;
$this->mailer = $mailer;
}
// Méthode « reine » 1
protected function sendEmail($message, UserInterface $user)
{
$message = \Swift_Message::newInstance()
->setSubject("Nouveau message d'un utilisateur surveillé")
->setFrom('admin@votresite.com')
->setTo('admin@votresite.com')
->setBody("L'utilisateur surveillé '".$user->getUsername()."' a posté le message suivant : '".$message."'");

$this->mailer->send($message);
}



// Méthode « technique » de liaison entre l'évènement et lafonctionnalité reine
public function onMessagePost(MessagePostEvent $event)
{
// On active la surveillance si l'auteur du message est dans laliste
if (in_array($event->getUser()->getId(), $this->liste)) {
// On envoie un e-mail à l'administrateur
$this->sendEmail($event->getMessage(), $event->getUser());
// On censure le message
$message = $this->censureMessage($event->getMessage());
// On enregistre le message censuré dans l'event
$event->setMessage($message);
}
}
}
/