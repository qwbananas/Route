<?php
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
    if(!is_string($data)){
        return null;
    }
    $r = openssl_private_encrypt($data, $encrypted, $_privKey);
    if($r){
        return base64_encode($encrypted);
    }
}
//私钥解密
function privDecrypt($keyPath, $encrypted)
{
    if(!is_string($encrypted)){
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