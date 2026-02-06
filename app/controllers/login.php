<?php
class Login extends Controller
{
    public function index()
    {
        // Pastikan session berjalan
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Ambil data POST
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        // Cek user sesuai script asli
        $user = $this->scriptConstruct("query", ['jns'=>'cekUser','tbl'=>'users'])->cek($username, $password);

        // Debug langsung di browser (tidak redirect)
        echo "<pre>";
        echo "=== DEBUG SESENDOK LOGIN ===\n\n";
        echo "POST DATA:\n";
        print_r($_POST);
        echo "\nUSER RESULT:\n";
        var_dump($user);
        echo "\nSESSION BEFORE:\n";
        print_r($_SESSION);
        echo "\n===========================\n";
        echo "</pre>";

        // Jika user ditemukan, simpan session dan tampilkan sukses
        if ($user) {
            $_SESSION['user'] = $user;
            echo "<p style='color:green;'>LOGIN BERHASIL! SESSION TERISI.</p>";
            echo "<pre>SESSION NOW:\n";
            print_r($_SESSION);
            echo "</pre>";
            // header('Location: /public/Dashboard'); // Aktifkan nanti jika debug selesai
        } else {
            echo "<p style='color:red;'>LOGIN GAGAL! USER TIDAK DITEMUKAN ATAU PASSWORD SALAH.</p>";
            // header('Location: /public/Login/login'); // Jangan redirect dulu
        }
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        echo "<p style='color:blue;'>LOGOUT BERHASIL!</p>";
        // header('Location: /public/Login/login'); // Aktifkan nanti
    }
}
