<?php
class Controller
{
    public function view($view, $data = [])
    {
        // session_start();
        // if (isset($_SESSION["user"])) {
        //     require_once '../app/views/' . $view . '.php';
        // }else{
        //     $data['key_encrypt'] = KEY_ENCRYPT;
        //     require_once '../app/views/login/index.php';
        // }
        require_once '../app/views/' . $view . '.php';
    }
    public function model($model)
    {
        
        set_include_path(implode(PATH_SEPARATOR, array(
            realpath(__DIR__ . '/'),
            get_include_path()
        )));
        require_once '../app/models/' . $model . '.php';
        
        return new $model;
    }
    public function script($script)
    {
        set_include_path(implode(PATH_SEPARATOR, array(
            realpath(__DIR__ . '/'),
            get_include_path()
        )));
        require_once '../app/models/script/' . $script . '.php';
        
        return new $script;
    }
    public function getFileJson($path = '../app/models/script/candaan.json')
    {
        $jsonString = file_get_contents($path);
        $jsonString = json_decode($jsonString, true);
        $nojsonString = array_rand($jsonString);
        return $jsonString[$nojsonString];
    }
}
