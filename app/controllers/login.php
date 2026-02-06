<?php
class Login extends Controller
{
    public function index()
    {
        if (isset($_SESSION["user"])) {
            unset($_SESSION["user"]);
        }
        session_start();
        $key_encrypt = $this->scriptConstruct("query", ['jns' => 'key_encrypt', 'tbl' => 'key_encrypt'])->key_encrypt();
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
        session_start(); // pastikan session aktif

        // ambil data login sesuai file asli
        $data = $this->script("masuk")->masuk();

        // Sesuai file asli, $data biasanya array
        // Cek status login sesuai script asli
        if (is_array($data) && !empty($data['user'])) {
            $_SESSION['user'] = $data['user']; // set session user
        }

        // tetap kembalikan data sesuai script asli
        echo (is_array($data)) ? json_encode($data, JSON_HEX_APOS) : $data;
    }
    public function register()
    {
        $data = $this->script("register")->register();
        echo (is_array($data)) ? json_encode($data, JSON_HEX_APOS) : $data;
    }
    public function wilayah()
    {
        $send = ['jns' => 'json_list_dropdown', 'tbl' => 'wilayah', 'kondisi' => [['disable', '<= ?', 0]]];
        $data = $this->scriptConstruct("query", $send)->json_list_dropdown();
        echo (is_array($data)) ? json_encode($data, JSON_HEX_APOS) : $data;
    }
    public function organisasi()
    {
        $send = ['jns' => 'json_list_dropdown', 'tbl' => 'organisasi', 'kondisi' => [['kd_wilayah', '= ?', $_POST['kd_wilayah']], ['disable', '<= ?', 0, 'AND']]];
        $data = $this->scriptConstruct("query", $send)->json_list_dropdown();
        echo (is_array($data)) ? json_encode($data, JSON_HEX_APOS) : $data;
    }
}
