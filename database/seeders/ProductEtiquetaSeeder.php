<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Supply;
use App\Models\Product;

class ProductEtiquetaSeeder extends Seeder
{
    public function run(): void
    {
        // Eliminar etiquetas genéricas
        Supply::where('type', 'etiqueta')->whereNull('product_id')->delete();

        // Crear 3 etiquetas por cada producto
        Product::all()->each(function ($product) {
            foreach (['local' => 'Local', 'ingles' => 'Inglés', 'portugues' => 'Portugués'] as $variant => $label) {
                Supply::firstOrCreate(
                    [
                        'type'       => 'etiqueta',
                        'variant'    => $variant,
                        'product_id' => $product->id,
                    ],
                    [
                        'code'          => 'ETIQUETA-' . $product->id . '-' . strtoupper($variant),
                        'name'          => $product->name . ' — ' . $label,
                        'stock'         => 0,
                        'stock_min'     => 0,
                        'units_per_roll'=> 1000,
                    ]
                );
            }
        });
    }
}
