<?php

namespace app\components\helpers;

class IpFormatter
{
    public static function hideOctets($ip) {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $parts = explode('.', $ip);
            if (count($parts) === 4) {
                $parts[2] = '***';
                $parts[3] = '***';
                return implode('.', $parts);
            }
        } elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $groups = explode(':', $ip);
            $group_count = count($groups);
            if ($group_count >= 4) {
                for ($i = $group_count - 4; $i < $group_count; $i++) {
                    $groups[$i] = '****';
                }
                return implode(':', $groups);
            }
        }

        return $ip;
    }
}