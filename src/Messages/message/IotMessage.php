<?php


namespace App\Messages\message;


/**
 * Class IotMessage
 * @package App\Messages\message
 */
class IotMessage
{
    /**
     * @var array
     */
    private array $content;

    /**
     * IotMessage constructor.
     * @param $content
     */
    public function __construct($content){
        $this->content=$content;
    }
    /**
     * @return array
     */
    public function getContent(): array
    {
        return $this->content;
    }
}