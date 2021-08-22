<?php

namespace App\Console\Commands\Custom\Helper;

use Exception;

trait CustomCommandHelper
{
    protected function strReplace(string $string, array $key_values, bool $preg = false)
    {
        foreach ($key_values as $key => $value) {
            $string = $preg ? preg_replace($key, $value, $string) : str_replace($key, $value, $string);
        }
        return $string;
    }

    protected function writeToFile(string $path, $data)
    {
        $dir = implode("/", array_slice(explode("/", $path), 0, -1));
        if (file_exists($path)) throw new Exception("$path file already exists");
        if (!is_dir($dir)) mkdir($dir, 0754, true);
        file_put_contents($path, $data);
    }
}
