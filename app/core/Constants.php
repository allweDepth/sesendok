<?php
define('LOCALURL', 'http://localhost/sesendokneo/');
define('BASEURL', 'http://localhost/sesendokneo/public/');
$msx = rand(32, 64);
$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ()<>?{}#$&=-*^@';
$keyEnc = substr(str_shuffle($permitted_chars), 0, $msx);
define('KEY_ENCRYPT', $keyEnc);