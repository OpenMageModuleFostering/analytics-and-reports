<?php

class Freento_Aconnector_Crypt
{
    protected function _getKeyResource($key, $public = true)
    {
        if ($public) {
            $keyResource = openssl_pkey_get_public($key);
        } else {
            $keyResource = openssl_pkey_get_private($key);
        }
        return $keyResource;
    }
    
    public function encrypt($keyPlain, $plaintext, $public = true)
    {
        $key = $this->_getKeyResource($keyPlain, $public);
        $a_key = openssl_pkey_get_details($key);
 
        // Encrypt the data in small chunks and then combine and send it.
        $chunkSize = ceil($a_key['bits'] / 8) - 11;
        $output = '';

        while ($plaintext)
        {
            $chunk = substr($plaintext, 0, $chunkSize);
            $plaintext = substr($plaintext, $chunkSize);
            $encrypted = '';
            if ($public) {
                if (!openssl_public_encrypt($chunk, $encrypted, $key))
                {
                    die('Failed to encrypt data');
                }
            } else {
                if (!openssl_private_encrypt($chunk, $encrypted, $key))
                {
                    die('Failed to encrypt data');
                }
            }
            $output .= $encrypted;
        }
        openssl_free_key($key);
        return $output;
    }
    
    public function decrypt($keyPlain, $encrypted, $public = true)
    {
        $key = $this->_getKeyResource($keyPlain, $public);
        $a_key = openssl_pkey_get_details($key);

        // Decrypt the data in the small chunks
        $chunkSize = ceil($a_key['bits'] / 8);
        $output = '';

        while ($encrypted)
        {
            $chunk = substr($encrypted, 0, $chunkSize);
            $encrypted = substr($encrypted, $chunkSize);
            $decrypted = '';
            if ($public) {
                if (!openssl_public_decrypt($chunk, $decrypted, $key))
                {
                    die('Failed to decrypt data');
                }
            } else {
                if (!openssl_private_decrypt($chunk, $decrypted, $key))
                {
                    die('Failed to decrypt data');
                }
            }
            $output .= $decrypted;
        }
        openssl_free_key($key);
        return $output;
    }
}