<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesExportController extends Controller
{
    public function index(Request $request)
    {
        // Filter tanggal
        $from = $request->input('from');
        $to = $request->input('to');

        $query = Order::with('user');

        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        $sales = $query->orderByDesc('created_at')->get();

        return view('manager.export', compact('sales'));
    }

    // Ekspor CSV
    public function exportCsv(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');

        $query = Order::with('user');
        if ($from) $query->whereDate('created_at', '>=', $from);
        if ($to) $query->whereDate('created_at', '<=', $to);

        $sales = $query->orderByDesc('created_at')->get();

        $filename = 'penjualan_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($sales) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Tanggal', 'Nomor Pesanan', 'Pelanggan', 'Total', 'Status']);
            foreach ($sales as $sale) {
                fputcsv($handle, [
                    $sale->created_at->format('d-m-Y'),
                    $sale->id,
                    $sale->user->name ?? '-',
                    $sale->total_price,
                    $sale->status,
                ]);
            }
            fclose($handle);
        };

        return Response::stream($callback, 200, $headers);
    }

    // Ekspor PDF (contoh sederhana, gunakan package seperti barryvdh/laravel-dompdf untuk hasil maksimal)
    public function exportPdf(Request $request)
    {
        $from = $request->input('from');
        $to = $request->input('to');

        $query = Order::with('user');
        if ($from) $query->whereDate('created_at', '>=', $from);
        if ($to) $query->whereDate('created_at', '<=', $to);

        $sales = $query->orderByDesc('created_at')->get();


        $pdf = Pdf::loadView('manager.export_pdf', compact('sales'));
        return $pdf->download('penjualan_' . now()->format('Ymd_His') . '.pdf');
    }
}