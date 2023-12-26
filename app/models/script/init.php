<?php
$status = session_status();
if ($status == PHP_SESSION_NONE) {
    //There is no active session
    //session_start();
    session_start();
} else if ($status == PHP_SESSION_DISABLED) {
    //Sessions are not available
} else if ($status == PHP_SESSION_ACTIVE) {
    //Destroy current and start new one
    //session_destroy();
    // session_start();
}

spl_autoload_register(function ($className) {
    $real_path = realpath(dirname(__FILE__));
    //var_dump( $real_path );
    //var_dump( strpos( $real_path, 'script/class' ) );
    /*if ( strpos( $real_path, 'class' ) != false ) {
        $path = "script/class/{$className}.php";
    } else if ( strpos( $real_path, 'script' ) != false ) {
        $path = "$real_path/class/{$className}.php";
    } else {
        $path = "{$className}.php";
    }*/
    if (strpos($real_path, 'class') != false) {
        $path = BASEURL . "/app/models/script/class/{$className}.php";
    } else if (strpos($real_path, 'script') != false) {
        $path = "$real_path/class/{$className}.php";
    } else {
        $path = BASEURL . "/app/models/script/class/{$className}.php";
    }
    //var_dump( $path );
    //$path = "script/class/{$className}.php";
    if (file_exists($path)) {
        require $path;
    } else {
        die("File $path tidak tersedia");
    }
});
