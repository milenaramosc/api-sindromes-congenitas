<?php

namespace App\Core\Handlers\Middlewares;

class Seguranca
{
    public static function noInjection($json)
    {
        // remove palavras que contenham sintaxe sql
        //$json = preg_replace("/(from|select|insert|delete|where|drop table|show tables||\||\\\\)/i","",$json);
        $json = trim($json);
        //limpa espaços vazio
        $json = strip_tags($json);
        //tira tags html e php
        //$json = addslashes($json);//Adiciona barras invertidas a uma string
        return $json;
    }
}
