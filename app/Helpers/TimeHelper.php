<?php

namespace App\Helpers;

use Carbon\Carbon;

class TimeHelper
{
    /**
     * Get timezone abbreviation based on location
     */
    public static function getTimezoneAbbr($location = null)
    {
        $timezone = $location ?: config('app.timezone', 'Asia/Jakarta');
        
        $abbreviations = [
            'Asia/Jakarta' => 'WIB',
            'Asia/Makassar' => 'WITA', 
            'Asia/Jayapura' => 'WIT',
        ];
        
        return $abbreviations[$timezone] ?? 'WIB';
    }
    
    /**
     * Format date with Indonesian timezone
     */
    public static function formatIndonesianDate($date, $format = 'd/m/Y H:i')
    {
        if (!$date) return '-';
        
        $carbon = $date instanceof Carbon ? $date : Carbon::parse($date);
        $timezone = config('app.timezone', 'Asia/Jakarta');
        
        return $carbon->setTimezone($timezone)->format($format) . ' ' . self::getTimezoneAbbr($timezone);
    }
    
    /**
     * Format date only (without time)
     */
    public static function formatIndonesianDateOnly($date)
    {
        return self::formatIndonesianDate($date, 'd/m/Y');
    }
    
    /**
     * Format time only
     */
    public static function formatIndonesianTimeOnly($date)
    {
        return self::formatIndonesianDate($date, 'H:i');
    }
    
    /**
     * Get current time in Indonesian timezone
     */
    public static function nowIndonesian()
    {
        return Carbon::now()->setTimezone(config('app.timezone', 'Asia/Jakarta'));
    }
}
