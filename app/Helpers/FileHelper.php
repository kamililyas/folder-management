<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class FileHelper
{
    public static function removeSpecialCharactersFromName($name)
    {
        return preg_replace("/[^a-z0-9؀-ۿ\_\-\.]/i", '_', $name); // Allow ONLY alpha numeric dash characters along with arabic literals
    }

    public static function getUploadPath($complete = false) {
        if ($complete) {
            return storage_path('app') . config('constants.FILE_UPLOAD_PATH') . Auth::user()->id . '/';
        }
        return config('constants.FILE_UPLOAD_PATH') . Auth::user()->id . '/';
    }
}