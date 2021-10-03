<?php


namespace App\Messages\message;

/**
 * Class ColdStorageReqMessage
 * @package App\Messages\message
 */
class ColdStorageReqMessage
{
    /**
     * @var array
     */
    private array $content;

    /**
     * ColdStorageReqMessage constructor.
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