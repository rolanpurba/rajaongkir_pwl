<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Services\RajaOngkirService;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    protected $rajaOngkir;

    public function __construct(RajaOngkirService $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    public function index()
    {
        $cart        = Cart::where('user_id', auth()->id())->firstOrFail();
        $items       = $cart->items()->with('product')->get();
        $totalWeight = $items->sum(fn($i) => $i->product->weight * $i->quantity);
        $totalPrice  = $items->sum(fn($i) => $i->product->price  * $i->quantity);

        return view('checkout.index', compact('items', 'totalWeight', 'totalPrice'));
    }

    public function searchDestination(Request $request)
    {
        $results = $this->rajaOngkir->searchDestination($request->search);
        return response()->json($results);
    }

    public function getCost(Request $request)
    {
        $originId      = config('services.rajaongkir.origin');
        $destinationId = $request->destination_id;
        $weight        = (int) $request->weight;
        $courier       = strtolower($request->courier);

        $results = $this->rajaOngkir->getCost($originId, $destinationId, $weight, $courier);
        return response()->json($results);
    }
}
