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
        $this->view('templates/footer', $dataFooter);
    }
    public function register()
    {
        $data = $this->script("register")->register();
        echo $data;
    }
    public function wilayah()
    {
        require_once '../app/models/script/class/Validate.php';
        $validate = new Validate($_POST);
        $jenis = $validate->setRules('jenis', 'jenis', [
            'sanitize' => 'string',
            'required' => true,
            'min_char' => 1,
            'max_char' => 100
        ]);
        $tbl = $validate->setRules('tbl', 'tbl', [
            'sanitize' => 'string',
            'required' => true,
            'min_char' => 1,
            'max_char' => 100
        ]);
        if ($validate->passed()) {
            switch ($jenis) {
                case 'list_dropdown':
                    switch ($tbl) {
                        case 'wilayah':
                            #code...
                            break;
                    };
                    
                    break;
                default:
                    #code...
                    break;
            };
            
        }
    }
}
