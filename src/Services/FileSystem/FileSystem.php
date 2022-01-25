<?php

namespace App\Services\FileSystem;

use Cloudinary\Cloudinary;

class FileSystem
{
    public static function upload($file, string $group, string $filename) {
        file_put_contents("temp/$filename", $file);
        $cloudinary = new Cloudinary($_SERVER['CLOUDINARY_URL']);
        $cloudinary->uploadApi()->upload( "temp/$filename",  ['public_id' => "$group/$filename"]);
        self::deleteTempContent();
    }

    public static function delete(string $group, string $filename) {
        $cloudinary = new Cloudinary($_SERVER['CLOUDINARY_URL']);
        $cloudinary->uploadApi()->destroy( "$group/$filename", ['resource_type' => 'image']);
        self::deleteTempContent();
    }

    private static function deleteTempContent() : void
    {
        array_map('unlink', glob("temp/*.*"));
    }
}
