<?php


namespace App\Messages\IotMessageHandler;


use App\Entity\User;
use App\Messages\message\IotMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Core\Security;

class Handler implements MessageHandlerInterface
{
    private MessageBusInterface $bus;
    private Security $security;
    private EntityManagerInterface $manager;

    public function __construct(MessageBusInterface $bus,Security $security,EntityManagerInterface $manager)
    {
        $this->bus= $bus;
        $this->security = $security;
    }

    public function __invoke(IotMessage $message)
    {
        $user = $this->security->getUser();
        $data=$message->getContent();
        $gateway_id=$data[1];
        $topic="http://avcdocteur.com/".$gateway_id;
        {$update = new Update([$topic], json_encode(
            $data));
        $this->bus->dispatch($update);
        }    
    }

}