<?php

namespace App\Services;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use App\Http\Requests\Request;
use Illuminate\Support\Facades\Crypt;

/**
 * Description of EncryptionService
 *
 * @author jeevan
 */
class EncryptionService
{
    protected $encryptionType;

    public function __construct()
    {
        $this->encryptionType = config('security.default_encryption_type', 'bcrypt');
        $this->urlEncryptionType = config('security.default_url_encryption_type','base64');

    }

    /**
     * @todo Ability to change the encryption type in the configuration file
     * reflecting the same in Auth::check() and other Auth functions
     */
    public function encrypt($string, $encryptionType = null)
    {
        $encrypteString = null;
        $encryptionType = !$encryptionType ? $this->encryptionType : $encryptionType;
        switch ($encryptionType) {
            case 'bcrypt':
                $encrypteString = bcrypt($string);
                break;
            case 'base64':
                $encrypteString = base64_encode($string);
                break;
            case 'encrypt':
                $encrypteString = Crypt::encrypt($string);
                break;
        }

        return $encrypteString;
    }

    public function decrypt($string, $decryptionType = null)
    {
        $decrypteString = null;
        $decryptionType = !$decryptionType ? $this->encryptionType : $decryptionType;
        switch ($decryptionType) {
            case 'base64':
                $decrypteString = base64_decode($string);
                break;
            case 'encrypt':
                $decrypteString = Crypt::decrypt($string);
                break;
        }

        return $decrypteString;
    }

    public function encryptUrlParameter($string){
         return $this->encrypt($string, $this->urlEncryptionType);
    }

   public function decryptUrlParameter($string){
      return  $this->decrypt($string, $this->urlEncryptionType);
    }
    public function encryption($string)
    {
        return $string=base64_encode($string);
    }
    public function decryption($string)
    {
        return $string=base64_decode($string);
    }
}

