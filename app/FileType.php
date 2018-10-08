<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class FileType
 * @package App\FileType
 *
 * @property string $name
 * @property \Carbon\Carbon $deleted_at
 *
 * @property UserFile[] $userFiles
 */
class FileType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userFiles()
    {
        return $this->hasMany(UserFile::class, 'file_type_id');
    }
}
