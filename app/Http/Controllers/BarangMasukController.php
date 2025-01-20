<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\DataBarang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangMasukController extends Controller
{

    public function index()
    {
        $dataBarang = DataBarang::all();
        $barangMasuk = BarangMasuk::with('dataBarang')->get();

        return view('pages.barang-masuk', compact('dataBarang', 'barangMasuk'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'data_barang_id' => 'required|exists:data_barang,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        $barangMasuk = BarangMasuk::findOrFail($id);
        $barangMasuk->update([
            'data_barang_id' => $request->data_barang_id,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('barang-masuk.index')->with('success', 'Data barang masuk berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'data_barang_id' => 'required|exists:data_barang,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal' => 'required|date',
        ]);

        $barangMasuk = BarangMasuk::create([
            'data_barang_id' => $request->data_barang_id,
            'jumlah' => $request->jumlah,
            'tanggal' => $request->tanggal,
            'created_by' => Auth::id(),
            'updated_by' => null,
        ]);

        $barang = DataBarang::findOrFail($request->data_barang_id);
        $barang->stok += $request->jumlah;
        $barang->updated_by = Auth::id();
        $barang->save();

        return redirect()->route('barang-masuk.index')->with('success', 'Barang masuk berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        $barangMasuk = BarangMasuk::findOrFail($id);

        $barang = $barangMasuk->dataBarang;

        $barang->stok -= $barangMasuk->jumlah;
        $barang->updated_by = Auth::id();
        $barang->save();

        $barangMasuk->delete();

        return redirect()->route('barang-masuk.index')->with('success', 'Data barang masuk berhasil dihapus.');
    }

}
