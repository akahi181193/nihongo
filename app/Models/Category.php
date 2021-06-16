<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    // Eloquent :liên kết modal với database theo mô hình(MVC) 
    // và thao tác với database , trả dữ liệu về Controller
    use HasFactory, SoftDeletes;
    protected $table ="categories";
    protected $fillable = [
        'id',
        'name',
        'user_id',
        'description',
    ];

    function deletedMemos() {
        return $this->hasMany(Memo::class)->onlyTrashed();
    }
}
