<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DataBarang;
use App\Models\BarangMasuk;
use App\Models\BarangKeluar;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function root(Request $request)
    {
        // Hitung total user dan data barang
        $userCount = User::count();
        $dataBarangCount = DataBarang::count();

        // Ambil daftar tahun dari tabel BarangMasuk dan BarangKeluar
        $years = BarangMasuk::selectRaw('YEAR(tanggal) as year')
            ->distinct()
            ->union(
                BarangKeluar::selectRaw('YEAR(tanggal) as year')->distinct()
            )
            ->orderByDesc('year')
            ->pluck('year')
            ->toArray();

        // Gunakan tahun sekarang jika daftar tahun kosong
        $currentYear = now()->year;
        if (empty($years)) {
            $years = [$currentYear];
        }
        $selectedYear = $request->input('year', max($years));
        $selectedYear = in_array($selectedYear, $years) ? $selectedYear : max($years);

        $selectedMonth = $request->input('month', now()->month);
        $selectedMonth = is_numeric($selectedMonth) && $selectedMonth >= 1 && $selectedMonth <= 12
            ? (int)$selectedMonth
            : now()->month;

        // Ambil data Barang Masuk dan Barang Keluar berdasarkan filter tahun dan bulan
        $barangMasukMonthly = BarangMasuk::with('dataBarang')
            ->whereYear('tanggal', $selectedYear)
            ->whereMonth('tanggal', $selectedMonth)
            ->orderBy('tanggal', 'desc')
            ->get();

        $barangKeluarMonthly = BarangKeluar::with('dataBarang')
            ->whereYear('tanggal', $selectedYear)
            ->whereMonth('tanggal', $selectedMonth)
            ->orderBy('tanggal', 'desc')
            ->get();

        // Hitung total Barang Masuk dan Barang Keluar
        $barangMasukCount = $barangMasukMonthly->count();
        $barangKeluarCount = $barangKeluarMonthly->count();

        // Kirim data ke view
        return view('index', compact(
            'userCount',
            'dataBarangCount',
            'barangMasukCount',
            'barangKeluarCount',
            'barangMasukMonthly',
            'barangKeluarMonthly',
            'selectedYear',
            'selectedMonth',
            'years'
        ));
    }
}
