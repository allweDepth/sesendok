<?php
class Login extends Controller
{
    public function index()
    {
        if (isset($_SESSION["user"])) {
            unset($_SESSION["user"]);
        }
        session_start();
        $key_encrypt = $this->scriptConstruct("query",['jns'=>'key_encrypt','tbl'=>'key_encrypt'])->key_encrypt();
        $_SESSION["key_encrypt"] = $key_encrypt;
        $_SESSION["tesbede"] = $key_encrypt;
        $dataHeader['awalHeader'] = '';
        $dataHeader['title'] = '| Login';
        $dataHeader['css'] = 'css/login.css';
        $dataHeader['tambahan_css'] = '';
        $dataFooter['js'] = 'js/login.js';
        $dataFooter['tambahan_js'] = '';
        $dataFooter['dok'] = 'Login';
        $dataFooter['key_encrypt'] = $key_encrypt;
        $this->view('templates/header_login', $dataHeader);
        $this->view('login/index');
        $this->view('templates/footer_modal');
        $this->view('templates/footer', $dataFooter);
    }
    public function masuk()
    { 
       if(session_status() === PHP_SESSION_NONE){
            session_start(); // pastikan session jalan
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Cek user sesuai script asli
        $user = $this->scriptConstruct("query", ['jns'=>'cekUser','tbl'=>'users'])->cek($username, $password);

        // Debug langsung di browser supaya tahu kenapa looping
        echo "<pre>";
        echo "POST Data:\n";
        print_r($_POST);
        echo "\nUser Ditemukan:\n";
        var_dump($user);
        echo "\nSession Sebelum Set:\n";
        print_r($_SESSION);
        echo "</pre>";

        // Jika user ditemukan, set session
        if($user){
            $_SESSION['user'] = $user;
            echo "<pre>Login Berhasil, SESSION SET:\n";
            print_r($_SESSION);
            echo "</pre>";
        } else {
            echo "<pre>Login Gagal</pre>";
        }
    }
    public function register()
    {
        $data = $this->script("register")->register();
        echo (is_array($data)) ? json_encode($data, JSON_HEX_APOS) : $data;
    }
    public function wilayah()
    {
        $send = ['jns'=>'json_list_dropdown','tbl'=>'wilayah','kondisi'=>[['disable','<= ?',0]]];
        $data = $this->scriptConstruct("query",$send)->json_list_dropdown();
        echo (is_array($data)) ? json_encode($data, JSON_HEX_APOS) : $data;
    }
    public function organisasi()
    {
        $send = ['jns'=>'json_list_dropdown','tbl'=>'organisasi','kondisi'=>[['kd_wilayah','= ?',$_POST['kd_wilayah']],['disable','<= ?',0,'AND']]];
        $data = $this->scriptConstruct("query",$send)->json_list_dropdown();
        echo (is_array($data)) ? json_encode($data, JSON_HEX_APOS) : $data;
    }
}
