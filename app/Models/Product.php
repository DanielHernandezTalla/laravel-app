<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Scopes\AvailableScope;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    // Asignas un nombre a la tabla
    protected $table = 'products';

    // Nos trae los datos con las relaciones de imagenes desde el principio
    protected $with = [
        'images'
    ];

    // Valores que seran ingresados de forma masiva
    protected $fillable = [
        'title',
        'description',
        'price',
        'stock',
        'status'
    ];

    // Agregando scoope global que nos traiga solo los productos disponibles 
    protected static function booted(): void
    {
        static::addGlobalScope(new AvailableScope);
    }

    public function carts(){
        // return $this->belongsToMany(Cart::class)->withPivot('quantity');
        return $this->morphedByMany(Cart::class, 'productable')->withPivot('quantity');
    }

    public function orders(){
        return $this->morphedByMany(Order::class, 'productable')->withPivot('quantity');
    }

    public function images(){
        return $this->morphMany(Image::class, 'imageable');
    }

    // Query para el scope 
    public function scopeAvailable($query){
        $query->where('status', 'available');
    }

    // Getters
    public function getTotalAttribute()
    {
        return $this->pivot->quantity * $this->price;
    }
}
