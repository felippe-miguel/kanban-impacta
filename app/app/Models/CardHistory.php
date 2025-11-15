<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardHistory extends Model
{
    protected $table = 'card_histories';

    protected $fillable = [
        'card_id',
        'action',
        'description',
        'old_value',
        'new_value',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function card()
    {
        return $this->belongsTo(Card::class);
    }
}
