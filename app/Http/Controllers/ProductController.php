<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Supply;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
{
    $categories = Category::all();
    $query = Product::with(['category', 'supplier']);

    if (request('category_id')) {
        $query->where('category_id', request('category_id'));
    }
    if (request('low_stock')) {
        $query->whereColumn('stock', '<=', 'stock_min');
    }
    if (request('search')) {
        $query->where('name', 'like', '%' . request('search') . '%');
    }

    $products = $query->get();
    return view('products.index', compact('products', 'categories'));
}

    public function create()
    {
        $categories = Category::where('active', true)->get();
        $suppliers  = Supplier::where('active', true)->get();
        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'stock_min'   => 'required|integer|min:0',
        ]);

        $product = Product::create($request->all());
        $this->createLabelsForProduct($product);

        return redirect()->route('products.index')
            ->with('success', 'Producto creado correctamente');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('active', true)->get();
        $suppliers  = Supplier::where('active', true)->get();
        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'stock_min'   => 'required|integer|min:0',
        ]);
        $product->update($request->all());
        return redirect()->route('products.index')
            ->with('success', 'Producto actualizado correctamente');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')
            ->with('success', 'Producto eliminado correctamente');
    }

    public function show(Product $product)
    {
        return redirect()->route('products.index');
    }

    public function generateLabels(Product $product)
    {
        $this->createLabelsForProduct($product);
        return redirect()->route('products.index')
            ->with('success', 'Etiquetas generadas para ' . $product->name);
    }

    private function createLabelsForProduct($product)
    {
        $category      = $product->category;
        $isAlinosSmall = $category &&
                         stripos($category->name, 'aliño') !== false &&
                         stripos($category->name, 'pequeño') !== false;

        if ($isAlinosSmall) {
            $tipos = [
                'cuello'    => 'Cuello',
                'delantera' => 'Delantera',
                'trasera'   => 'Trasera',
            ];
            foreach ($tipos as $variant => $label) {
                $exists = Supply::where('type', 'etiqueta')
                    ->where('variant', $variant)
                    ->where('product_id', $product->id)
                    ->exists();
                if (!$exists) {
                    Supply::create([
                        'code'           => 'ETQ-' . strtoupper(substr(preg_replace('/\s+/', '', $product->name), 0, 6)) . '-' . strtoupper($variant),
                        'name'           => $product->name . ' — ' . $label,
                        'type'           => 'etiqueta',
                        'variant'        => $variant,
                        'stock'          => 0,
                        'stock_min'      => 0,
                        'units_per_roll' => 1000,
                        'product_id'     => $product->id,
                    ]);
                }
            }
        } else {
            $variantes = [
                'local'     => 'Local',
                'ingles'    => 'Inglés',
                'portugues' => 'Portugués',
            ];
            foreach ($variantes as $variant => $label) {
                $exists = Supply::where('type', 'etiqueta')
                    ->where('variant', $variant)
                    ->where('product_id', $product->id)
                    ->exists();
                if (!$exists) {
                    Supply::create([
                        'code'           => 'ETQ-' . strtoupper(substr(preg_replace('/\s+/', '', $product->name), 0, 6)) . '-' . strtoupper($variant),
                        'name'           => $product->name . ' — ' . $label,
                        'type'           => 'etiqueta',
                        'variant'        => $variant,
                        'stock'          => 0,
                        'stock_min'      => 0,
                        'units_per_roll' => 1000,
                        'product_id'     => $product->id,
                    ]);
                }
            }
        }
    }
}
