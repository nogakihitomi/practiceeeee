<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Product extends Model
{
    use HasFactory;

    protected $table = 'products';


    protected $fillable = [
        'product_name',
        'company_id',
        'price',
        'stock',
        'comment',
        'img_path',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    protected $attributes = [
        'img_path' => 'images/dummy-product.png',
    ];

    public static function createProduct($data)
    {
        $product = new self();
        $imgPath = $product->saveImage($data->file('img_path'));

    return self::create([
        'product_name' => $data->input('product_name'),
        'price' => $data->input('price'),
        'stock' => $data->input('stock'),
        'company_id' => $data->input('company_id'),
        'comment' => $data->input('comment'),
        'img_path' => $imgPath,
    ]);

    }

    public function saveImage($file)
    {
        if ($file) {

            $extension = $file->getClientOriginalExtension();
    
            $filename = uniqid() . '.' . $extension;
    
            $path = $file->storeAs('products', $filename, 'public');
    
            return '/storage/' . $path;
        }
    
        return null;
    }

    public function updateProduct($request)
    {
        $imgPath = $request->hasFile('img_path')
            ? $this->saveImage($request->file('img_path'))
            : $this->img_path;
    
        $this->update([
            'product_name' => $request->product_name,
            'price' => $request->price,
            'stock' => $request->stock,
            'company_id' => $request->company_id,
            'comment' => $request->comment,
            'img_path' => $imgPath, 
        ]);
    }

}