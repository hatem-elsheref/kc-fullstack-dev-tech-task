<?php

namespace HM\Core\KC;

use HM\Core\KC\Base\Config;
use HM\Core\KC\Base\Database;
use HM\Core\KC\Base\DatabaseManager;

class Application
{
    public bool $isRunningInConsole = false;
    public ?string $basePath = null;
    public Request $request;
    public Route $route;
    public Config $config;
    public DatabaseManager $databaseManager;
    public Database $database;

    public static Application $app;

    public function __construct($isConsoleApp = false, $basePath = null)
    {
        if (!$isConsoleApp) {
            $this->request = new Request();

            $this->route  = new Route($this->request);
        }

        $this->isRunningInConsole = $isConsoleApp;
        $this->basePath = $basePath;

        $this->config = new Config();

        $this->config->load();

        $this->databaseManager = (new DatabaseManager(Config::get('database')[$_ENV['DB_DRIVER']], $this->basePath . DIRECTORY_SEPARATOR . 'Database'));
        $this->database = $this->databaseManager->startConnection();

        self::$app = $this;
    }

    public function start() :void
    {
        if (!$this->isRunningInConsole) {
            echo $this->route->handle();
            return;
        }

        $this->runInConsole();
    }

    private function runInConsole() : mixed
    {
        do{
            echo 'Select Operation : ' . PHP_EOL;
            echo '1) Run Migrations: ' . PHP_EOL;
            echo '2) Exit          : ' . PHP_EOL;

            $operation = trim(fgets(STDIN));

            switch ($operation) {
                case 1:
                    return $this->databaseManager->freshMigrate('Migrations');
                case 2:
                    return 0;
                default:
            }

        }while(!in_array($operation, [1, 2]));
    }

}