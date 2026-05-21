<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'supplier'])->get();
        return view('products.index', compact('products'));
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
        Product::create($request->all());
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
}