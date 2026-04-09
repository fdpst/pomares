<?php

namespace App\Helpers;
use Illuminate\Encryption\Encrypter;

class CustomCrypt
{
  public $method;
  public $secret_key;
  public $encrypter;

  public function __construct(){
    $this->method = config('password.cipher_method');
    $this->secret_key  = config('password.cipher_key');
    $this->encrypter = new Encrypter($this->secret_key, $this->method);
  }

  public function Encrypt($password){
    return $this->encrypter->encryptString($password);
  }

  public function Decrypt($password){
    return $this->encrypter->decryptString($password);
  }

  public function check($plain_password, $encrypted_password){
    return $this->encrypter->decryptString($encrypted_password) == $plain_password; 
  }
}
