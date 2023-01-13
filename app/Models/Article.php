<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'publish',
        'user_id'
    ];

    protected $appends = [
        'publish_text'
    ];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function getPublishTextAttribute()
    {
        if ($this->publish) {
            return 'Published';   
        }else{
            return 'Draft';
        }
    }
}
