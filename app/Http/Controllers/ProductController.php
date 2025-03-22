<?php


namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company; 
use Illuminate\Http\Request;
use App\Http\Requests\ProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;




class ProductController extends Controller 
{

    public function index(Request $request)
    { 
        $search = $request->input('search');
        $search_company = $request->input('search-company');

        $query = Product::query();
        $products = Product::all();


    
        if(!empty($search)){
            $query->where('product_name', 'LIKE', "%{$search}%");
        }

        if(!empty($search_company)){
            $query->where('company_id', $search_company);
        }

        if($min_price = $request->min_price){
            $query->where('price', '>=', $min_price);
        }
    
        if($max_price = $request->max_price){
            $query->where('price', '<=', $max_price);
        }
    
        if($min_stock = $request->min_stock){
            $query->where('stock', '>=', $min_stock);
        }
    
        if($max_stock = $request->max_stock){
            $query->where('stock', '<=', $max_stock);
        }
    
        if($sort = $request->sort){
            $direction = $request->direction == 'desc' ? 'desc' : 'asc';
            $query->orderBy($sort, $direction);
        }
    
        $products = $query->paginate(10);
        $companies = Company::all();



        return view('products.index', compact('products', 'companies'));

    }
    

    public function create()
    {
        $companies = Company::all();

        return view('products.create', compact('companies'));
    }

    public function store(ProductRequest $request){

    $request->validate([
        'product_name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'company_id' => 'required|exists:companies,id',
        'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    try {
        DB::beginTransaction();

        $product = new Product();
        $product->createProduct($request);

        DB::commit();

        return redirect()->route('products.index')
            ->with('success', '商品が正常に登録されました！');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()
            ->with('error', '商品の登録中にエラーが発生しました: ' . $e->getMessage())
            ->withInput();
    }
}


    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect()->route('products.index')->with('error', '商品が見つかりませんでした。');
        }

        $companies = Company::all();
        return view('products.show', compact('product', 'companies'));
    }



    public function edit($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return redirect()->route('products.index')->with('error', '商品が見つかりませんでした。');
        }

        $companies = Company::all();
        return view('products.edit', compact('product', 'companies'));
    }


    public function update(UpdateProductRequest $request, $id)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'comment' => 'nullable|string',
            'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        try {
            DB::beginTransaction();
    
            $product = Product::findOrFail($id);
            $product->updateProduct($request);

            DB::commit();
    
            return redirect()->route('products.index')
            ->with('success', '商品が正常に更新されました！');
        } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()
            ->with('error', '商品の更新中にエラーが発生しました: ' . $e->getMessage())
            ->withInput();
        }
    }

    


    public function destroy($id)
        {
            $product = Product::findOrFail($id);
            $product->delete();

    
            return response()->json(['message' => '商品が削除されました。']);
        }

    public function search(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $query->where('product_name', 'LIKE', '%' . $request->search . '%');
        }
    
        if ($request->filled('search_company')) {
            $query->where('company_id', $request->search_company);
        }
    
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
    
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
    
        $products = $query->with('company')->get();
        
        return response()->json($products);
    }


    

    
}