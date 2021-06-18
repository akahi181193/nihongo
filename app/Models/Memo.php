<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Memo extends Model
{
    use HasFactory, SoftDeletes;
    protected $table ="memos";
    protected $fillable = [
        'category_id',
        'id',
        'user_id',
        'wordclass',
        'name',
        'images',
        'audio',
        'description',
    ];

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id')->withTrashed();
    }
}
