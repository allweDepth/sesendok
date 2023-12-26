<?php
class Register extends Controller
{
    public function index()
    {
        if (KEY_ENCRYPT) {
            session_start();
            //var_dump($_SESSION);
            $_SESSION["key_encrypt"] = KEY_ENCRYPT;
            // var_dump($_SESSION);
        }
        $dataHeader['awalHeader'] = '';
        $dataHeader['title'] = '| Register';
        $dataHeader['css'] = 'css/login.css';
        $dataHeader['tambahan_css'] = '';
        $dataFooter['js'] = 'js/login.js';
        $dataFooter['tambahan_js'] = '';
        $dataFooter['key_encrypt'] = KEY_ENCRYPT;
        $this->view('templates/header', $dataHeader);
        $this->view('register/index');
        $this->view('templates/footer',$dataFooter);
    }
    public function register()
    {
        $data = $this->script("register")->register();
        echo $data;
    }
}
