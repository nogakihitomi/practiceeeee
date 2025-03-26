@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">商品新規登録画面</h1>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">

        @csrf



        <div class="mb-3">
            <label for="product_name" class="form-label">商品名:</label>
            <input id="product_name" type="text" name="product_name" class="form-control">
            @if($errors->has('product_name'))
            <p class="text-danger">{{ $errors->first('product_name') }}</p>
                    @endif
        </div>

        <div class="mb-3">
            <label for="company_id" class="form-label">メーカー名</label>
            <select class="form-select" id="company_id" name="company_id">
                @foreach($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">価格:</label>
            <input id="price" type="text" name="price" class="form-control">
            @if($errors->has('price'))
            <p class="text-danger">{{ $errors->first('price') }}</p>
                    @endif
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">在庫数:</label>
            <input id="stock" type="text" name="stock" class="form-control">
            @if($errors->has('stock'))
            <p class="text-danger">{{ $errors->first('stock') }}</p>
                    @endif
        </div>

        <div class="mb-3">
            <label for="comment" class="form-label">コメント</label>
            <textarea id="comment" name="comment" class="form-control" rows="3"></textarea>
        </div>

        <div class="mb-3">
            <label for="img_path" class="form-label">商品画像:</label>
            <input id="img_path" type="file" name="img_path" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">新規登録</button>

        <a href="{{ route('products.index') }}" class="btn btn-primary">戻る</a>
        

    </form>

</div>
@endsection