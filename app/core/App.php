<?php

class App
{
    protected $controller = 'Home';
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseURL();

        // controller
        if (file_exists('../app/controllers/' . $url[0] . '.php')) {
            // nilai controller diisi dengan array pertama di dalam url
            $this->controller = $url[0];
            // hilangkan array pertama di dalam url
            unset($url[0]);
        }

        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;

        // method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // params
        if (!empty($url)) {
            $this->params = array_values($url);
        }

        // jalankan controller dan method serta kirimkan params jika ada
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseURL()
    {
        if (isset($_GET['url'])) {
            // untuk membuang tanda '/' di akhir url
            $url = rtrim($_GET['url'], '/');
            // untuk membersihkan url daripada karakter-karakter yang pelik
            $url = filter_var($url, FILTER_SANITIZE_URL);
            // untuk memecahkan url berdasarkan tanda '/'
            $url = explode('/', $url);
            return $url;
        }
    }
}
