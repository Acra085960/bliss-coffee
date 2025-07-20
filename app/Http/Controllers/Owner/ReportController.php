<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Filter tanggal, outlet, dan periode
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $outletId = $request->input('outlet_id');
        $period = $request->input('period');

        $query = Order::with(['user', 'orderItems.menu', 'outlet']);
        
        // Handle period filter
        if ($period) {
            switch ($period) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'this_week':
                    $query->whereBetween('created_at', [
                        now()->startOfWeek(), 
                        now()->endOfWeek()
                    ]);
                    break;
                case 'this_month':
                    $query->whereYear('created_at', now()->year)
                          ->whereMonth('created_at', now()->month);
                    break;
                case 'this_year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        } else {
            // Handle custom date range if no period selected
            if ($start) {
                $query->whereDate('created_at', '>=', $start);
            }
            
            if ($end) {
                $query->whereDate('created_at', '<=', $end);
            }
        }
        
        if ($outletId) {
            $query->where('outlet_id', $outletId);
        }
        
        $orders = $query->orderByDesc('created_at')->get();

        $totalRevenue = $orders->sum('total_price');
        $totalOrders = $orders->count();

        // Ambil semua outlet untuk dropdown filter
        $outlets = \App\Models\Outlet::all();

        return view('owner.reports', compact('orders', 'totalRevenue', 'totalOrders', 'start', 'end', 'outletId', 'outlets', 'period'));
    }
    public function export($type, Request $request)
    {
        // Ambil filter dari request
        $start = $request->input('start_date');
        $end = $request->input('end_date');
        $outletId = $request->input('outlet_id');
        $period = $request->input('period');
        
        $monthlyReports = [];
        
        foreach (range(1, 12) as $month) {
            // Get top menu for this month with filter
            $topMenuQuery = \DB::table('order_items')
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('menus', 'order_items.menu_id', '=', 'menus.id')
                ->whereYear('orders.created_at', now()->year)
                ->whereMonth('orders.created_at', $month);
            
            // Apply period filter
            if ($period) {
                switch ($period) {
                    case 'today':
                        $topMenuQuery->whereDate('orders.created_at', today());
                        break;
                    case 'this_week':
                        $topMenuQuery->whereBetween('orders.created_at', [
                            now()->startOfWeek(), 
                            now()->endOfWeek()
                        ]);
                        break;
                    case 'this_month':
                        $topMenuQuery->whereYear('orders.created_at', now()->year)
                                    ->whereMonth('orders.created_at', now()->month);
                        break;
                    case 'this_year':
                        $topMenuQuery->whereYear('orders.created_at', now()->year);
                        break;
                }
            } else {
                // Apply custom date range
                if ($start) {
                    $topMenuQuery->whereDate('orders.created_at', '>=', $start);
                }
                if ($end) {
                    $topMenuQuery->whereDate('orders.created_at', '<=', $end);
                }
            }
            
            if ($outletId) {
                $topMenuQuery->where('orders.outlet_id', $outletId);
            }
            
            $topMenu = $topMenuQuery->select('menus.name', \DB::raw('SUM(order_items.quantity) as total_quantity'))
                ->groupBy('menus.id', 'menus.name')
                ->orderBy('total_quantity', 'desc')
                ->first();
            
            // Get orders count and revenue with filter
            $ordersQuery = Order::whereYear('created_at', now()->year)
                ->whereMonth('created_at', $month);
            $revenueQuery = Order::whereYear('created_at', now()->year)
                ->whereMonth('created_at', $month);
                
            // Apply period filter to orders and revenue queries
            if ($period) {
                switch ($period) {
                    case 'today':
                        $ordersQuery->whereDate('created_at', today());
                        $revenueQuery->whereDate('created_at', today());
                        break;
                    case 'this_week':
                        $ordersQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                        $revenueQuery->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                        break;
                    case 'this_month':
                        $ordersQuery->whereYear('created_at', now()->year)->whereMonth('created_at', now()->month);
                        $revenueQuery->whereYear('created_at', now()->year)->whereMonth('created_at', now()->month);
                        break;
                    case 'this_year':
                        $ordersQuery->whereYear('created_at', now()->year);
                        $revenueQuery->whereYear('created_at', now()->year);
                        break;
                }
            } else {
                // Apply custom date range
                if ($start) {
                    $ordersQuery->whereDate('created_at', '>=', $start);
                    $revenueQuery->whereDate('created_at', '>=', $start);
                }
                if ($end) {
                    $ordersQuery->whereDate('created_at', '<=', $end);
                    $revenueQuery->whereDate('created_at', '<=', $end);
                }
            }
            
            if ($outletId) {
                $ordersQuery->where('outlet_id', $outletId);
                $revenueQuery->where('outlet_id', $outletId);
            }
            
            $monthlyReports[] = [
                'month' => now()->startOfYear()->addMonths($month - 1)->format('F'),
                'orders' => $ordersQuery->count(),
                'revenue' => $revenueQuery->sum('total_price'),
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