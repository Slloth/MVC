<?php

namespace Core;

class DotEnvEnvironment
{

   public static function dotEnvLoad($path = __DIR__."/.."): void  
   {
        $lines = file_exists($path.'/.env.local') ? file($path . '/.env.local'): file($path . '/.env');
        foreach ($lines as $line) {
            [$key, $value] = explode('=', $line, 2);
            preg_match_all('/\${(.*?)}/', $value, $match);
            /**
             * Permet d'utiliser interpreter les variables dans les fichier .env
             */
            if(!empty($match[0])){
                for($i = 0; $i < count($match); $i++){
                    $value = str_replace($match[0][$i],$_ENV[$match[1][$i]],$value);
                }
            }
            
            $key = trim($key);
            $value = trim($value);
            
            putenv(sprintf('%s=%s', $key, $value));
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}