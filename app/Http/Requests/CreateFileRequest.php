<?php

namespace App\Http\Requests;

use App\FileType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'max:100',
            'file' => 'file|mimes:' . implode(',', FileType::select('name')->where('name', '!=', config('constants.FILE_TYPE_FOLDER'))->pluck('name')->toArray()) . '|max:' . config('constants.MAX_FILE_SIZE'),
            'isFolder' => 'boolean',
        ];
    }
}
