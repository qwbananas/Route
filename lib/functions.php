<?php
//openssl
//创建一对公钥和私钥
function createKey($keyPath) {
    $r = openssl_pkey_new();
    if($r === false) {
        echo openssl_error_string();
    }
    openssl_pkey_export($r, $privKey);
    file_put_contents($keyPath . DIRECTORY_SEPARATOR . 'priv.key', $privKey);

    $rp = openssl_pkey_get_details($r);
    $pubKey = $rp['key'];
    file_put_contents($keyPath . DIRECTORY_SEPARATOR .  'pub.key', $pubKey);
}
//setPrivKey
function setPrivKey($keyPath) {
    $file = $keyPath . DIRECTORY_SEPARATOR . 'priv.key';
    $prk = file_get_contents($file);
    $_privKey = openssl_pkey_get_private($prk);
    return $_privKey;
}
//私钥加密
function privEncrypt($keyPath, $data) {
    //setup PrivKey
    $_privKey = setPrivKey($keyPath);
    //privEncrypt
    if(!is_string($data)) {
        return null;
    }
    $r = openssl_private_encrypt($data, $encrypted, $_privKey);
    if($r){
        return base64_encode($encrypted);
    }
}
//私钥解密
function privDecrypt($keyPath, $encrypted) {
    if(!is_string($encrypted)) {
        return null;
    }
    $_privKey = setPrivKey($keyPath);
    $encrypted = base64_decode($encrypted);
    $r = openssl_private_decrypt($encrypted, $decrypted, $_privKey);
    if($r){
        return $decrypted;
    }

    return null;
}
//setPubKey
function setPubKey($keyPath) {
    $file = $keyPath . DIRECTORY_SEPARATOR .  'pub.key';
    $puk = file_get_contents($file);
    $_pubKey = openssl_pkey_get_public($puk);

    return $_pubKey;
}
//公钥加密
function pubEncrypt($keyPath, $data) {
    //setup PubKey
    $_pubKey = setPubKey($keyPath);
    //pubEncrypt
    if(!is_string($data)){
        return null;
    }
    $r = openssl_public_encrypt($data, $encrypted, $_pubKey);
    if($r){
        return base64_encode($encrypted);
    }
}
//公钥解密
function pubDecrypt($keyPath, $crypted) {
    if(!is_string($crypted)){
        return null;
    }
    $_pubKey = setPubKey($keyPath);
    $crypted = base64_decode($crypted);
    $r = openssl_public_decrypt($crypted, $decrypted, $_pubKey);
    if($r){
        return $decrypted;
    }

    return null;
}
//mcrypt
//加密
function Mencrypt($key, $value){
    $td = mcrypt_module_open('tripledes', '', 'ecb', '');
    $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_DEV_RANDOM);
    $key = substr(md5($key), 0, mcrypt_enc_get_key_size($td));
    mcrypt_generic_init($td, $key, $iv);
    $ret = base64_encode(mcrypt_generic($td, $value));
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);

    return $ret;
}
//解密
function Mdencrypt($key, $value){
    $td = mcrypt_module_open('tripledes', '', 'ecb', '');
    $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_DEV_RANDOM);
    $key = substr(md5($key), 0, mcrypt_enc_get_key_size($td));
    mcrypt_generic_init($td, $key, $iv);
    $ret = trim(mdecrypt_generic($td, base64_decode($value))) ;
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);

    return $ret;
}
//框架自定义函数, 处理字符串, 查询某一特殊字符在字符串中出现的所有位置, 返回数组
function getCharpos($str, $char){
    $j = 0;
    $arr = array();
    $count = substr_count($str, $char);
    for($i = 0; $i < $count; $i++){
        $j = strpos($str, $char, $j);
        $arr[] = $j;
        $j = $j+1;
    }

    return $arr;

}
