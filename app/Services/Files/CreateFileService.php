<?php

namespace App\Services\Files;

use App\FileType;
use App\Helpers\FileHelper;
use App\User;
use App\UserFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CreateFileService
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var array
     */
    protected $inputParamArray;

    /**
     * @var array
     */
    protected $files;


    /**
     * CreateFileService constructor.
     * @param User $user
     * @param array $inputParamArray
     * @param array $files
     */
    public function __construct(User $user, Array $inputParamArray, Array $files)
    {
        $this->inputParamArray = $inputParamArray;
        $this->user = $user;
        $this->files = $files;
    }

    /**
     * @return mixed
     */
    public function create()
    {
        $userFilesAdded = array();

        if (!empty($this->files)) {
            foreach ($this->files as $file) {
                $fileType = FileType::where('name', $file->getClientOriginalExtension())->first();

                if ($fileType) {
                    $name = $this->getCleanedFileName($file->getClientOriginalName());
                    $content = file_get_contents($file->getRealPath());

                    Storage::put(FileHelper::getUploadPath() . $name, $content, 'public');

                    $userFile = new UserFile();
                    $userFile->name = $name;
                    $userFile->size = eval('return ' . $file->getClientSize() . config('constants.FILE_SIZE_CALCULATE_EXPRESSION') . ';');/* Convert Bytes to required size unit */
                    $userFile->file_type_id = $fileType->id;
                    $userFile->user_id = Auth::user()->id;
                    $userFile->parent_id = $this->getParentIDToAssignToNewFile();
                    $userFile->save();

                    $this->updateParentFolderSize($userFile->parent_id, $userFile->size);

                    array_push($userFilesAdded, $userFile);
                }
            }
        }
        elseif (isset($this->inputParamArray[config('constants.USER_FILE_KEY_NAME')]) && $this->inputParamArray[config('constants.USER_FILE_KEY_NAME')]) {
            $name = $this->getCleanedFileName($this->inputParamArray[config('constants.USER_FILE_KEY_NAME')]);
            $fileType = FileType::where('name', config('constants.FILE_TYPE_FOLDER'))->first();

            $userFile = new UserFile();
            $userFile->name = $name;
            $userFile->size = 0;/* Convert Bytes to required size unit */
            $userFile->file_type_id = $fileType->id;
            $userFile->user_id = Auth::user()->id;
            $userFile->parent_id = $this->getParentIDToAssignToNewFile();
            $userFile->save();

            array_push($userFilesAdded, $userFile);
        }

        return $userFilesAdded;
    }

    private function getParentIDToAssignToNewFile()
    {
        $parentId = null;
        if (isset($this->inputParamArray[config('constants.USER_FILE_KEY_PARENT_ID')]) && $this->inputParamArray[config('constants.USER_FILE_KEY_PARENT_ID')]) {
            $checkParentUserFile = UserFile::where('id', $this->inputParamArray[config('constants.USER_FILE_KEY_PARENT_ID')])->first();

            if ($checkParentUserFile) {
                $checkParentUserFileType = $checkParentUserFile->fileType()->first();
                if ($checkParentUserFileType->name == config('constants.FILE_TYPE_FOLDER')) {
                    $parentId = $this->inputParamArray[config('constants.USER_FILE_KEY_PARENT_ID')];
                }
            }
        }

        return $parentId;
    }

    private function getCleanedFileName($name) {
        $cleanedName = FileHelper::removeSpecialCharactersFromName($name);
        $checkUserFileName = UserFile::where('name', $name)->first();

        if ($checkUserFileName) {
            $explodedName = explode('.', $name);
            $onlyFileName = $explodedName[0];
            $onlyFileName .= '-' . time();

            $cleanedName = $onlyFileName;
            if (isset(explode('.', $name)[1])) {
                $cleanedName = $onlyFileName . '.' . $explodedName[1];
            }

        }

        return $cleanedName;
    }

    private function updateParentFolderSize($parentId, $size) {
        if ($parentId != null) {
            UserFile::where('id', $parentId)->increment('size', $size);
        }

    }
}