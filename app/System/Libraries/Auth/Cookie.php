<?php
/**
 * Auth Cookie Class
 *
 * UserApplePie
 * @author David (DaVaR) Sargent <davar@userapplepie.com>
 * @version 4.2.1
 *
 * @author Jhobanny Morillo <geomorillo@yahoo.com>
 */

namespace Libs\Auth;

class Cookie {

    public static function exists($key) {
        if (isset($_COOKIE[$key])) {
            return true;
        } else {
            return false;
        }
    }

    public static function set($key, $value, $expiry = "", $path = "/", $domain = false) {
        $retval = false;
        if (!headers_sent()) {
            if ($domain === false)
                $domain = $_SERVER['HTTP_HOST'];

            $retval = @setcookie($key, $value, $expiry, $path, $domain);
            if ($retval)
                $_COOKIE[$key] = $value;
        }
        return $retval;
    }

    public static function get($key, $default = '') {
        return (isset($_COOKIE[$key]) ? $_COOKIE[$key] : $default);
    }

    public static function display() {
        return $_COOKIE;
    }

    public static function destroy($key, $value = '', $path = "/", $domain = "") {
        if (isset($_COOKIE[$key])) {
            unset($_COOKIE[$key]);
            setcookie($key, $value, time() - 3600, $path, $domain);
        }
    }

}
