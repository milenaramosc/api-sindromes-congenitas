<?php

namespace CLI\Utils;

class Str
{
    public static function camelCase(string $str, array $noStrip = [])
    {
        // non-alpha and non-numeric characters become spaces
        $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
        $str = trim($str);
        // uppercase the first character of each word
        $str = ucwords($str);
        $str = str_replace(" ", "", $str);

        return lcfirst($str);
    }

    public static function pascalCase(string $str, array $noStrip = [])
    {
        // non-alpha and non-numeric characters become spaces
        $str = preg_replace('/[^a-z0-9' . implode("", $noStrip) . ']+/i', ' ', $str);
        $str = trim($str);
        // uppercase the first character of each word
        $str = ucwords($str);
        $str = str_replace(" ", "", $str);

        return ucfirst($str);
    }

    public static function snakeCase(string $str)
    {
        return ltrim(
            strtolower(preg_replace(
                '/[A-Z]([A-Z](?![a-z]))*/',
                '_$0',
                $str
            )),
            '_'
        );
    }

    public static function has(string $str, string $search): bool
    {
        return strpos($str, $search) !== false;
    }

    /**
     * Adiciona $add no final de $str
     * caso $add não exista em $str
     *
     * @param string $str
     * @param string $add
     * @return string
     */
    public static function addToEndNotHaving(string $str, string $add): string
    {
        if (self::has($str, $add)) {
            return $str;
        }

        return "$str$add";
    }

    /**
     * Adiciona $add no início de $str
     * caso $add não exista em $str
     *
     * @param string $str
     * @param string $add
     * @return string
     */
    public static function addToStartNotHaving(string $str, string $add): string
    {
        return "$add$str";
    }

    /**
     * @param string $str
     * @param string $remove
     * @return string
     */
    public static function removeHaving(string $str, string $remove): string
    {
        if (self::has($str, $remove)) {
            return str_replace($remove, "", $str);
        }

        return $str;
    }

    public static function nameFormat(string $str)
    {
        return trim(preg_replace("([A-Z])", " $0", $str));
    }
}
