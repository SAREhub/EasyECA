<?php


namespace SAREhub\EasyECA\Util;


use SAREhub\Client\Message\Exchange;
use SAREhub\Client\Message\Message;

interface SplittingStrategy
{
    /**
     * @param Message $inMessage
     * @return Exchange[]
     */
    public function split(Message $inMessage): array;
}