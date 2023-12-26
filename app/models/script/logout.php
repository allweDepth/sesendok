<?php
class logout{
    public function logout(){
        require 'init.php';
        $user = new User();
        $user->cekUserSession();
        $user->logout();
        
        /*require_once( "auth.php" );
        //data session
        require_once( "config.php" );
        // hapus data aktif
        $id = $_SESSION[ "user" ][ "id" ];
        $sql = "DELETE FROM `akun_aktifku` WHERE id_user = $id";
        mysqli_query( $koneksi, $sql );
        session_start();
        // hapus session
        unset( $_SESSION[ "user" ] );
        // redirect ke halaman login.php
        header( "Location: ../login.html" );*/
    }
}
