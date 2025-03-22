<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'quantity', 'total_price'];


    public static function executeSale(int $productId, int $quantity): array
    {
        $product = Product::findOrFail($productId);

        if ($product->stock < $quantity) {
            return ['success' => false, 'message' => '在庫が不足しています'];
        }

        $product->decrement('stock', $quantity);

        return ['success' => true, 'message' => '購入が完了しました', 'sale' => $sale];
    }
}