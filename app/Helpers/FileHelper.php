<?php
namespace App\Helpers;

class FileHelper
{
    public static function removeSpecialCharactersFromName($name)
    {
        return preg_replace("/[^a-z0-9؀-ۿ\_\-\.]/i", '_', $name); // Allow ONLY alpha numeric dash characters along with arabic literals
    }
}