<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Category;

class CategoryDetail extends Model
{
    use HasFactory;
    protected $table='categories_detail';

    public function product(){
        return $this->belongsToMany(Product::class,'id_product','id_products');
    }

    public function category(){
        return $this->belongsToMany(Category::class,'id_category','id_category');
    }

    protected $fillable = [
        'id_category', 'id_products',
    ];
}
