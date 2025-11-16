<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Category;
use App\Models\TaxRate;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $allCategories = Category::orderBy('name')->get();

        $query = Product::with(['batches', 'category']);


        $query->when($request->filled('category_id'), function ($q) use ($request) {
            return $q->where('category_id', $request->category_id);
        });

        $query->when($request->filled('tgl_masuk_from'), function ($q) use ($request) {
            return $q->whereHas('batches', function ($batchQuery) use ($request) {
                $batchQuery->where('tgl_masuk', '>=', $request->tgl_masuk_from);
            });
        });
        $query->when($request->filled('tgl_masuk_to'), function ($q) use ($request) {
            return $q->whereHas('batches', function ($batchQuery) use ($request) {
                $batchQuery->where('tgl_masuk', '<=', $request->tgl_masuk_to);
            });
        });

        $query->when($request->filled('tgl_expired_from'), function ($q) use ($request) {
            return $q->whereHas('batches', function ($batchQuery) use ($request) {
                $batchQuery->where('tgl_expired', '>=', $request->tgl_expired_from);
            });
        });
        $query->when($request->filled('tgl_expired_to'), function ($q) use ($request) {
            return $q->whereHas('batches', function ($batchQuery) use ($request) {
                $batchQuery->where('tgl_expired', '<=', $request->tgl_expired_to);
            });
        });

        $products = $query->latest()->get();

        return view('products.index', [
            'products' => $products,
            'allCategories' => $allCategories,
        ]);
    }
    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $taxRates = TaxRate::orderBy('name')->get();

        return view('products.create', [
            'categories' => $categories,
            'taxRates' => $taxRates
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => 'nullable|string|max:100|unique:products,sku',
            'harga_jual' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id','tax_rate_id' => 'nullable|exists:tax_rates,id',
        ]);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produk baru berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {

        $categories = Category::orderBy('name')->get();$taxRates = TaxRate::orderBy('name')->get();

        return view('products.edit', [
            'product' => $product,
            'categories' => $categories,'taxRates' => $taxRates
        ]);
    }
    public function update(Request $request, Product $product)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sku' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('products')->ignore($product->id),
            ],
            'harga_jual' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id','tax_rate_id' => 'nullable|exists:tax_rates,id',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
    public function showStock(Product $product)
    {

        $batches = $product->batches()
            ->where('stok_sisa', '>', 0)
            ->orderByRaw('CASE WHEN tgl_expired IS NULL THEN 1 ELSE 0 END, tgl_expired ASC, tgl_masuk ASC')
            ->get();


        return view('products.stock_show', [
            'product' => $product,
            'batches' => $batches,
        ]);
    }
    public function print(Request $request)
    {
        $batchFilter = function ($batchQuery) use ($request) {
            $batchQuery->where('stok_sisa', '>', 0);

            $batchQuery->when($request->filled('tgl_masuk_from'), function ($q) use ($request) {
                return $q->where('tgl_masuk', '>=', $request->tgl_masuk_from);
            });
            $batchQuery->when($request->filled('tgl_masuk_to'), function ($q) use ($request) {
                return $q->where('tgl_masuk', '<=', $request->tgl_masuk_to);
            });
            $batchQuery->when($request->filled('tgl_expired_from'), function ($q) use ($request) {
                return $q->where('tgl_expired', '>=', $request->tgl_expired_from);
            });
            $batchQuery->when($request->filled('tgl_expired_to'), function ($q) use ($request) {
                return $q->where('tgl_expired', '<=', $request->tgl_expired_to);
            });
        };

        $query = Product::with('category')
            ->whereHas('batches', $batchFilter);


        $query->when($request->filled('category_id'), function ($q) use ($request) {
            return $q->where('category_id', $request->category_id);
        });


        $products = $query->with(['batches' => $batchFilter])
            ->latest()
            ->get();

        $filteredCategory = $request->filled('category_id') ? Category::find($request->category_id) : null;

        return view('products.print', [
            'products' => $products,
            'filteredCategoryName' => $filteredCategory ? $filteredCategory->name : 'Semua Kategori',
            'printDate' => now()->format('d M Y, H:i:s'),
            'filters' => $request->only(['tgl_masuk_from', 'tgl_masuk_to', 'tgl_expired_from', 'tgl_expired_to']),
        ]);
    }
}
