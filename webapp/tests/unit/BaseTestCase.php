<?php


namespace msse661;


use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    protected static $LOWERCASE   = 'abcdefghijklmnopqrstuvwxyz';
    protected static $UPPERCASE   = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    protected static $NUMBERS     = '0123456789';
    protected static $ALL_CHARS   = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_';

    protected function assertArrayEquals($expected, $actual, $keys_to_check = null) {
        $keys_to_check = $keys_to_check ? $keys_to_check : array_keys($expected);

        foreach($keys_to_check as $key) {
            $this->assertEquals($expected[$key], $actual[$key]);
        }
    }

    public static function generateRandomEmail($user = null) {
        $user   = $user ? $user : self::generateRandomString(5, self::$LOWERCASE);
        $domain = self::generateRandomString(10, self::$LOWERCASE);
        $tld    = self::generateRandomString(3, self::$LOWERCASE);

        return "{$user}@{$domain}.{$tld}";
    }

    public static function generateRandomName($length = 10) {
        return ucwords(self::generateRandomString($length, self::$LOWERCASE));
    }

    public static function generateRandomString($length = 10, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}