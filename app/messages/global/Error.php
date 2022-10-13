<?php

/**
 * @range E000
 */

use App\Core\Handlers\Response\Messages;

Messages::getInstance()
    ->addMessage('E000-000', 'Ocorreu um erro interno. Entre em contato com o suporte!')
    ->addMessage('E000-001', 'Caminho não encontrado!')
    ->addMessage('E000-002', 'Validação não definida!')
    ->addMessage('E000-003', 'Não foi possível realizar a conexão com o banco!')
    ->addMessage('E000-004', 'Não foi possível realizar a conexão com o banco!')
    ->addMessage('E000-005', 'Não foi possível realizar a conexão com o banco!')
    ->addMessage('E000-006', 'Não foi possível verificar o CEP!')
    ->addMessage('E000-007', 'Não foi possível criar as seeds para services_bmp_tokens')
    ->addMessage('E000-008', 'Algo inesperado ocorreu. Código E000-008.');
