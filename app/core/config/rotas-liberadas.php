<?php

// Rotas Liberadas

/**
 * Rotas que não precisam de autenticação para executar
 * @var array
 */


const ROTAS_LIBERADAS = [
    '/atendimento/iniciar' . "POST",
    '/gerar/relatorio' . "GET",
];

/**
 * Rotas de terceiros - autenticação via /v2/authentication
 * @var array
 */
const ROTAS_TERCEIROS = [];
