<?php
namespace App\Helpers;

use DB;

class Utility {
    public static function password_check($password) {
        $blacklist_password = [
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
        if(
            in_array(strtoupper($password), $blacklist_password) ||
            in_array(strtoupper(strrev($password)), $blacklist_password)
        ) {
            return true;
        }
        return false;
    }

    public static function fdate($date, $format = 'd-m-Y H:i:s') {
        return date($format, strtotime($date));
    }

    public static function generate_code($table, $column, $length = 4) {
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $randomString = '';

        for($i = 0; $i < $length; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        if(DB::table($table)->where($column, $randomString)->first()) {
            self::generate_code($table, $column, $length);
        }

        return $randomString;
    }

    static function toCamelCase($input) {
        // Pisahkan string menjadi array kata
        $words = explode(' ', $input);

        // Ubah setiap kata menjadi camel case
        $camelCaseWords = array_map(function ($word) {
            return lcfirst(ucwords($word));
        }, $words);

        // Gabungkan kembali kata-kata yang telah diubah menjadi camel case
        $camelCaseString = implode('', $camelCaseWords);

        return $camelCaseString;
    }


    static function uploadFile($folder="file",$file) {
        $imageName = time().'-.'.$file->extension();
        $file->move(public_path('storage/'.$folder.'/'), $imageName);
        $imagePath = 'storage/'.$folder.'/'.$imageName;
        return $imagePath;

        // if ($request->file('image')) {
        //     $imageName = time() . '-image.' . $request->image->extension();
        //     $uploadedImage = $request->image->move(public_path('storage/web_photos/'), $imageName);
        //     $imagePath = 'storage/web_photos/' . $imageName;
        //     $data['image'] = $imagePath;
        // }

    }

}