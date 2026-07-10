<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RajaOngkirService
{
    protected $key;
    protected $base = 'https://rajaongkir.komerce.id/api/v1';

    public function __construct()
    {
        $this->key = config('services.rajaongkir.key');
    }

    public function searchDestination($search)
    {
        $res = Http::withHeaders(['key' => $this->key])
            ->timeout(15)
            ->withoutVerifying()
            ->get($this->base . '/destination/domestic-destination', [
                'search' => $search
            ]);
        return $res->json()['data'] ?? [];
    }

    public function getCost($originId, $destinationId, $weight, $courier)
    {
        $res = Http::withHeaders(['key' => $this->key])
            ->timeout(15)
            ->withoutVerifying()
            ->asForm()
            ->post($this->base . '/calculate/domestic-cost', [
                'origin'      => $originId,
                'destination' => $destinationId,
                'weight'      => $weight,
                'courier'     => $courier,
            ]);
        return $res->json()['data'] ?? [];
    }
}
