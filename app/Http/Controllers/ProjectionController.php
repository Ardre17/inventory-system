<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProjectionController extends Controller
{
    public function index()
    {
        $products   = Product::where('active', true)->with('category')->get();
        $now        = Carbon::now();

        // Periodos de análisis
        $last4Weeks  = $now->copy()->subWeeks(4);
        $last1Month  = $now->copy()->subMonth();
        $last3Months = $now->copy()->subMonths(3);

        $projections = $products->map(function($product) use ($now, $last4Weeks, $last1Month, $last3Months) {

            // Ventas últimas 4 semanas
            $sales4w = OrderItem::whereHas('order', function($q) use ($last4Weeks) {
                $q->where('type', 'sale')
                  ->where('status', 'completed')
                  ->where('created_at', '>=', $last4Weeks);
            })->where('product_id', $product->id)
              ->sum('quantity_sent');

            // Ventas último mes
            $sales1m = OrderItem::whereHas('order', function($q) use ($last1Month) {
                $q->where('type', 'sale')
                  ->where('status', 'completed')
                  ->where('created_at', '>=', $last1Month);
            })->where('product_id', $product->id)
              ->sum('quantity_sent');

            // Ventas últimos 3 meses
            $sales3m = OrderItem::whereHas('order', function($q) use ($last3Months) {
                $q->where('type', 'sale')
                  ->where('status', 'completed')
                  ->where('created_at', '>=', $last3Months);
            })->where('product_id', $product->id)
              ->sum('quantity_sent');

            // Proyecciones
            $proyWeek  = $sales4w > 0 ? round($sales4w / 4) : 0;
            $proyMonth = $sales1m > 0 ? round($sales1m) : ($sales3m > 0 ? round($sales3m / 3) : 0);

            // Días de stock disponible
            $dailySales    = $proyMonth > 0 ? $proyMonth / 30 : 0;
            $daysOfStock   = $dailySales > 0 ? round($product->stock / $dailySales) : null;

            // Alertas
            $alert = null;
            if ($dailySales > 0) {
                if ($daysOfStock !== null && $daysOfStock <= 7) {
                    $alert = 'critical';
                } elseif ($daysOfStock !== null && $daysOfStock <= 15) {
                    $alert = 'warning';
                } elseif ($daysOfStock !== null && $daysOfStock <= 30) {
                    $alert = 'info';
                }
            }

            // Cuánto producir
            $toProduceWeek  = max(0, $proyWeek - $product->stock);
            $toProduceMonth = max(0, $proyMonth - $product->stock);

            return [
                'product'         => $product,
                'stock'           => $product->stock,
                'sales_4w'        => $sales4w,
                'sales_1m'        => $sales1m,
                'sales_3m'        => $sales3m,
                'proy_week'       => $proyWeek,
                'proy_month'      => $proyMonth,
                'days_of_stock'   => $daysOfStock,
                'to_produce_week' => $toProduceWeek,
                'to_produce_month'=> $toProduceMonth,
                'alert'           => $alert,
                'daily_sales'     => round($dailySales, 1),
            ];
        });

        // Ordenar por alerta primero
        $projections = $projections->sortByDesc(function($p) {
            return match($p['alert']) {
                'critical' => 3,
                'warning'  => 2,
                'info'     => 1,
                default    => 0,
            };
        })->values();

        // Resumen global
        $summary = [
            'critical' => $projections->where('alert', 'critical')->count(),
            'warning'  => $projections->where('alert', 'warning')->count(),
            'info'     => $projections->where('alert', 'info')->count(),
            'ok'       => $projections->whereNull('alert')->count(),
            'total_proy_week'  => $projections->sum('proy_week'),
            'total_proy_month' => $projections->sum('proy_month'),
        ];

        return view('projections.index', compact('projections', 'summary'));
    }
}
