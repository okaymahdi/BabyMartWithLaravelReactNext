<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartModel extends Model

{
    protected $fillable = ['user_id', 'product_id', 'quantity'];

    public function user()
    {
        return $this->belongsTo(UserModel::class);
    }

    public function product()
    {
        return $this->belongsTo(ProductModel::class);
    }
}
