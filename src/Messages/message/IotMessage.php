<?php


namespace App\Messages\message;


/**
 * Class IotMessage
 * @package App\Messages\message
 */
class IotMessage
{
    /**
     * @var string
     */
    private string $content;

    /**
     * IotMessage constructor.
     * @param $content
     */
    public function __construct($content){
        $this->content=$content;
    }
    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }
}