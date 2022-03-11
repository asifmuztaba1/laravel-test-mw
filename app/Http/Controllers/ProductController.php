<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ProductVariantPrice;
use App\Models\Variant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->isMethod('get'))
        {
            //dd($request);
        }
        $products = Product::paginate(10);
        $variantFilter=Variant::with('variantToProductVariant')->get();
        return view('products.index',compact('products','variantFilter'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $variants = Variant::all();
        return view('products.create', compact('variants'));
    }
    public static function getVarId($varname,$product_id){
        if($varname==null) return null;
        $id=ProductVariant::select('id')->where(['variant'=>$varname,'product_id'=>$product_id])->orderBy('id', 'desc')->first()->id;
        if($id){
            return $id;
        }
        return null;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $variants=json_decode($request->product_variant);
        $prices=json_decode($request->product_variant_prices);
        $variantsSave=new ProductVariant();
        $varArr=[];
        $product = new Product();
        $product->title = $request->title;
        $product->sku = $request->sku;
        $product->description = $request->description;
        $product->save();
        foreach ($variants as $vr){
            foreach ($vr->tags as $vt){
                $rows=[
                    'variant'=>$vt,
                    'variant_id'=>$vr->option,
                    'product_id'=>$product->id
                ];
                $varArr[] = $rows;
            }
        }
        ProductVariant::insert($varArr);
        $tapToIns=[];
        foreach ($prices as $price){
            $taps=explode('/',$price->title);
            $tapid=[];
            foreach ($taps as $tap){
                $tapid[] = $this->getVarId($tap,$product->id);
            }
            $tapToIns[]=[
                "product_variant_one"=>$tapid[0]??null,
                "product_variant_two"=>$tapid[1]??null,
                "product_variant_three"=>$tapid[2]??null,
                "price"=>$price->price,
                "stock"=>$price->stock,
                "product_id"=>$product->id,
            ];
        }
        ProductVariantPrice::insert($tapToIns);
        if($request->file('file') ){
            $files = $request->file('file');
            if(!is_array($files)){
                $files = [$files];
            }
            $productImages=new ProductImage();
            for($i=0, $iMax = count($files); $i< $iMax; $i++){
                $file = $files[$i];
                $filename = $file->getClientOriginalName();
                $filename= str_replace(' ', '', $filename);
                $productImages->thumbnail=1;
                $productImages->file_path="storage/app/productsImage/".$filename;
                $productImages->product_id=$product->id;
                if($file->storeAs('productsImage', $filename)) $productImages->save();

            }
            return response()->json(['message'=>'Product uploaded'], 200);
        }
        else{
            return response()->json(['message'=>'error uploading Product'], 503);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show($product)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit(Product $product)
    {

        $productinfo=Product::all();
        $variants = Variant::all();
        return view('products.edit', compact('variants','productinfo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
