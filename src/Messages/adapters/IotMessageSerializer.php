<?php


namespace App\Messages\adapters;


use App\Messages\message\IotMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class IotMessageSerializer implements SerializerInterface
{

    /**
     * @inheritDoc
     */
    public function decode(array $encodedEnvelope): Envelope
    {   
        
        $body = json_decode($encodedEnvelope['body'], true);
        if (!$body) {
            throw new MessageDecodingFailedException('The body is not a valid JSON.');
        }
        $body=$body['body'];
        $topic = $body['topic'] ?? '';
        switch ($topic) {
            case 'avc/temperature':
                // Here, you can / should validate the structure of $body
                $content=array_values($body);
                //dump($content);
                $message = new IotMessage($content);
                break;
            case 'avc/blood_pressure':
                // Here, you can / should validate the structure of $body
                $content=array_values($body);
                //dump($content);
                $message = new IotMessage($content);
                break;
            case 'avc/oxygen':
                // Here, you can / should validate the structure of $body
                $content=array_values($body);
                //dump($content);
                $message = new IotMessage($content);
                break;
            case 'avc/glucose':
                // Here, you can / should validate the structure of $body
                $content=array_values($body);
                //dump($content);
                $message = new IotMessage($content);
                break;
            case 'avc/glucose':
                // Here, you can / should validate the structure of $body
                $content=array_values($body);
                //dump($content);
                $message = new IotMessage($content);
                break;
            default:
                throw new MessageDecodingFailedException("The topic '$topic' is not accessible for this application.");
        }

        return new Envelope($message);
    }

    /**
     * @inheritDoc
     */
    public function encode(Envelope $envelope): array
    {
        $message = $envelope->getMessage();

        if ($message instanceof IotMessage) return [
            'body' => json_encode([
                'content' => $message->getContent(),
            ]),
            'headers' => [
            ],
        ];
    }

}