<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductImage;
use App\Models\CategoryDetail;
use App\Models\Transaction;

class Product extends Model
{
    use HasFactory;
    protected $table='products';

    public function product_image(){
        return $this->hasMany(ProductImage::class,'id_products','id_product');
    }

    public function product_category(){
        return $this->hasMany(CategoryDetail::class,'id_products','id_product');
    }

    public function product_transaction(){
        return $this->hasMany(Transaction::class,'id_product_transaction','id_product');
    }

    protected $fillable = [
        'name_product','desc_product','rating_product', 'stock_product', 'price_product',
    ];
}
