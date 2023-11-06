<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    use HasFactory;

    protected $fillable = [
        'points_for_answer',
        'answer_text'
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

}
