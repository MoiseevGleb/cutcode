<?php

namespace Domain\Product\Models;

use Database\Factories\PropertyFactory;
use Domain\Product\Collections\PropertyCollection;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'title'
    ];

    protected static function newFactory(): Factory
    {
        return PropertyFactory::new();
    }

    public function newCollection(array $models = []): PropertyCollection
    {
        return new PropertyCollection($models);
    }
}
