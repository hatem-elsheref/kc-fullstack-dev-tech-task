<?php

namespace HM\Core\KC;

use ReflectionClass;
use App\Http\Controllers\NotFoundController;
class Route
{
    public static $routes = [];

    private Request $request;
    const string GET_METHOD = 'get';

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public static function get($path, $callback) :void
    {
        self::$routes[self::GET_METHOD][$path] = $callback;
    }


    /**
     * @throws \ReflectionException
     */
    public function handle() :mixed
    {
        try {
            $currentPath = $this->request->path(false);

            $routes = self::$routes[$this->request->method()];

            foreach ($routes as $path => $callback) {

                if ($this->match($currentPath, $path)) {
                    if (is_callable($callback)) {
                        return call_user_func_array($callback, $this->request->params());
                    }

                    if (is_string($callback) || is_array($callback)) {

                        $controllerAndAction = is_string($callback) ? explode('@', $callback) : $callback;

                        $reflection = new ReflectionClass($controllerAndAction[0]);

                        if (!$reflection->hasMethod($controllerAndAction[1])){
                            return (new NotFoundController())->actionNotFound($controllerAndAction[1]);
                        }

                        $controller = $reflection->newInstance();

                        return call_user_func_array([$controller, $controllerAndAction[1]], $this->request->params());
                    }

                }
            }

        }catch (\Exception $exception){
            if ($exception instanceof \ReflectionException){
                return (new NotFoundController())->controllerNotFound($controllerAndAction[0]);
            }
        }

        return (new NotFoundController())->routeNotFound($currentPath);
    }

    public function match($currentPath , $routePath) :bool
    {
        $segments = explode('/', trim($routePath, '/'));

        $currentPathSegments = explode('/', trim($currentPath, '/'));

        $routePathIsMatched = count($segments) === count($currentPathSegments);

        if (!$routePathIsMatched) {
            return false;
        }

        $this->request->removeParams();

        foreach ($segments as $index => $segment) {
            if (preg_match('/^{([a-zA-Z0-9]+)}$/', $segment, $param)) {
                $this->request->setParam($param[1], $currentPathSegments[$index]);
            }elseif ($segment !== $currentPathSegments[$index]) {
                return false;
            }
        }

        return true;
    }



}