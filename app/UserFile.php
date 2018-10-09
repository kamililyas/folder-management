<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class UserFile
 * @package App\UserFile
 *
 * @property int $id
 * @property string $name
 * @property int $size
 * @property int $file_type_id
 * @property int $user_id
 * @property int $parent_id
 * @property \Carbon\Carbon $deleted_at
 *
 * @property User $user
 * @property FileType $fileType
 * @property UserFile $parent
 * @property UserFile[] $children
 */
class UserFile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'size',
        'file_type_id',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fileType()
    {
        return $this->belongsTo(FileType::class, 'file_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(UserFile::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(UserFile::class, 'parent_id');
    }
}
