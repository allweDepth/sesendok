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
        $send = ['jns'=>'json_list_dropdown','tbl'=>'wilayah','kondisi'=>[['disable','<= ?',0]]];
        $data = $this->scriptConstruct("query",$send)->json_list_dropdown();
        echo $data;
    }
    public function organisasi()
    {
        $send = ['jns'=>'json_list_dropdown','tbl'=>'organisasi','kondisi'=>[['kd_wilayah','= ?',$_POST['kd_wilayah']],['disable','<= ?',0,'AND']]];
        $data = $this->scriptConstruct("query",$send)->json_list_dropdown();
        echo $data;
    }
}
