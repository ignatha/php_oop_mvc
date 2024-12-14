<?php

namespace App\Services;

class Route {
    protected $routes = [];
    protected $middleware = []; // untuk menampung middleware global

    public function middleware(array $middleware)
    {
        $this->middleware = $middleware;
    }

    public function get($route,$action, $middleware = [])
    {
        $new = preg_replace('/\{([a-zA-Z0-9_]+)(:\*)?\}/', '(?P<$1>.*)', $route);
        $this->routes['GET'][$new] = compact('action','middleware'); // compact menghasilkan sebuah array berisi variable2 yang dimasukkan
    }

    public function post($route,$action, $middleware = [])
    {
        $new = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[a-zA-Z0-9_]+)', $route);
        $this->routes['POST'][$new] = compact('action','middleware');
    }

    public function run()
    {
        $url = $this->cleanUrl($_SERVER['REQUEST_URI']);
        $method = strtoupper($_SERVER['REQUEST_METHOD']);

        foreach ($this->routes[$method] as $route => $value) {
            $regex = "#^{$route}$#";
            
            if (preg_match($regex, $url, $matches)) {

                $params = array_filter($matches,'is_string',ARRAY_FILTER_USE_KEY);
                
                // tambahkan ke dalam middleware
                foreach ($value['middleware'] as $middleware) {
                    $this->middleware[] = $middleware; // disimpan di dalam property middleware
                }

                $this->action($value['action'],$params);

                return;
            }
        }

        echo "404 not found";
    }

    public function action($action,$params)
    {
        if ($action instanceof \Closure) {
            $action(...$params);
        }else {

            [$controller,$method] = explode('@',$action);
            $controller = 'App\\Controller\\'.$controller;

            if (class_exists($controller)) {
                $instance = new $controller();
                if (method_exists($instance,$method)) {

                    // $next = fn() => call_user_func_array([$instance,$method],$params); // eksekusi sebelum respond .. default eksekusi method di controller
                    $next = fn() => $instance->$method(...$params); // php v8 keatas


                    $terminate = function () {return;}; // di eksekusi sesudah respond .. default mengeksekusi kosong

                    foreach ($this->middleware as $middlewareInst) {
                        if (class_exists($middlewareInst)) { 
                            $middlewareClass = new $middlewareInst;

                            if (method_exists($middlewareClass,'handle') && method_exists($middlewareClass,'terminate')) {
                                $next = function() use($middlewareClass, $next) {
                                    return $middlewareClass->handle($_REQUEST,$next);
                                }; // mengupdate value dari next dengan method handle di middleware yang akan dijalankan sebelum user menerima respond

                                $terminate = function() use($middlewareClass, $terminate) {
                                    return $middlewareClass->terminate($_REQUEST,$terminate);
                                }; // mengupdate value dari terminate dengan method terminate middleware yang akan dijalankan setelah user menerima respond
                            }

                        }
                    }

                    $next(); // deeksekusi duluan sebelum controller / respond
                    $terminate(); // diekseskusi setelah respond
                }else {
                    echo "Method {$method} tidak ditemukan";
                }

            } else {
                echo "Controller {$controller} tidak ditemukan";
            }

        } 
    }

    protected function cleanUrl($url)
    {
        if ($url !== '/' && substr($url,-1) === '/') {
            $url = rtrim($url,'/');
        }

        $praseUrl = parse_url($url);
        $cleanUrl = $praseUrl['path'];

        return $cleanUrl;
    }

}