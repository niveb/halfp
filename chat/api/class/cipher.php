<?php
require 'config.php';

class Cipher {

    public static function encrypt($content) {
        if (ENCRYPTION_KEY == "")
            return $content;
        $ciphering = "AES-128-CTR";
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        $encryption_iv = '8521146905544121';
        return openssl_encrypt($content, $ciphering,ENCRYPTION_KEY, $options, $encryption_iv);
    }

    public static function decrypt($encrypted) {
        if (ENCRYPTION_KEY == "")
            return $encrypted;
        $ciphering = "AES-128-CTR";
        $iv_length = openssl_cipher_iv_length($ciphering);
        $options = 0;
        $encryption_iv = '8521146905544121';
        return openssl_decrypt($encrypted, $ciphering,ENCRYPTION_KEY, $options, $encryption_iv);
    }
}

?>
