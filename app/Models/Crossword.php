<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crossword extends Model
{
    use HasFactory;

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Crossword::class);
    }

    protected $table = 'crosswords';
    protected $fillable = [
        'name',
        'crossword',
        'user_id',
        'size',
        'words'
    ];
}
