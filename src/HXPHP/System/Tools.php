<?php

namespace HXPHP\System;

class Tools
{
    public static function getTemplatePath(string $component, string $name, string $templateFile)
    {
        $templatePath = getenv('TEMPLATES_PATH').$component.DIRECTORY_SEPARATOR.$name.DIRECTORY_SEPARATOR.$templateFile;

        if (!file_exists($templatePath)) {
            throw new \Exception("O template nao foi localizado: <'$templatePath'>", 1);
        }

        return $templatePath;
    }

    /**
     * Criptografa a senha do usuário no padrão HXPHP.
     *
     * @param string $password Senha do usuário
     * @param string $salt     Código alfanumérico
     *
     * @return array Array com o SALT e a SENHA
     */
    public static function hashHX(string $password, string $salt = null): array
    {
        if (!$salt) {
            $salt = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
        }

        $password = hash('sha512', $password.$salt);

        return [
            'salt'     => $salt,
            'password' => $password,
        ];
    }

    /**
     * Processo de tratamento para o mecanismo MVC.
     *
     * @param string $input String que será convertida
     *
     * @return string String convertida
     */
    public static function filteredName(string $input): string
    {
        $input = explode('?', $input);
        $input = $input[0];

        $find = [
            '-',
            '_',
        ];
        $replace = [
            ' ',
            ' ',
        ];

        return str_replace(' ', '', ucwords(str_replace($find, $replace, $input)));
    }

    public static function decamelize(string $cameled, string $sep = '-'): string
    {
        return implode(
                $sep, array_map(
                        'strtolower', preg_split('/([A-Z]{1}[^A-Z]*)/', $cameled, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY)
                )
        );
    }
}
