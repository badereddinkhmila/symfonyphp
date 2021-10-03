<?php

namespace App\Messages\adapters;
use App\Messages\message\ColdStorageMessage;
use App\Messages\message\ColdStorageReqMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class ColdStorageSerializer implements SerializerInterface
{
    /**
     * @inheritDoc
     */
    public function decode(array $encodedEnvelope): Envelope
    {
        $body = json_decode($encodedEnvelope['body'],true);
            if(gettype($body['body'])== "string"){
            $body= json_decode($body['body'],true);
            }
            elseif(gettype($body['body'])== "array"){
                $body=$body['body'];
            }
        if (!$body) {
            throw new MessageDecodingFailedException('The body is not a valid JSON.');
        }
        $type = $body['type'] ?? '';
        if ($type == 'cold_storage') {
                $content=array_values($body);
                $message = new ColdStorageMessage($content);
                return new Envelope($message);
        }
        elseif($type == 'cold_storage_req'){
            $content=array_values($body);
            $content['isSent']=true;
            $message = new ColdStorageReqMessage($content);
            return new Envelope($message);
        }
    }

    /**
     * @inheritDoc
     */
    public function encode(Envelope $envelope): array
    {
        $message = $envelope->getMessage();
        if ($message instanceof ColdStorageReqMessage) return [
            'body' => json_encode([
                'type' => 'cold_storage_req',
                'content' => $message->getContent(),
            ]),
            'headers' => [
            ],
        ];
    }
}