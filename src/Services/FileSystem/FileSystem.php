<?php

namespace App\Services\FileSystem;

use Cloudinary\Cloudinary;

class FileSystem
{
    public static function upload($file, string $group, int $group_id, string $filename)
    {
        file_put_contents("temp/$filename", $file);
        $cloudinary = new Cloudinary($_SERVER['CLOUDINARY_URL']);
        $cloudinary->uploadApi()->upload("temp/$filename", ['public_id' => "$group/$group_id/$filename"]);
        self::deleteTempContent();
    }

    public static function delete(string $group, string $filename)
    {
        $cloudinary = new Cloudinary($_SERVER['CLOUDINARY_URL']);
        $cloudinary->uploadApi()->destroy("$group/$filename", ['resource_type' => 'image']);
        /// TODO : Si plus aucun fichier dans dossier Cloudinary, delete dossier.
        self::deleteTempContent();
    }

    private static function deleteTempContent(): void
    {
        array_map('unlink', glob("temp/*.*"));
    }

    private static function deleteFolderCloudinary(string $path): void
    {
        $cloudinary = new Cloudinary($_SERVER['CLOUDINARY_URL']);
        $cloudinary->adminApi()->deleteFolder("$path/");
    }

    private static function getAllFilesFromFolderCloudinary(): void
    {
        $cloudinary = new Cloudinary($_SERVER['CLOUDINARY_URL']);
        // TODO : find a solution to get all files from a folder
    }
}
