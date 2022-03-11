<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantPrice extends Model
{
    protected $table = 'product_variant_prices';
    protected $fillable = [
        'product_variant_one', 'product_variant_two', 'product_variant_three','price','stock','product_id'
    ];
    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function productVarNameOne(){
        return $this->belongsTo(ProductVariant::class,"product_variant_one","id");
    }
    public function productVarNameTwo(){
        return $this->belongsTo(ProductVariant::class,"product_variant_two","id");
    }
    public function productVarNameThree(){
        return $this->belongsTo(ProductVariant::class,"product_variant_three","id");
    }
}
