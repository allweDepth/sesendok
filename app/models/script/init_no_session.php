<?php
spl_autoload_register(function ($className) {
    $real_path = realpath(dirname(__FILE__));
    if (strpos($real_path, 'class') != false) {
        $path = BASEURL . "/app/models/script/class/{$className}.php";
    } else if (strpos($real_path, 'script') != false) {
        $path = "$real_path/class/{$className}.php";
    } else {
        $path = BASEURL . "/app/models/script/class/{$className}.php";
    }
    if (file_exists($path)) {
        require $path;
    } else {
        die("File $path tidak tersedia");
    }
});
