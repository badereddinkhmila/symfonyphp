<?php


namespace App\Messages\MessageHandler;


use App\Messages\message\ColdStorageReqMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CSReqHandler implements MessageHandlerInterface
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus= $bus;
    }

    public function __invoke(ColdStorageReqMessage $message)
    {
        $content=$message->getContent();
        if(array_key_exists('isSent', $content)){
        }
        else{
        $this->bus->dispatch($message);
        }
    }
}