<?php
/**
 * Created by PhpStorm.
 * User: Paradoxs
 * Date: 02.03.2018
 * Time: 14:00
 */

namespace App\Service;


class MessageManager
{
    private $encouragingMessages = array();
    private $discouragingMessages = array();
    public function __construct(array $encouragingMessages, array $discouragingMessages)
    {
        $this->encouragingMessages = $encouragingMessages;
        $this->discouragingMessages = $discouragingMessages;
    }
    public function getEncouragingMessage()
    {
        return $this->encouragingMessages[array_rand($this->encouragingMessages)];
    }
    public function getDiscouragingMessage()
    {
        return $this->discouragingMessages[array_rand($this->discouragingMessages)];
    }
}