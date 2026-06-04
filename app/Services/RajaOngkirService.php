<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RajaOngkirService
{
    protected $key;
    protected $base = 'https://api.rajaongkir.com/starter';

    public function __construct()
    {
        $this->key = config('services.rajaongkir.key');
    }

    public function getProvinces()
    {
        $res = Http::withHeaders(['key' => $this->key])
            ->timeout(15)
            ->get($this->base . '/province');
        return $res->json()['rajaongkir']['results'];
    }

    public function getCities($provinceId)
    {
        $res = Http::withHeaders(['key' => $this->key])
            ->timeout(15)
            ->get($this->base . '/city', ['province' => $provinceId]);
        return $res->json()['rajaongkir']['results'];
    }

    public function getCost($destination, $weight, $courier)
    {
        $res = Http::withHeaders(['key' => $this->key])
            ->timeout(15)
            ->post($this->base . '/cost', [
                'origin'      => config('services.rajaongkir.origin'),
                'destination' => $destination,
                'weight'      => $weight,
                'courier'     => $courier,
            ]);
        return $res->json()['rajaongkir']['results'][0]['costs'];
    }
}
