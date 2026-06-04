<?php

namespace App\Http\Controllers;
use App\Models\{Cart, CartItem, Product};
use Illuminate\Http\Request;


class CartController
{
    public function index()
    {
        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
        $items = $cart->items()->with('product')->get();
        $totalWeight = $items->sum(fn($i) => $i->product->weight * $i->quantity);
        $totalPrice  = $items->sum(fn($i) => $i->product->price  * $i->quantity);
        return view('cart.index', compact('items', 'totalWeight', 'totalPrice'));
    }

    public function add(Request $request, Product $product)
    {
        $cart = Cart::firstOrCreate(['user_id' => auth()->id()]);
        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            $item->increment('quantity');
        } else {
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity'   => 1,
            ]);
        }
        return back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    public function remove(CartItem $item)
    {
        $item->delete();
        return back()->with('success', 'Produk dihapus dari keranjang.');
    }
}
