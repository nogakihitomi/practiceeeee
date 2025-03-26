@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><h2>商品情報編集画面</h2></div>

                    <div class="card-body">
                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                            @method('PUT')

            
                            <div class="mb-3">
                                <label for="product_id" class="form-label" value="{{ $product->id }}">ID. {{ $product->id }}</label>
                            </div>

                            <div class="mb-3">
    <label for="product_name" class="form-label">商品名:</label>
    <input id="product_name" type="text" name="product_name" class="form-control" value="{{ old('product_name', $product->product_name) }}">
    @if ($errors->has('product_name'))
        <p class="text-danger">{{ $errors->first('product_name') }}</p>
    @endif
</div>


        <div class="mb-3">
            <label for="company_id" class="form-label">メーカー名:</label>
            <select class="form-select" id="company_id" name="company_id">
                @foreach($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
    <label for="price" class="form-label">価格:</label>
    <input id="price" type="number" name="price" class="form-control" value="{{ old('price', $product->price) }}">
    @if ($errors->has('price'))
        <p class="text-danger">{{ $errors->first('price') }}</p>
    @endif
</div>


        <div class="mb-3">
            <label for="stock" class="form-label">在庫数:</label>
            <input id="stock" type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}">
            @if($errors->has('stock'))
             <p class="text-danger">{{ $errors->first('stock') }}</p>
                    @endif
        </div>

        <div class="mb-3">
            <label for="comment" class="form-label">コメント</label>
            <textarea id="comment" name="comment" class="form-control" rows="3">{{ old('comment', $product->comment) }}</textarea>
        </div>

                            <div class="mb-3">
                                <label for="img_path" class="form-label">商品画像:</label>
                                <input id="img_path" type="file" name="img_path" class="form-control">
                                @if ($product->img_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->img_path))
                                <img src="{{ asset('storage/' . $product->img_path) }}" alt="{{ $product->product_name }}" width="100">
                                @else
                                <img src="{{ asset($product->img_path) }}" alt="商品画像" width="100">
                                @endif                            
                            </div>

                            <button type="submit" class="btn btn-primary">更新</button>

                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">戻る</a>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection