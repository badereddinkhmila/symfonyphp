<?php


namespace App\Messages\message;


/**
 * Class ColdStorageMessage
 * @package App\Messages\message
 */
class ColdStorageMessage
{
    /**
     * @var array
     */
    private array $content;

    /**
     * ColdStorageMessage constructor.
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