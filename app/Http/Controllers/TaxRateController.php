<?php

namespace App\Http\Controllers;

use App\Models\TaxRate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaxRateController extends Controller
{
    /**
     * Menampilkan daftar tarif pajak & formulir tambah.
     */
    public function index()
    {
        $taxRates = TaxRate::withCount('products')->latest()->get();
        return view('tax_rates.index', [
            'taxRates' => $taxRates
        ]);
    }

    /**
     * Menyimpan tarif pajak baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tax_rates,name',
            'rate_percent' => 'required|numeric|min:0|max:100',
        ]);

        TaxRate::create([
            'name' => $validated['name'],
            'rate' => $validated['rate_percent'] / 100, // Konversi 11 menjadi 0.11
        ]);

        return redirect()->route('tax-rates.index')
                         ->with('success', 'Tarif pajak baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan formulir edit.
     */
    public function edit(TaxRate $taxRate)
    {
        return view('tax_rates.edit', [
            'taxRate' => $taxRate
        ]);
    }

    /**
     * Memperbarui tarif pajak.
     */
    public function update(Request $request, TaxRate $taxRate)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tax_rates')->ignore($taxRate->id),
            ],
            'rate_percent' => 'required|numeric|min:0|max:100',
        ]);

        $taxRate->update([
            'name' => $validated['name'],
            'rate' => $validated['rate_percent'] / 100, // Konversi 11 menjadi 0.11
        ]);

        return redirect()->route('tax-rates.index')
                         ->with('success', 'Tarif pajak berhasil diperbarui.');
    }

    /**
     * Menghapus tarif pajak.
     */
    public function destroy(TaxRate $taxRate)
    {

        $productCount = $taxRate->products()->count();
        if ($productCount > 0) {
            return redirect()->route('tax-rates.index')
                             ->with('error', "Tarif pajak tidak bisa dihapus karena masih digunakan oleh {$productCount} produk.");
        }

        $taxRate->delete();

        return redirect()->route('tax-rates.index')
                         ->with('success', 'Tarif pajak berhasil dihapus.');
    }
}
