<?php


namespace App\Messages\MessageHandler;


use App\Messages\message\IotMessage;
use Symfony\Component\Mercure\Update;
use App\Mercure\Cookies\CookieGenerator;
use App\Mercure\JWT\JwtProvider;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class Handler implements MessageHandlerInterface
{   
    private $bus;
    private $httpClient;
    
    public function __construct(MessageBusInterface $bus)
    {  
        $this->bus= $bus;
        $this->httpClient=HttpClient::create();
    }

    public function __invoke(IotMessage $message)
    {   
        $data=$message->getContent();
        $content=$data[1];
        
        $gateway_id=$content['gateway_id'];
        $topic="http://avcdocteur.com/".$gateway_id."/live";
        
        $request=$this->httpClient->request('GET','https://localhost/.well-known/mercure/subscriptions',[
            'auth_bearer'=>"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJtZXJjdXJlIjp7InN1YnNjcmliZSI6WyIqIl19fQ.88pc5_o1Y8dVM9VeqVrRfWQSLBYebpxrnY4CXPIU7pU"
        ]);
        $resp= json_decode($request->getContent(),true);
        $subscribers=$resp['subscriptions'];    
        $topics=array();
        foreach($subscribers as $sub){
            array_push($topics,$sub['topic']);
        } 
        
        if(in_array($topic,$topics))
        {
            $update = new Update([$topic], json_encode(
                $data));
            $this->bus->dispatch($update);
        }
        else dump('No Consumer for topic: '.$topic);
    }
}