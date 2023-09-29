<?php

namespace Core;

class DotEnvEnvironment
{

   public static function load($path = __DIR__."/.."): void  
   {
        $lines = file_exists($path.'/.env.local') ? file($path . '/.env.local'): file($path . '/.env');
        foreach ($lines as $line) {
            [$key, $value] = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);

            putenv(sprintf('%s=%s', $key, $value));
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
   }
}