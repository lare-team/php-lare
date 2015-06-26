<?php

namespace Lare_Team\Lare;

/*
 *
 * (c) 2015 Lare Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class Lare
{
    private static $enabled = false;
    private static $previous_namespace = '';
    private static $current_namespace = '';
    private static $version = '1.0.0';
    private static $supported_version = '1.0.0';

    static private $instance = null;

    static public function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self;
            if (isset($_SERVER['HTTP_X_LARE'])) {
                if (isset($_SERVER['HTTP_X_LARE_VERSION'])) {
                    $frontend_version = $_SERVER['HTTP_X_LARE_VERSION'];
                    $frontend_versions = preg_split('/\./i', $frontend_version);
                    $supported_versions = preg_split('/\./i', self::$supported_version);
                    $i = 0;
                    while ($i < count($supported_versions) && $i < count($frontend_versions)) {
                        if ($frontend_versions[$i] < $supported_versions[$i]) {
                            self::$enabled = false;
                            return self::$instance;
                        }
                        $i++;
                    }
                }
                self::$enabled = true;
                self::$previous_namespace = $_SERVER['HTTP_X_LARE'];
                header('X-LARE-VERSION: '.Lare::$version);
            }
        }
        return self::$instance;
    }

    private function __construct(){}
    private function __clone(){}

    public static function is_enabled() {
        return self::$enabled;
    }

    public static function set_current_namespace($namespace) {
        self::$current_namespace = $namespace;
    }

    public static function get_current_namespace() {
        return self::$current_namespace;
    }

    public static function get_matching_count($extension_namespace = null) {
        if (!self::$enabled) return 0;
        if (!isset($extension_namespace)) {
            $extension_namespace = self::$current_namespace;
        }
        $matching_count = 0;
        $previous_namespaces = preg_split('/\./i', self::$previous_namespace);
        $extension_namespaces = preg_split('/\./i', $extension_namespace);

        while ($matching_count < count($previous_namespaces) && $matching_count < count($extension_namespaces)) {
            if ($previous_namespaces[$matching_count] == $extension_namespaces[$matching_count]) {
                $matching_count++;
            } else {
                break;
            }
        }
        return $matching_count;
    }

    public static function matches($extension_namespace = null) {
        if (!isset($extension_namespace)) {
            $extension_namespace = self::$current_namespace;
        }
        return self::get_matching_count($extension_namespace) == count(preg_split('/\./i', $extension_namespace));
    }
}

Lare::get_instance();
