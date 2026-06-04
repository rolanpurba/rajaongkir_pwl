<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Cart, Order, OrderItem};
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->with('items')->latest()->get();
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) abort(403);
        $order->load('items.product');
        return view('orders.show', compact('order'));
    }

    public function adminIndex()
    {
        $orders = Order::with('user')->latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipient_name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'province' => 'required',
            'city' => 'required',
            'city_id' => 'required',
            'courier' => 'required',
            'courier_service' => 'required',
            'shipping_cost' => 'required|integer',
        ]);

        $cart = Cart::where('user_id', auth()->id())->with('items.product')->firstOrFail();
        $items = $cart->items;

        $subtotal = $items->sum(fn($i) => $i->product->price * $i->quantity);
        $totalWeight = $items->sum(fn($i) => $i->product->weight * $i->quantity);

        DB::transaction(function () use ($request, $cart, $items, $subtotal, $totalWeight) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'recipient_name' => $request->recipient_name,
                'phone' => $request->phone,
                'address' => $request->address,
                'province' => $request->province,
                'city' => $request->city,
                'city_id' => $request->city_id,
                'courier' => $request->courier,
                'courier_service' => $request->courier_service,
                'shipping_cost' => $request->shipping_cost,
                'total_price' => $subtotal + $request->shipping_cost,
                'total_weight' => $totalWeight,
                'status' => 'belum_bayar',
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                    'weight' => $item->product->weight,
                ]);
            }

            $cart->items()->delete();
        });

        return redirect()->route('orders.index')
            ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:belum_bayar,dikirim,selesai',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan diperbarui!');
    }
    public function destroy(Order $order)
    {
        // Pastikan hanya pemilik order yang bisa hapus
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Hanya boleh hapus jika belum bayar
        if ($order->status !== 'belum_bayar') {
            return back()->with('error', 'Pesanan yang sudah diproses tidak bisa dihapus.');
        }

        $order->items()->delete();
        $order->delete();

        return back()->with('success', 'Pesanan berhasil dihapus.');
    }
    public function invoice(Order $order)
    {
        // Pastikan hanya pemilik order atau admin yang bisa cetak
        if (auth()->user()->role !== 'admin' && $order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load('items.product', 'user');
        $pdf = Pdf::loadView('orders.invoice', compact('order'));
        return $pdf->download("invoice-order-{$order->id}.pdf");
    }

}

