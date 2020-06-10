@extends('layouts.app')

@section('content')
  @if(Session::has('flash_message'))
    <div class="alert alert-success">
      {{ session('flash_message') }}
    </div>
  @endif
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    @foreach ($cartitems as $cartitem)
                        <div class="card-header">
                            <a href="/item/{{ $cartitem->item_id }}">{{ $cartitem->name }}</a>
                        </div>
                        <div class="card-body">
                            <div>
                                {{ $cartitem->amount }}円
                            </div>
                            <div class="form-inline">
                              <!-- 数量を更新 -->
                              <form method="POST" action="/cartitem/{{ $cartitem->id }}">
                                @method('PUT')
                                @csrf
                                <!-- 数量の表示 -->
                                <input type="text" class="form-control" name="quantity" value="{{ $cartitem->quantity }}">
                                個
                                <button type="submit" class="btn btn-primary">更新</button>
                              </form>
                              <!-- 削除フォーム -->
                              <form method="POST" action="/cartitem/{{ $cartitem->id }}">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-primary ml-1">カートから削除する</button>
                              </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        小計
                    </div>
                    <div class="card-body">
                        {{ $subtotal }}円
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
