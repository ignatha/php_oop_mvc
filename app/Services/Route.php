<?php

namespace App\Services;

class Route {
    protected $routes = [];
    protected $middleware = [];

    public function middleware(array $middleware)
    {
        $this->middleware = $middleware;
    }

    public function get($route,$action, $middleware = [])
    {
        $new = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[a-zA-Z0-9_/-]+)', $route);
        $this->routes['GET'][$new] = compact('action','middleware'); // compact menghasilkan sebuah array berisi variable2 yang dimasukkan
    }

    public function post($route,$action, $middleware = [])
    {
        $new = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[a-zA-Z0-9_]+)', $route);
        $this->routes['POST'][$new] = compact('action','middleware');
    }

    public function run()
    {
        $url = $_SERVER['REQUEST_URI'];
        $method = strtoupper($_SERVER['REQUEST_METHOD']);

        foreach ($this->routes[$method] as $route => $value) {
            $regex = "#^{$route}$#";
            
            if (preg_match($regex, $url, $matches)) {

                $params = array_filter($matches,'is_string',ARRAY_FILTER_USE_KEY);
                
                // tambahkan ke dalam middleware
                foreach ($value['middleware'] as $middleware) {
                    $this->middleware[] = $middleware;
                }

                $this->action($value['action'],$params);

                return;
            }
        }

        echo "404 not found";
    }

    public function action($action,$params)
    {
        [$controller,$method] = explode('@',$action);
        $controller = 'App\\Controller\\'.$controller;

        if (class_exists($controller)) {
            $instance = new $controller();
            if (method_exists($instance,$method)) {

                $next = fn() => call_user_func_array([$instance,$method],$params); // eksekusi action

                $terminate = function () {return;};
                // jalankan middleware sebelum action controller
                foreach ($this->middleware as $middlewareInst) {
                    if (class_exists($middlewareInst)) { 
                        $middlewareClass = new $middlewareInst;

                        if (method_exists($middlewareClass,'handle') && method_exists($middlewareClass,'terminate')) {
                            $next = function() use($middlewareClass, $next) {
                                return $middlewareClass->handle($_REQUEST,$next);
                            };

                            $terminate = function() use($middlewareClass, $terminate) {
                                return $middlewareClass->terminate($_REQUEST,$terminate);
                            };
                        }

                    }
                }

                $next();
                $terminate();
            }else {
                echo "Method {$method} tidak ditemukan";
            }

        } else {
            echo "Controller {$controller} tidak ditemukan";
        }
    }

}