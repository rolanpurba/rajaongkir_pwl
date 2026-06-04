<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart        = Cart::where('user_id', auth()->id())->firstOrFail();
        $items       = $cart->items()->with('product')->get();
        $totalWeight = $items->sum(fn($i) => $i->product->weight * $i->quantity);
        $totalPrice  = $items->sum(fn($i) => $i->product->price  * $i->quantity);
        $provinces   = $this->provinces();

        return view('checkout.index', compact('items', 'provinces', 'totalWeight', 'totalPrice'));
    }

    public function getCities(Request $request)
    {
        $cities = $this->cities()[$request->province_id] ?? [];
        return response()->json($cities);
    }

    public function getCost(Request $request)
    {
        $weight  = (int) $request->weight;
        $courier = strtoupper($request->courier);

        $rates = [
            'JNE'  => [
                ['service'=>'REG', 'description'=>'Reguler',           'cost'=>[['value'=> $weight*9,  'etd'=>'2-3']]],
                ['service'=>'YES', 'description'=>'Yakin Esok Sampai', 'cost'=>[['value'=> $weight*15, 'etd'=>'1']]],
                ['service'=>'OKE', 'description'=>'Ongkos Kirim Ekonomis','cost'=>[['value'=> $weight*7, 'etd'=>'3-5']]],
            ],
            'POS'  => [
                ['service'=>'Kilat Khusus', 'description'=>'Kilat Khusus', 'cost'=>[['value'=> $weight*8,  'etd'=>'2-4']]],
                ['service'=>'Biasa',        'description'=>'Pos Biasa',    'cost'=>[['value'=> $weight*5,  'etd'=>'5-7']]],
            ],
            'TIKI' => [
                ['service'=>'REG', 'description'=>'Reguler',   'cost'=>[['value'=> $weight*8,  'etd'=>'2-3']]],
                ['service'=>'ECO', 'description'=>'Ekonomis',  'cost'=>[['value'=> $weight*6,  'etd'=>'4-6']]],
                ['service'=>'ONS', 'description'=>'Over Night','cost'=>[['value'=> $weight*14, 'etd'=>'1']]],
            ],
        ];

        return response()->json($rates[$courier] ?? $rates['JNE']);
    }

    private function provinces(): array
    {
        return [
            ['id'=>'1', 'name'=>'Bali'],
            ['id'=>'2', 'name'=>'Bangka Belitung'],
            ['id'=>'3', 'name'=>'Banten'],
            ['id'=>'4', 'name'=>'Bengkulu'],
            ['id'=>'5', 'name'=>'DI Yogyakarta'],
            ['id'=>'6', 'name'=>'DKI Jakarta'],
            ['id'=>'7', 'name'=>'Gorontalo'],
            ['id'=>'8', 'name'=>'Jambi'],
            ['id'=>'9', 'name'=>'Jawa Barat'],
            ['id'=>'10','name'=>'Jawa Tengah'],
            ['id'=>'11','name'=>'Jawa Timur'],
            ['id'=>'12','name'=>'Kalimantan Barat'],
            ['id'=>'13','name'=>'Kalimantan Selatan'],
            ['id'=>'14','name'=>'Kalimantan Tengah'],
            ['id'=>'15','name'=>'Kalimantan Timur'],
            ['id'=>'16','name'=>'Kalimantan Utara'],
            ['id'=>'17','name'=>'Kepulauan Riau'],
            ['id'=>'18','name'=>'Lampung'],
            ['id'=>'19','name'=>'Maluku'],
            ['id'=>'20','name'=>'Maluku Utara'],
            ['id'=>'21','name'=>'Nusa Tenggara Barat'],
            ['id'=>'22','name'=>'Nusa Tenggara Timur'],
            ['id'=>'23','name'=>'Papua'],
            ['id'=>'24','name'=>'Papua Barat'],
            ['id'=>'25','name'=>'Riau'],
            ['id'=>'26','name'=>'Sulawesi Barat'],
            ['id'=>'27','name'=>'Sulawesi Selatan'],
            ['id'=>'28','name'=>'Sulawesi Tengah'],
            ['id'=>'29','name'=>'Sulawesi Tenggara'],
            ['id'=>'30','name'=>'Sulawesi Utara'],
            ['id'=>'31','name'=>'Sumatera Barat'],
            ['id'=>'32','name'=>'Sumatera Selatan'],
            ['id'=>'33','name'=>'Sumatera Utara'],
            ['id'=>'34','name'=>'Aceh'],
        ];
    }

    private function cities(): array
    {
        return [
            '1'  => [['id'=>'244','name'=>'Denpasar'],['id'=>'17','name'=>'Badung'],['id'=>'18','name'=>'Bangli'],['id'=>'45','name'=>'Buleleng'],['id'=>'92','name'=>'Gianyar'],['id'=>'124','name'=>'Jembrana'],['id'=>'161','name'=>'Karangasem'],['id'=>'196','name'=>'Klungkung'],['id'=>'236','name'=>'Tabanan']],
            '3'  => [['id'=>'395','name'=>'Tangerang'],['id'=>'396','name'=>'Kab. Tangerang'],['id'=>'397','name'=>'Tangerang Selatan'],['id'=>'82','name'=>'Cilegon'],['id'=>'233','name'=>'Serang'],['id'=>'19','name'=>'Lebak'],['id'=>'106','name'=>'Pandeglang']],
            '5'  => [['id'=>'163','name'=>'Yogyakarta'],['id'=>'39','name'=>'Bantul'],['id'=>'230','name'=>'Sleman'],['id'=>'102','name'=>'Gunung Kidul'],['id'=>'177','name'=>'Kulon Progo']],
            '6'  => [['id'=>'152','name'=>'Jakarta Pusat'],['id'=>'151','name'=>'Jakarta Barat'],['id'=>'153','name'=>'Jakarta Selatan'],['id'=>'154','name'=>'Jakarta Timur'],['id'=>'155','name'=>'Jakarta Utara']],
            '9'  => [['id'=>'22','name'=>'Bandung'],['id'=>'75','name'=>'Bekasi'],['id'=>'114','name'=>'Bogor'],['id'=>'243','name'=>'Depok'],['id'=>'79','name'=>'Cimahi'],['id'=>'127','name'=>'Karawang'],['id'=>'241','name'=>'Sukabumi'],['id'=>'83','name'=>'Cirebon'],['id'=>'224','name'=>'Tasikmalaya'],['id'=>'94','name'=>'Garut']],
            '10' => [['id'=>'234','name'=>'Semarang'],['id'=>'322','name'=>'Surakarta'],['id'=>'290','name'=>'Purwokerto'],['id'=>'222','name'=>'Tegal'],['id'=>'252','name'=>'Magelang'],['id'=>'174','name'=>'Klaten'],['id'=>'156','name'=>'Jepara'],['id'=>'184','name'=>'Kudus']],
            '11' => [['id'=>'444','name'=>'Surabaya'],['id'=>'255','name'=>'Malang'],['id'=>'101','name'=>'Gresik'],['id'=>'169','name'=>'Lamongan'],['id'=>'323','name'=>'Sidoarjo'],['id'=>'166','name'=>'Kediri'],['id'=>'152','name'=>'Jember'],['id'=>'42','name'=>'Banyuwangi']],
            '12' => [['id'=>'89','name'=>'Pontianak'],['id'=>'90','name'=>'Kab. Pontianak'],['id'=>'246','name'=>'Singkawang'],['id'=>'172','name'=>'Ketapang'],['id'=>'336','name'=>'Sambas']],
            '13' => [['id'=>'50','name'=>'Banjarmasin'],['id'=>'51','name'=>'Banjarbaru'],['id'=>'52','name'=>'Kab. Banjar'],['id'=>'248','name'=>'Kotabaru'],['id'=>'182','name'=>'Tabalong']],
            '15' => [['id'=>'197','name'=>'Samarinda'],['id'=>'198','name'=>'Balikpapan'],['id'=>'199','name'=>'Bontang'],['id'=>'200','name'=>'Kutai Kartanegara'],['id'=>'201','name'=>'Berau']],
            '17' => [['id'=>'264','name'=>'Batam'],['id'=>'265','name'=>'Tanjung Pinang'],['id'=>'266','name'=>'Bintan'],['id'=>'267','name'=>'Karimun']],
            '25' => [['id'=>'263','name'=>'Pekanbaru'],['id'=>'99','name'=>'Dumai'],['id'=>'138','name'=>'Kampar'],['id'=>'315','name'=>'Siak']],
            '27' => [['id'=>'257','name'=>'Makassar'],['id'=>'258','name'=>'Maros'],['id'=>'260','name'=>'Gowa'],['id'=>'54','name'=>'Pare-Pare'],['id'=>'175','name'=>'Bone']],
            '31' => [['id'=>'318','name'=>'Padang'],['id'=>'319','name'=>'Bukittinggi'],['id'=>'320','name'=>'Payakumbuh'],['id'=>'321','name'=>'Solok']],
            '32' => [['id'=>'323','name'=>'Palembang'],['id'=>'324','name'=>'Prabumulih'],['id'=>'325','name'=>'Lubuk Linggau'],['id'=>'326','name'=>'Pagar Alam']],
            '33' => [['id'=>'318','name'=>'Medan'],['id'=>'44','name'=>'Binjai'],['id'=>'86','name'=>'Deli Serdang'],['id'=>'165','name'=>'Pematang Siantar'],['id'=>'395','name'=>'Tebing Tinggi']],
            '34' => [['id'=>'1','name'=>'Banda Aceh'],['id'=>'2','name'=>'Sabang'],['id'=>'3','name'=>'Lhokseumawe'],['id'=>'4','name'=>'Langsa'],['id'=>'5','name'=>'Subulussalam']],
        ];
    }
}
