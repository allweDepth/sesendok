<?php
class Login extends Controller
{
    public function index()
    {
        if (KEY_ENCRYPT) {
                
            //var_dump($_SESSION);
            $_SESSION["key_encrypt"] = KEY_ENCRYPT;
            // var_dump($_SESSION);
        } else {
            //header("Location: login");
        }
        $dataHeader['awalHeader'] = '';
        $dataHeader['title'] = '| Login';
        $dataHeader['css'] = 'css/login.css';
        $dataHeader['tambahan_css'] = '';
        $dataFooter['js'] = 'js/login.js';
        $dataFooter['tambahan_js'] = '';
        $dataFooter['key_encrypt'] = KEY_ENCRYPT;
        $this->view('templates/header', $dataHeader);
        $this->view('login/index');
        $this->view('templates/footer', $dataFooter);
        
    }
    public function masuk()
    {
        $data = $this->script("masuk")->masuk();
        //var_dump($data);
        echo $data;
    }
}
