<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ProductImage extends Model
{
    use HasFactory;
    protected $table='products_image';
    protected $primaryKey = 'id_products_image';

    public function product(){
        return $this->belongsToMany(Product::class,'id_product','id_products');
    }

    protected $fillable = [
        'id_products','image',
    ];
}
