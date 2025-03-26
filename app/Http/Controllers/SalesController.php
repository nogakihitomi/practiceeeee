<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;


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
        DB::beginTransaction();
        Log::info('受信したリクエストデータ:', $request->all());
        
        try {
            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $product = Product::findOrFail($validated['product_id']);

            if ($product->stock < $validated['quantity']) {
                return response()->json(['error' => '在庫が不足しています。'], 400);
            }

            $product->decrement('stock', $validated['quantity']);

            Sale::create([
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
            ]);

            DB::commit();  // トランザクションのコミット

            return response()->json([
                'message' => '購入が完了しました。',
                'remaining_stock' => $product->stock
            ], 200);

        } catch (\Exception $e) {
            DB::rollBack(); // エラーが発生した場合、ロールバック
            Log::error("購入処理中にエラーが発生しました: " . $e->getMessage());
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
