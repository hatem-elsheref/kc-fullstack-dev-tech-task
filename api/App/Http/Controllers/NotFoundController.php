<?php

namespace App\Http\Controllers;

class NotFoundController extends Controller
{
    public function routeNotFound($method, $path): string
    {
        return sprintf('<h5>Route [%s] not found: %s</h5>', $method, $path);
    }

    public function controllerNotFound($controller): string
    {
        return sprintf('<h5>Controller not found: %s</h5>', $controller);
    }

    public function actionNotFound($action): string
    {
        return sprintf('<h5>Action not found: %s</h5>', $action);
    }
}
