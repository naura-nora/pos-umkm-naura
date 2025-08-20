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
    // Hitung total dan saldo
    $incomeFromTransactions = Transaksi::where('status', 'Lunas')->sum('total');
    $manualIncome = FinancialReport::where('type', 'income')->where('source', 'manual')->sum('amount');
    $totalExpense = FinancialReport::where('type', 'expense')->sum('amount');
    $totalIncome = $incomeFromTransactions + $manualIncome;
    $balance = $totalIncome - $totalExpense;

    // Data pemasukan manual
    $manualIncomes = FinancialReport::where('type', 'income')
        ->where('source', 'manual')
        ->orderBy('report_date', 'desc')
        ->paginate(5, ['*'], 'manual_income_page');

    // Data pemasukan dari transaksi (dikelompokkan per hari)
    $transactionIncomes = Transaksi::where('status', 'Lunas')
        ->selectRaw('DATE(tanggal) as report_date, 
                    SUM(total) as amount,
                    SUM((SELECT SUM(qty) FROM detail_transaksi WHERE transaksi_id = transaksi.id)) as total_items,
                    GROUP_CONCAT(DISTINCT users.name SEPARATOR ", ") as cashiers')
        ->join('users', 'users.id', '=', 'transaksi.user_id')
        ->groupBy('report_date')
        ->orderBy('report_date', 'desc')
        ->paginate(5, ['*'], 'transaction_page');

    // Data pengeluaran
    $expenses = FinancialReport::where('type', 'expense')
        ->orderBy('report_date', 'desc')
        ->paginate(5, ['*'], 'expense_page');

    return view('financial_reports.index', compact(
        'manualIncomes',
        'transactionIncomes',
        'expenses',
        'totalIncome',
        'totalExpense',
        'balance',
        'incomeFromTransactions',
        'manualIncome'
    ));
}
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // $type = $request->get('type', 'income');
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
        'type'        => 'required|in:income,expense', // ✅ Validasi
        'amount'      => 'required|numeric|min:0',
    ]);

    FinancialReport::create([
        'report_date'   => $request->report_date,
        'description'   => $request->description,
        'type'          => $request->type,      // ✅ Ambil dari dropdown
        'amount'        => $request->amount,
        'responsible'   => auth()->user()->name,
        'user_id'       => auth()->id(),
    ]);

    return redirect()->route('financial-reports.index')->with('success', 'Data berhasil ditambahkan.');
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
            'amount' => 'required|numeric|min:0',
            'responsible' => $financialReport->type == 'expense' ? 'required|string|max:255' : 'nullable'
        ]);

        $data = $request->only(['report_date', 'description', 'amount']);
        if ($financialReport->type == 'expense') {
            $data['responsible'] = $request->responsible;
        }

        $financialReport->update($data);

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
