<?php

namespace Domain\Product\Models;

use Database\Factories\OptionFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = [
        'title'
    ];

    protected static function newFactory(): OptionFactory|Factory
    {
        return OptionFactory::new();
    }

}
