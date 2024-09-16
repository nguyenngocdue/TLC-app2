<?php

namespace App\BigThink;

class Math
{
    static function fnv1a_32($data)
    {
        // 32-bit FNV prime
        $fnvPrime = 0x01000193;
        // 32-bit FNV offset basis
        $hash = 0x811C9DC5;

        // Convert the input string into an array of ASCII values
        $dataLength = strlen($data);
        for ($i = 0; $i < $dataLength; $i++) {
            // XOR the hash with the current byte
            $hash ^= ord($data[$i]);
            // Multiply the hash by the FNV prime (modulo 2^32 to keep it 32-bit)
            $hash = ($hash * $fnvPrime) & 0xFFFFFFFF;
        }

        return $hash;
    }

    static function createDiginetFingerprint($arrayToCreateFingerprint)
    {
        $str = '';
        foreach ($arrayToCreateFingerprint as $value) $str .= $value;
        // return hexdec(substr(md5($str), 0, 16));
        return hexdec(static::fnv1a_32($str));
    }
}
