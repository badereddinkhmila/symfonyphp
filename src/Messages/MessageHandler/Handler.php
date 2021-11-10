<?php


namespace App\Messages\MessageHandler;


use App\Messages\message\IotMessage;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class Handler implements MessageHandlerInterface
{   
    private $bus;
    
    public function __construct(MessageBusInterface $bus)
    {  
        $this->bus= $bus;
    }

    public function __invoke(IotMessage $message)
    {   
        $data=$message->getContent();
        $content=$data[1];
        
        $gateway_id=$content['gateway_id'];
        $topic="http://avcdocteur.com/".$gateway_id."/live"; 
        
        $update = new Update([$topic], json_encode(
        $data));
        $this->bus->dispatch($update);
        }
}