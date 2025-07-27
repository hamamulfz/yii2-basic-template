<?php

/**
 * User: Taufiq Rahman (Rahman.taufiq@gmail.com)
 * Date: 26/09/20
 * Time: 07.01
 */

namespace app\helpers;

class ServerCheck
{

    static public  function humanSize($Bytes)
    {
        $Type = array("", "kilo", "mega", "giga", "tera", "peta", "exa", "zetta", "yotta");
        $Index = 0;
        while ($Bytes >= 1024) {
            $Bytes /= 1024;
            $Index++;
        }
        return ("" . $Bytes . " " . $Type[$Index] . "bytes");
    }

    public function health()
    {
        $memory = $this->humanSize(memory_get_usage());
        $diskfree = $this->humanSize(disk_free_space("/"));
        $usage = sys_getloadavg();
        $data = [
            'memoryusage' => $memory,
            'diskfree' => $diskfree,
            'Cpu' => $usage
        ];
        return $data;
    }

    public static function getClientIp()
    {
        $ip = false;
        $seq = array(
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'
        );

        foreach ($seq as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                        return $ip;
                    }
                }
            }
        }
    }

}
