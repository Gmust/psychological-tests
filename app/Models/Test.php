<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends Model
{
    use HasFactory;


    protected $fillable = [
        'title',
        'total_points',
        'result',
        'questions'
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'passed_tests', 'test_id', 'user_id')->withTimestamps();
    }
}
