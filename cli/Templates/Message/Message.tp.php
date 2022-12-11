<@php

/**
 * @range {range}
 */

use App\Core\Handlers\Response\Messages;

Messages::getInstance()
    ->addMessage('{range}1-001', 'Sua mensagem');
