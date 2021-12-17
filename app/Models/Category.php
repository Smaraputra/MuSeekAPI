<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CategoryDetail;

class Category extends Model
{
    use HasFactory;
    protected $table='categories';

    public function category_detail(){
        return $this->hasMany(CategoryDetail::class,'id_category','id_category');
    }

    protected $fillable = [
        'name_category',
    ];
}
