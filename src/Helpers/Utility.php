<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\UploadedFile;

/**
 * Class Utility
 * 
 * Utility helper class for common operations
 */
class Utility
{
    /**
     * Check if password is in blacklist
     *
     * @param string $password Password to check
     * @return bool True if password is blacklisted
     */
    public static function checkPassword(string $password): bool
    {
        $blacklistPasswords = [
            '12345678',
            'ABCDEFGH',
            'QWERTYUIOP',
            'ASDFGHJKL',
            'ZXCVBNM',
            'QWERTYUI',
            'PASSWORD',
            'PASSWORD123',
            '1234567890',
        ];

        $upperPassword = strtoupper($password);
        return in_array($upperPassword, $blacklistPasswords) ||
               in_array(strrev($upperPassword), $blacklistPasswords);
    }

    /**
     * Format date to specified format
     *
     * @param string $date Date string to format
     * @param string $format Output date format
     * @return string Formatted date
     */
    public static function formatDate(string $date, string $format = 'd-m-Y H:i:s'): string
    {
        return date($format, strtotime($date));
    }

    /**
     * Generate unique random code
     *
     * @param string $table Database table name
     * @param string $column Column name to check uniqueness
     * @param int $length Length of generated code
     * @return string Generated unique code
     */
    public static function generateCode(string $table, string $column, int $length = 4): string
    {
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $index = random_int(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        if (DB::table($table)->where($column, $randomString)->exists()) {
            return self::generateCode($table, $column, $length);
        }

        return $randomString;
    }

    /**
     * Convert string to camelCase
     *
     * @param string $input Input string
     * @return string Converted camelCase string
     */
    public static function toCamelCase(string $input): string
    {
        $words = explode(' ', $input);
        
        $camelCaseWords = array_map(function (string $word): string {
            return lcfirst(ucwords($word));
        }, $words);

        return implode('', $camelCaseWords);
    }

    /**
     * Upload file to public storage
     *
     * @param UploadedFile $file File to upload
     * @param string $folder Target folder in storage
     * @return string File path relative to public directory
     */
    public static function uploadFile(UploadedFile $file, string $folder = 'file'): string
    {
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $uploadPath = "storage/{$folder}";
        
        $file->move(public_path($uploadPath), $fileName);
        
        return "{$uploadPath}/{$fileName}";
    }
}