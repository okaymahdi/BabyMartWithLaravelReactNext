<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WishListModel extends Model
{
    protected $fillable = ['user_id', 'product_id'];

    public function user()
    {
        return $this->belongsTo(UserModel::class);
    }

    // public function product()
    // {
    //     return $this->belongsTo(ProductModel::class);
    // }
}
