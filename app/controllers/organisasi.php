<?php
class Organisasi extends Controller
{
    public function index()
    {
        $_SESSION['key_encrypt'] = KEY_ENCRYPT;
        $dataHeader['awalHeader'] = '';
        $dataHeader['title'] = '| Login';
        $dataHeader['css'] = 'css/login.css';
        $dataHeader['tambahan_css'] = '';
        $dataFooter['js'] = 'js/login.js';
        $dataFooter['tambahan_js'] = '';
        $dataFooter['dok'] = 'organisasi';
        $dataFooter['key_encrypt'] = $_SESSION['key_encrypt'];
        $this->view('templates/header_login', $dataHeader);
        $this->view('organisasi/index');
        $this->view('templates/footer', $dataFooter);
        
    }
    public function masuk()
    { 
        $data = $this->script("masuk")->masuk();
        // var_dump($data);
        echo $data;
    }
}