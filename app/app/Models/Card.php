<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    protected $fillable = ['title', 'description', 'column_id'];

    public function getCreatedAtAttribute($value)
    {
        return date('d/m/Y H:i:s', strtotime($value));
    }

    public function column()
    {
        return $this->belongsTo(Column::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
