@extends('layouts.app')

@section('content')



<div class="container">
    <h1 class="mb-4">商品一覧画面</h1>

    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">商品新規登録</a>

     <div class="search mt-5">
    
    
    <form action="{{ route('products.index') }}" method="GET" class="row g-3">
        @csrf
        <div class="col-sm-12 col-md-4">
            <input type="text" name="search" class="form-control" placeholder="検索キーワード" value="{{ request('search') }}">
        </div>

        <div class="col-sm-12 col-md-4">
                <select class="form-select" name="search-company" value="{{ request('searchCompany') }}" placeholder="メーカーを選択">
                    <option value="" selected>メーカー名</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                    @endforeach
                </select>    
        </div>

        <div class="w-100"></div>


        <div class="col-sm-12 col-md-2">
            <input type="number" name="min_price" class="form-control" placeholder="最小価格" value="{{ request('min_price') }}">
        </div>

        <div class="col-sm-12 col-md-2">
            <input type="number" name="max_price" class="form-control" placeholder="最大価格" value="{{ request('max_price') }}">
        </div>

        <div class="col-sm-12 col-md-2">
            <input type="number" name="min_stock" class="form-control" placeholder="最小在庫" value="{{ request('min_stock') }}">
        </div>

        <div class="col-sm-12 col-md-2">
            <input type="number" name="max_stock" class="form-control" placeholder="最大在庫" value="{{ request('max_stock') }}">
        </div>


        <div class="col-sm-12 col-md-1">
            <button class="btn btn-outline-secondary" type="submit">検索</button>
        </div>
        </form>
</div>

<script>
        $(document).ready(function() {
            $('button[type="submit"]').on('click', function(e) {
                e.preventDefault();

                let searchKeyword = $('input[name="search"]').val();
                let searchCompany = $('select[name="search-company"]').val();
                let minPrice = $('input[name="min_price"]').val();
                let maxPrice = $('input[name="max_price"]').val();
                let minStock = $('input[name="min_stock"]').val();
                let maxStock = $('input[name="max_stock"]').val();

                $.ajax({
                    url: '{{ route('search') }}',
                    method: 'GET',
                    data: {
                        search: searchKeyword,
                        search_company: searchCompany,
                        min_price: minPrice,
                        max_price: maxPrice,
                        min_stock: minStock,
                        max_stock: maxStock,
                    },
                        dataType: 'json',
                        success: function (response) {
                            let resultsHtml = '';
                            if (response.length > 0) {
                                response.forEach(function (product) {
                                    resultsHtml += `
                                    <tr>
                                    <td>${product.id}</td>
                                    <td><img src="${product.img_path}" alt="${product.product_name}" width="100"></td>
                                    <td>${product.product_name}</td>
                                    <td>${product.price}</td>
                                    <td>${product.stock}</td>
                                    <td>${product.company_name}</td>
                                    </tr>
                                    `;
                                });
                            } else {
                                resultsHtml = '<tr><td colspan="6">該当する商品がありません。</td></tr>';
                            }
                            $('#search-results').html(resultsHtml);
                        },
                        error: function () {
                            alert('検索に失敗しました');
                        }
                });
            });
        });
    </script>


    <div class="products mt-5">
        <h2>商品情報</h2>
        <table class="table table-striped">
            <thead>
            <tr>
            <th>ID</th>
            <th>商品画像</th>
            <th>商品名</th>
            <th>価格
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'price', 'direction' => 'asc']) }}">↑</a>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'price', 'direction' => 'desc']) }}">↓</a>
            </th>
            <th>在庫数
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'stock', 'direction' => 'asc']) }}">↑</a>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'stock', 'direction' => 'desc']) }}">↓</a>
            </th>
            <th>メーカー名</th>
            </tr>
            </thead>
            <tbody id="search-results">
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>
                    @if ($product->img_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($product->img_path))
                    <img src="{{ asset('storage/' . $product->img_path) }}" alt="{{ $product->product_name }}" width="100">
                    @else
                    <img src="{{ asset($product->img_path) }}" alt="商品画像" width="100">
                    @endif
                    </td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->company->company_name }}</td>
                    <td>
                        <a href="{{ route('products.show', $product) }}" class="btn btn-info btn-sm mx-1">詳細</a>                       
                         <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm mx-1 delete-btn" data-id="{{ $product->id }}">削除</button>
                        </form>
                    </td>
                </tr>
            @endforeach

            <script>
            $(".delete-button").on("click", function () {
                let saleId = $(this).data("id");

                        $.ajax({
                            url: `http://localhost:8000/api/sales/${saleId}`,
                            method: 'DELETE',
                            dataType: "json",
                            success: function(response) {
                                if (response.message) {
                                    $("#sale-" + saleId).fadeOut(500, function () {
                                    $(this).remove();
                                });
                            } else {
                                alert("削除に失敗しました");
                            }
                        },
                        error: function (xhr) {
                            alert("削除エラー: " + xhr.responseJSON.error);
                        }
                    });
                });
            </script>
            
  

            </tbody>
        </table>
    </div>
    
    
    {{ $products->appends(request()->query())->links() }}
</div>
@endsection