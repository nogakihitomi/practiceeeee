<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $sales = Sale::with('product')->get();
            return response()->json($sales);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve sales data.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('受信したリクエストデータ:', $request->all());
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
            ]);
    
            return DB::transaction(function () use ($request) {
                $product = Product::findOrFail($request->product_id);
    
                if ($product->stock < $request->quantity) {
                    return response()->json(['message' => '在庫が不足しています。'], 400);
                }
    
                $product->decrement('stock', $request->quantity);
    
                $sale = Sale::create([
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'total_price' => $product->price * $request->quantity, 
                ]);
    
                return response()->json([
                    'message' => '購入が完了しました。',
                    'sale' => $sale
                ], 201);
            });
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => '入力データが不正です', 'details' => $e->errors()], 422);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json(['error' => 'データベースエラー', 'details' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => 'サーバーエラー', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $sale = Sale::with('product')->findOrFail($id);
            return response()->json($sale);
        } catch (Exception $e) {
            return response()->json(['error' => 'Sale data not found.', 'message' => $e->getMessage()], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $sale = Sale::findOrFail($id);

            $request->validate([
                'quantity' => 'sometimes|integer|min:1',
            ]);

            if ($request->has('quantity')) {
                $sale->quantity = $request->quantity;
                $sale->total_price = $sale->product->price * $request->quantity;
                $sale->save();
            }

            return response()->json([
                'message' => 'データが更新されました。',
                'sale' => $sale
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update sale.', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $sale = Sale::findOrFail($id);
            $sale->delete();

            return response()->json(['message' => 'Sale deleted successfully.'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete sale.', 'message' => $e->getMessage()], 500);
        }
    
    }
}