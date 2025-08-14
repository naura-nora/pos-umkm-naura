<?php

namespace App\Http\Controllers;

use App\Models\FinancialReport;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class FinancialReportController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin')->except(['index']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
{
    try {
        // Ambil laporan manual
        $reports = FinancialReport::when(
            \Schema::hasColumn('financial_reports', 'source'),
            function ($query) {
                return $query->where('source', 'manual');
            },
            function ($query) {
                return $query->whereNull('transaksi_id');
            }
        )->latest()->paginate(10);

        // Ambil laporan dari transaksi (untuk summary)
        $transactionIncome = Transaksi::where('status', 'Lunas')
            ->selectRaw('DATE(tanggal) as report_date, SUM(total) as amount')
            ->groupBy('report_date')
            ->get();
            
        $totalIncome = FinancialReport::where('type', 'income')->sum('amount');
        $totalExpense = FinancialReport::where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        return view('financial_reports.index', compact(
            'reports', // Ubah dari $manualReports menjadi $reports
            'transactionIncome',
            'totalIncome',
            'totalExpense',
            'balance'
        ));
    } catch (\Exception $e) {
        // Fallback jika ada error
        $reports = FinancialReport::latest()->paginate(10);
        $transactionIncome = collect();
        $totalIncome = FinancialReport::where('type', 'income')->sum('amount');
        $totalExpense = FinancialReport::where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        return view('financial_reports.index', compact(
            'reports',
            'transactionIncome',
            'totalIncome',
            'totalExpense',
            'balance'
        ));
    }

}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('financial_reports.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'report_date' => 'required|date',
            'description' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0'
        ]);

        FinancialReport::create([
            'report_date' => $request->report_date,
            'description' => $request->description,
            'type' => $request->type,
            'amount' => $request->amount,
            'user_id' => auth()->id()
        ]);

        return redirect()->route('financial-reports.index')
            ->with('success', 'Laporan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FinancialReport $financialReport)
    {
        return view('financial_reports.edit', compact('financialReport'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FinancialReport $financialReport)
    {
        $request->validate([
            'report_date' => 'required|date',
            'description' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0'
        ]);

        $financialReport->update($request->all());

        return redirect()->route('financial-reports.index')
            ->with('success', 'Laporan berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinancialReport $financialReport)
    {
        $financialReport->delete();

        return redirect()->route('financial-reports.index')
            ->with('success', 'Laporan berhasil dihapus');
    }
}
