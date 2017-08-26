<?php

namespace rway7\SecureEloquent;

trait HasSecrets
{
    /**
     * Determine if this model is encrypted.
     *
     * @return bool
     */
    public function secured()
    {
        return $this->is_secured;
    }

    /**
     * Encrypt the model with the given key.
     *
     * @param string $key
     *
     * @return $this
     */
    public function secure($key)
    {
        foreach ($this->secrets ?: [] as $secret) {
            $this->attributes[$secret] = $this->encrypter($key)->encryptString($this->attributes[$secret]);
        }

        $this->is_secured = true;

        return $this;
    }

    /**
     * Decrypt the model with the given key.
     *
     * @param string $key
     *
     * @return $this
     */
    public function unsecure($key)
    {
        foreach ($this->secrets ?: [] as $secret) {
            $this->attributes[$secret] = $this->encrypter($key)->decryptString($this->attributes[$secret]);
        }

        $this->is_secured = false;

        return $this;
    }

    /**
     * Get the encrypter instance.
     *
     * @param string $key
     *
     * @return \Illuminate\Encryption\Encrypter
     */
    protected function encrypter($key)
    {
        return new \Illuminate\Encryption\Encrypter(
            substr(sha1($key), 0, 16), 'AES-128-CBC'
        );
    }
}