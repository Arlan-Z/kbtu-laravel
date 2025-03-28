<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'description', 'price'];
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
    public function setNameAttribute($value) : void
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = strtolower(str_replace(' ', '-', $value));
    }
}
