<?php

use App\Core\Handlers\Response\Messages;

Messages::getInstance()
    ->addMessage("E304-001", "Acesso negado à este recurso")
    ->addMessage("E304-002", "Senha inválida")
    ->addMessage("E304-003", "Senha do aplicativo inválida")
    ->addMessage("E304-004", "Senha inválida")
    ->addMessage("E304-005", "Senha inválida")
    ->addMessage("E304-006", "Senha inválida")
    ->addMessage("E304-007", "Senha inválida")
    ->addMessage("E304-008", "Algo inesperado ocorreu. Código E304-008")
    ->addMessage("E304-009", "Senha inválida")
    ->addMessage("E304-010", "Algo inesperado ocorreu ao realizar a validação da conta para transação. Tente novamente mais tarde.")
    ->addMessage("E304-011", "Senha inválida")
    ->addMessage("E304-012", "Seu usuário não possui permissão para acessar este recurso.")
    ->addMessage("E304-013", "Seu usuário não possui permissão para acessar este recurso.")
    ->addMessage("E304-014", "Conta não encontrada")
    ->addMessage("E304-015", "Informe a senha do aplicativo");
