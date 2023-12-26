<?php
class Masuk
{
    public function masuk()
    {
        //var_dump($_POST);
        require 'init.php';
        unset($_SESSION["user"]);
        $DB = DB::getInstance();
        $keyEncrypt = $_SESSION["key_encrypt"];
        $user = new User();
        $validate = new Validate($_POST);
        //$crypto = new CryptoUtils();
        
        if (isset($_POST['login'])) {
            
            $username = $validate->setRules('username', 'Username', [
                'sanitize' => 'string',
                'required' => true,
                'min_char' => 6
            ]);
            // var_dump($username);
            //$username = $crypto->decrypt($username, $keyEncrypt);
            $password = $validate->setRules('password', 'Pasword', [
                'sanitize' => 'string',
                'required' => true,
                'min_char' => 4
            ]);
            //var_dump($password);
            //$password = $crypto->decrypt($password, $keyEncrypt);
            if ($validate->passed()) {
                $data = $DB->getQuery('SELECT * FROM user_ahsp WHERE username = ? OR email = ?', [$username, $username])[0];
                //var_dump(sizeof((array)$data));
                if (sizeof((array)$data) > 0) { // ubah ke array sebelum menggunakan sizeof
                    $pesanError = '';
                    //$pesanError = $user->validasiLogin($_POST);
                    if (empty($pesanError)) {
                        $status = session_status();
                        if ($status == PHP_SESSION_NONE) {
                            //There is no active session
                            session_start();
                        } else
                        if ($status == PHP_SESSION_DISABLED) {
                            //Sessions are not available
                        } else
                        if ($status == PHP_SESSION_ACTIVE) {
                            //Destroy current and start new one
                            session_destroy();
                            session_start();
                        }
                        $_SESSION["user"] = (array)$data;
                        $_SESSION["user"]["key_encrypt"] = KEY_ENCRYPT;
                        //var_dump($_SESSION);
                        $id = $_SESSION["user"]["id"];
                        //var_dump($id);
                        $type_user = $_SESSION["user"]["type_user"];
                        //$DB->update('user_ahsp', ['tgl_login'=>NOW()], ['id','=',$id]);
                        $DB->runQuery('UPDATE `user_ahsp` SET tgl_login = NOW() WHERE id = ?', [$id]);
                        if ($type_user == 'admin') {
                            $session = 1;
                        } else {
                            $session = 1; //$session = 2;
                        }
                    } else {
                        $session = 4;
                    }
                } else {
                    $session = 5;
                }
            } else {
                $session = $validate->getError();
                // var_dump($validate->getError());
            }
        }
        return $session;
    }
}
