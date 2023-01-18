<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FileStorageHelper
{
    const DISK = 'screenshots';

    public function createFile($path,$content,$name)
    {
        $location = $this->store($path,$content,$name);
        return $this->getFileUrl($location);
    }
    public function store($path,$content,$name)
    {
        $file_path = $path.'/'.$name;
        Storage::disk(self::DISK)->put($file_path,$content);
        return $file_path;
    }

    public function getFileUrl($location)
    {
        return Storage::disk(self::DISK)->url($location);
    }


}
