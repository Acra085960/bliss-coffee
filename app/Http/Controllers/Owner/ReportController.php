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

        $query = Order::with(['user', 'orderItems.menu']);
        
        if ($start) {
            $query->whereDate('created_at', '>=', $start);
        }
        
        if ($end) {
            $query->whereDate('created_at', '<=', $end);
        }
        
        $orders = $query->orderByDesc('created_at')->get();

        $totalRevenue = $orders->sum('total_price');
        $totalOrders = $orders->count();

        return view('owner.reports', compact('orders', 'totalRevenue', 'totalOrders', 'start', 'end'));
    }
    public function export($type)
    {
        $monthlyReports = [];
        
        foreach (range(1, 12) as $month) {
            // Get top menu for this month
            $topMenu = \DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('menus', 'order_items.menu_id', '=', 'menus.id')
                ->whereYear('orders.created_at', now()->year)
                ->whereMonth('orders.created_at', $month)
                ->select('menus.name', \DB::raw('SUM(order_items.quantity) as total_quantity'))
                ->groupBy('menus.id', 'menus.name')
                ->orderBy('total_quantity', 'desc')
                ->first();
            
            $monthlyReports[] = [
                'month' => now()->startOfYear()->addMonths($month - 1)->format('F'),
                'orders' => Order::whereYear('created_at', now()->year)
                    ->whereMonth('created_at', $month)
                    ->count(),
                'revenue' => Order::whereYear('created_at', now()->year)
                    ->whereMonth('created_at', $month)
                    ->sum('total_price'),
                'top_menu' => $topMenu ? $topMenu->name . ' (' . $topMenu->total_quantity . ')' : '-',
            ];
        }

        if ($type === 'csv') {
            $filename = 'laporan_penjualan_' . now()->format('Y-m-d') . '.csv';
            $handle = fopen('php://temp', 'r+');
            
            // Add BOM for proper UTF-8 encoding in Excel
            fwrite($handle, "\xEF\xBB\xBF");
            
            fputcsv($handle, ['Bulan', 'Jumlah Order', 'Total Pendapatan', 'Menu Terlaris']);
            
            foreach ($monthlyReports as $report) {
                fputcsv($handle, [
                    $report['month'],
                    $report['orders'],
                    'Rp ' . number_format($report['revenue'], 0, ',', '.'),
                    $report['top_menu']
                ]);
            }
            
            rewind($handle);
            $csv = stream_get_contents($handle);
            fclose($handle);

            return response($csv)
                ->header('Content-Type', 'text/csv; charset=UTF-8')
                ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
        }

        if ($type === 'pdf') {
            try {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('owner.reports_pdf', compact('monthlyReports'));
                $pdf->setPaper('A4', 'portrait');
                
                $filename = 'laporan_penjualan_' . now()->format('Y-m-d') . '.pdf';
                return $pdf->download($filename);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal mengexport PDF: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('error', 'Format export tidak valid');
    }
}