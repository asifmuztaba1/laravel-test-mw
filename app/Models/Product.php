<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'title', 'sku', 'description'
    ];
    /**
     * @var mixed
     */

    public function variant(){
        return $this->hasMany(ProductVariant::class);
    }
    public function variantPrice(){
        return $this->hasMany(ProductVariantPrice::class);
    }
}
