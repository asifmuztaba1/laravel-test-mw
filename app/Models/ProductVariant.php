<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $table = 'product_variants';
    protected $fillable = [
        'variant', 'variant_id', 'product_id'
    ];
    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function productVariantToVariant()
    {
        return $this->belongsTo(Variant::class);
    }
}
