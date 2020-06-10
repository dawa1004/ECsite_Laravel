<?php

namespace App\Http\Controllers;

use App\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 検索結果に含めるカラムを指定
        $cartitems = CartItem::select('cart_items.*', 'items.name', 'items.amount')
            // ログイン中のユーザーのユーザーIDをキーにしてカート内の商品を検索
            ->where('user_id', Auth::id())
            // cart_itemsテーブルとitemsテーブルの結合
            ->join('items', 'items.id', '=', 'cart_items.item_id')
            ->get(); // 検索結果を取得し、ビューに渡す
        $subtotal = 0;
        // 検索結果を一行ずつ取り出す
        foreach($cartitems as $cartitem) {
            // 「単価ｘ数量」の値を$subtotalに加算
            $subtotal += $cartitem->amount * $cartitem->quantity;
        }
        // 小計をビューに渡す
        return view('cartitem/index', ['cartitems' => $cartitems, 'subtotal' => $subtotal]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         // レコードの登録と更新を兼ねるメソッド
        CartItem::updateOrCreate(
            [
                'user_id' => Auth::id(), // ユーザーID取得
                'item_id' => $request->post('item_id'), // 商品IDの取得
            ],
            [
                'quantity' => \DB::raw('quantity + ' . $request->post('quantity') ), // 数量の取得
            ]
        );
        return redirect('/')->with('flash_message', 'カートに追加しました'); // 商品一覧へ戻る + メッセージ
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CartItem  $cartItem
     * @return \Illuminate\Http\Response
     */
    public function show(CartItem $cartItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CartItem  $cartItem
     * @return \Illuminate\Http\Response
     */
    public function edit(CartItem $cartItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CartItem  $cartItem
     * @return \Illuminate\Http\Response
     */
    // 更新元のカート情報と更新する数量を受け取る為のリクエスト情報を受け取る
    public function update(Request $request, CartItem $cartItem)
    {
        // 更新する元の数量を上書き
        $cartItem->quantity = $request->post('quantity');
        $cartItem->save();
        return redirect('cartitem')->with('flash_message', 'カートを更新しました');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CartItem  $cartItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(CartItem $cartItem)
    {
        $cartItem->delete();
        return redirect('cartitem')->with('flash_message', 'カートから削除しました');
    }
}
