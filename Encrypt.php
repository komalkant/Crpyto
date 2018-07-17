<?php


interface Encrypt
{
    /**
     * encrypt any string value class instance.
     *
     */
    public static function encrypt($token);

    /**
     * decrypt any string value class instance.
     *
     */
    public static function decrypt($token);



}
