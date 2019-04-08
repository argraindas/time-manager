<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Record
 *
 * @property int $id
 * @property int $user_id
 * @property string $time_start
 * @property string $time_end
 * @property string $description
 * @property int $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Record newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Record newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Record query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Record whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Record whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Record whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Record whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Record whereTimeEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Record whereTimeStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Record whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Record whereUserId($value)
 * @mixin \Eloquent
 * @property-read \App\Category $category
 */
class Record extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
