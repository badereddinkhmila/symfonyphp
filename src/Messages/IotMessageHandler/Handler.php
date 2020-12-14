<?php


namespace App\Messages\IotMessageHandler;


use App\Messages\message\IotMessage;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class Handler implements MessageHandlerInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus= $bus;
    }

    public function __invoke(IotMessage $message)
    {
        $data=$message->getContent();   
        {$update = new Update('http://avcdocteur.com/sse-data', json_encode(
            $data));
        $this->bus->dispatch($update);
        }    
    }

}