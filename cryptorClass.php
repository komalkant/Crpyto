<?php
class Cryptor
{

  static protected $method = 'AES-128-CTR';
  static private $key;

  static protected function iv_bytes()
  {
  	
    return openssl_cipher_iv_length(Cryptor::$method);
  }

  public function __construct($key = false, $method = false)
  {
    if(!$key) {
      // if you don't supply your own key, this will be the default
      $key = gethostname() . "|" . ip2long($_SERVER['SERVER_ADDR']);
    }
    if(ctype_print($key)) {
      // convert key to binary format
      Cryptor::$key = openssl_digest($key, 'SHA256', true);
    } else {
      Cryptor::$key = $key;
    }
    if($method) {
      if(in_array($method, openssl_get_cipher_methods())) {
        Cryptor::$method = $method;
      } else {
        die(__METHOD__ . ": unrecognised encryption method: {$method}");
      }
    }
  }

  public static function encrypt($data)
  {
    $iv = openssl_random_pseudo_bytes(self::iv_bytes());
    $encrypted_string = bin2hex($iv) . openssl_encrypt($data, Cryptor::$method, Cryptor::$key, 0, $iv);
    return $encrypted_string;
  }


  public static function decrypt($data)
  {
    $iv_strlen = 2  * self::iv_bytes();
    if(preg_match("/^(.{" . $iv_strlen . "})(.+)$/", $data, $regs)) {
      list(, $iv, $crypted_string) = $regs;
      $decrypted_string = openssl_decrypt($crypted_string, Cryptor::$method, Cryptor::$key, 0, hex2bin($iv));
      return $decrypted_string;
    } else {
      return false;
    }
  }

}

  $token = "komalkant Gupta";
  $crypted_token = Cryptor::encrypt($token);
  echo 'Encrypted -> ';
  print_r($crypted_token); echo "</br>";

  $decrypted_token = Cryptor::decrypt($crypted_token);
  echo 'Decrypted -> ';
  print_r($decrypted_token); echo "</br>";
?>