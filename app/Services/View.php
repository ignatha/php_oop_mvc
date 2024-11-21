<?php
namespace App\Services;

class View{

    protected $data = [];
    protected $content;
    protected $path;
    protected static $section = [];
    protected static $stackSection = [];
    protected static $layout;

    public function __construct($viewPath = __DIR__ . '/../View/')
    {
        $this->path = $viewPath;
    }

    public function render($view,$data = [])
    {

        $this->data = $data;
        $path = $this->path.str_replace('.','/',$view).'.php';

        if (file_exists($path)) {

            extract($this->data);

            ob_start();

            include($path);

            $this->content = ob_get_clean();

            if (self::$layout) {
                ob_start();
                include(self::$layout);
                return ob_get_clean();
            }

            return $this->content;
                        
        }
        
        throw new \Exception("View file not found: {$path}");
    }

    public static function extend($view)
    {
        $viewPath = __DIR__ . '/../View/' . str_replace('.', '/', $view) . '.php';

        if (file_exists($viewPath)) {

            self::$layout = $viewPath;

        } else {
            throw new \Exception("Partial view file not found: {$viewPath}");
        }
    }

    public static function yieldSection($name){
        echo self::$section[$name] ?? '';
    }

    public static function startSection($name)
    {
        array_push(self::$stackSection,$name);
        ob_start();
    }

    public static function endSection()
    {
        $name = array_pop(self::$stackSection);
        self::$section[$name] = ob_get_clean();

    }


    public static function include($view, $data = [])
    {
        $viewPath = __DIR__ . '/../View/' . str_replace('.', '/', $view) . '.php';

        if (file_exists($viewPath)) {

            extract($data);

            include($viewPath);

        } else {
            throw new \Exception("Partial view file not found: {$viewPath}");
        }

    }







    

    

}