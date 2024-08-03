<?php
namespace App\Helpers;
use Illuminate\Support\Carbon;

class ConvertTime {
    public static function convert($date_time){
        $current_timezone = self::getCurrentTimeZone();
        return  Carbon::createFromFormat('Y-m-d H:i:s', $date_time, $current_timezone)
            ->setTimezone('Asia/Makassar')
            ->format('d-M-Y H:i');
    }

    private static function getCurrentTimeZone() {
        $indonesia_timezones = [
            'Asia/Jakarta', 'Asia/Pontianak', 'Asia/Makassar', 'Asia/Jayapura'
        ];

        $timezone_offset = (new \DateTime('now', new \DateTimeZone('UTC')))
            ->getOffset();
        
        $timezone_name = timezone_name_from_abbr('', $timezone_offset, 0);
        
        if ($timezone_name === false || !in_array($timezone_name, $indonesia_timezones)) {
            $timezone_name = 'Asia/Jakarta';
        }
        
        return new \DateTimeZone($timezone_name);
    }
}



