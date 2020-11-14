<?php


namespace App\Messages\adapters;


use App\Messages\message\IotMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class JsonSerializer implements SerializerInterface
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

        $type = $body['type'] ?? '';
        switch ($type) {
            case 'iotmessage':
                // Here, you can / should validate the structure of $body
                $message = new IotMessage($body['content']);
                break;

            default:
                throw new MessageDecodingFailedException("The type '$type' is not supported.");
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
                'type' => 'iotmessage',
                'content' => $message->getContent(),
            ]),
            'headers' => [
            ],
        ];
    }
}