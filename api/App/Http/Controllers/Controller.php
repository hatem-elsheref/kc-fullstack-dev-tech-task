<?php

namespace App\Http\Controllers;

use HM\Core\KC\Application;
use HM\Core\KC\Base\Database;
use HM\Core\KC\Request;
use HM\Core\KC\Base\Config;

class Controller
{
    protected Request $request;
    protected Database $database;
    protected Config $config;
    public function __construct()
    {
        $this->request  = Application::$app->request;
        $this->database = Application::$app->database;
        $this->config   = Application::$app->config;
    }
}
