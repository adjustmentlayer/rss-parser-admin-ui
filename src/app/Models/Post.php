<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'creator',
        'guid',
        'link'
    ];

    public function categories() {
        return $this->hasMany(Category::class);
    }
}
