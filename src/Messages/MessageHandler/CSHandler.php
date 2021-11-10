<?php


namespace App\Messages\MessageHandler;


use Symfony\Component\Mercure\Update;
use App\Messages\message\ColdStorageMessage;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CSHandler implements MessageHandlerInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus= $bus;
    }

    public function __invoke(ColdStorageMessage $message)
    {
        $data=$message->getContent();
        $gateway_id=$data[2];
        $topic="http://avcdocteur.com/".$gateway_id."/update";
        {$update = new Update([$topic], json_encode(
            $data[1]));
        $this->bus->dispatch($update);
        }
    }
}