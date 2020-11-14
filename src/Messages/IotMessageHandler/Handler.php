<?php


namespace App\Messages\IotMessageHandler;


use App\Messages\message\IotMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class Handler implements MessageHandlerInterface
{
    private EntityManagerInterface $entityManager;
    private MessageBusInterface $bus;

    public function __construct(EntityManagerInterface $entityManager,MessageBusInterface $bus)
    {
        $this->entityManager = $entityManager;
        $this->bus= $bus;
    }

    public function __invoke(IotMessage $message)
    {
        $data=$message->getContent();
        print ($data);
        $update = new Update('http://avcdocteur.com/sse-data', json_encode([
            $data]));
        $this->bus->dispatch($update);

    }

}