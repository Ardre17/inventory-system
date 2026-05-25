<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Supply;

class SupplySeeder extends Seeder
{
    public function run(): void
    {
        $supplies = [
            ['type'=>'sticker','variant'=>'30mm',           'name'=>'Sticker 30mm',           'code'=>'STICKER-30MM'],
            ['type'=>'sticker','variant'=>'43mm',           'name'=>'Sticker 43mm',           'code'=>'STICKER-43MM'],
            ['type'=>'sticker','variant'=>'50mm',           'name'=>'Sticker 50mm',           'code'=>'STICKER-50MM'],
            ['type'=>'sticker','variant'=>'55mm',           'name'=>'Sticker 55mm',           'code'=>'STICKER-55MM'],
            ['type'=>'sticker','variant'=>'65mm',           'name'=>'Sticker 65mm',           'code'=>'STICKER-65MM'],
            ['type'=>'sticker','variant'=>'70mm',           'name'=>'Sticker 70mm',           'code'=>'STICKER-70MM'],
            ['type'=>'sticker','variant'=>'85mm',           'name'=>'Sticker 85mm',           'code'=>'STICKER-85MM'],
            ['type'=>'precinto','variant'=>'74x30',         'name'=>'Precinto 74x30',         'code'=>'PRECINTO-74X30'],
            ['type'=>'precinto','variant'=>'94x30',         'name'=>'Precinto 94x30',         'code'=>'PRECINTO-94X30'],
            ['type'=>'precinto','variant'=>'97x30',         'name'=>'Precinto 97x30',         'code'=>'PRECINTO-97X30'],
            ['type'=>'precinto','variant'=>'106x30',        'name'=>'Precinto 106x30',        'code'=>'PRECINTO-106X30'],
            ['type'=>'precinto','variant'=>'118x30',        'name'=>'Precinto 118x30',        'code'=>'PRECINTO-118X30'],
            ['type'=>'precinto','variant'=>'128x30',        'name'=>'Precinto 128x30',        'code'=>'PRECINTO-128X30'],
            ['type'=>'precinto','variant'=>'138x30',        'name'=>'Precinto 138x30',        'code'=>'PRECINTO-138X30'],
            ['type'=>'precinto','variant'=>'175x30',        'name'=>'Precinto 175x30',        'code'=>'PRECINTO-175X30'],
            ['type'=>'precinto','variant'=>'aliño_2lt',     'name'=>'Precinto Aliño 2Lt',     'code'=>'PRECINTO-ALINO2LT'],
            ['type'=>'precinto','variant'=>'aliños_pequeños','name'=>'Precinto Aliños Pequeños','code'=>'PRECINTO-ALINOPEQ'],
            ['type'=>'etiqueta','variant'=>'local',         'name'=>'Etiqueta Local',         'code'=>'ETIQUETA-LOCAL'],
            ['type'=>'etiqueta','variant'=>'ingles',        'name'=>'Etiqueta Inglés',        'code'=>'ETIQUETA-INGLES'],
            ['type'=>'etiqueta','variant'=>'portugues',     'name'=>'Etiqueta Portugués',     'code'=>'ETIQUETA-PORTUGUES'],
        ];

        foreach ($supplies as $data) {
            Supply::firstOrCreate(
                ['code' => $data['code']],
                array_merge($data, ['stock'=>0,'stock_min'=>0,'units_per_roll'=>1000])
            );
        }
    }
}
