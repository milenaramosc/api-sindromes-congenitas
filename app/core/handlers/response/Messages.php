<?php

namespace App\Core\Handlers\Response;

use Exception;

class Messages
{
    private static array $messages = [];
    private static ?self $instance = null;

    private function __construct()
    {
    }

    public static function getInstance()
    {
        self::$instance ??= new Messages();
        return self::$instance;
    }

    /**
     * Get the value of messages
     */
    public static function getMessages()
    {
        return self::$messages;
    }

    public static function getMessageByCode(string $cod): string
    {
        $message = self::getMessages()[$cod];
        if ($message === null) {
            throw new Exception("Code '$cod' not set");
        }
        return $message;
    }

    /**
     * Set the value of messages
     *
     * @return self
     */
    public function addMessage(string $cod, string $message): self
    {
        if (isset(self::$messages[$cod])) {
            throw new Exception("Code '$cod' already declared");
        }
        self::$messages[$cod] = $message;
        return $this;
    }
}
