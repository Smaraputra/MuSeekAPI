<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\User;
use App\Models\Payment;

class Transaction extends Model
{
    use HasFactory;
    protected $table='transactions';

    protected $primaryKey = 'id_transaction';

    public function product_trans(){
        return $this->belongsToMany(Product::class,'id_product','id_product_transaction');
    }

    public function user_trans(){
        return $this->belongsToMany(Category::class,'id_user','id_user_transaction');
    }

    protected $fillable = [
        'id_user_transaction','id_product_transaction','start_transaction','end_transaction','total_product','total_transaction','rating','status_rating_transaction','status_transaction','date_transaction', 'status_payment', 'deadline_payment', 'proof',
    ];
}
