<?php

namespace App\Lib;

class AuthUser
{
    private static $userId;

    private static $userInfo;

    public static function login($userData)
    {
        if (!$userData) {
            throw \Exception('User Data Is Null');
        }

        $_SESSION['user_id'] = $userData->id;
        $_SESSION['user_data'] = $userData;

        self::user();
    }

    public static function user()
    {
        if (isset($_SESSION['user_id'])) {
            self::$userId = $_SESSION['user_id'];
            self::$userInfo = $_SESSION['user_data'];
        } else {
            self::$userId = '';
            self::$userInfo = NULL;
        }

        return self::$userInfo;
    }

    public static function check()
    {
        if (empty(self::$userId) || is_null(self::$userId)) {
            self::user();
        }

        return empty(self::$userId) || is_null(self::$userId) ? false : true;
    }

    public static function add($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function take($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public static function del($key)
    {
        unset($_SESSION[$key]);
    }

    public static function forgot()
    {
        session_unset();
        session_destroy();
    }
}