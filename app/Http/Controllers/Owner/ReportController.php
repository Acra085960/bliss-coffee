<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Filter tanggal (opsional)
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        $orders = Order::when($start, fn($q) => $q->whereDate('created_at', '>=', $start))
            ->when($end, fn($q) => $q->whereDate('created_at', '<=', $end))
            ->orderByDesc('created_at')
            ->get();

        $totalRevenue = $orders->sum('total_price');
        $totalOrders = $orders->count();

        return view('owner.reports', compact('orders', 'totalRevenue', 'totalOrders', 'start', 'end'));
    }
    public function export($type)
{
   $monthlyReports = [];
foreach (range(1, 12) as $month) {
    $monthlyReports[] = [
        'month' => now()->startOfYear()->addMonths($month - 1)->format('F'),
        'orders' => Order::whereYear('created_at', now()->year)
            ->whereMonth('created_at', $month)
            ->count(),
        'revenue' => Order::whereYear('created_at', now()->year)
            ->whereMonth('created_at', $month)
            ->sum('total_price'),
        'top_menu' => '-', // Jika ingin, bisa tambahkan query menu terlaris per bulan
    ];
}

    if ($type === 'csv') {
        $filename = 'laporan_penjualan.csv';
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['Bulan', 'Jumlah Order', 'Total Pendapatan', 'Menu Terlaris']);
        foreach ($monthlyReports as $report) {
            fputcsv($handle, [
                $report['month'],
                $report['orders'],
                $report['revenue'],
                $report['top_menu']
            ]);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }

    if ($type === 'pdf') {
        // Pastikan sudah install barryvdh/laravel-dompdf
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('owner.reports_pdf', compact('monthlyReports'));
        return $pdf->download('laporan_penjualan.pdf');
    }

    abort(404);
}
}