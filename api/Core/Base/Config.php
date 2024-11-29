<?php

namespace HM\Core\KC\Base;

class Config
{
    public static $config = [];

    public function load(): void
    {
        $configDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'Config';

        foreach (glob($configDir . DIRECTORY_SEPARATOR . '*.php') as $file) {
            $fileName = pathinfo($file, PATHINFO_FILENAME);

            $data = require $file;

            self::$config[$fileName] = $data;
        }
    }

    public static function set($key, $value): void
    {
        self::$config[$key] = $value;
    }

    public static function get($key, $default = null): mixed
    {
        return self::$config[$key] ?? $default;
    }
}
