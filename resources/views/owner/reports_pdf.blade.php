<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan Bulanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #333;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            margin: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .summary {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BLISS COFFEE</h1>
        <p>Laporan Penjualan Bulanan</p>
        <p>Periode: {{ now()->year }}</p>
        <p>Dicetak pada: {{ now()->format('d F Y H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Bulan</th>
                <th>Jumlah Pesanan</th>
                <th>Total Pendapatan</th>
                <th>Menu Terlaris</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalOrders = 0;
                $totalRevenue = 0;
            @endphp
            @foreach($monthlyReports as $index => $report)
            @php
                $totalOrders += $report['orders'];
                $totalRevenue += $report['revenue'];
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $report['month'] }}</td>
                <td class="text-right">{{ number_format($report['orders']) }}</td>
                <td class="text-right">Rp {{ number_format($report['revenue'], 0, ',', '.') }}</td>
                <td>{{ $report['top_menu'] }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f0f0f0; font-weight: bold;">
                <td colspan="2">TOTAL</td>
                <td class="text-right">{{ number_format($totalOrders) }}</td>
                <td class="text-right">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</td>
                <td>-</td>
            </tr>
        </tfoot>
    </table>

    <div class="summary">
        <h3>Ringkasan Laporan</h3>
        <p><strong>Total Pesanan:</strong> {{ number_format($totalOrders) }} pesanan</p>
        <p><strong>Total Pendapatan:</strong> Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
        <p><strong>Rata-rata Pesanan per Bulan:</strong> {{ number_format($totalOrders / 12, 1) }} pesanan</p>
        <p><strong>Rata-rata Pendapatan per Bulan:</strong> Rp {{ number_format($totalRevenue / 12, 0, ',', '.') }}</p>
    </div>

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem Bliss Coffee</p>
        <p>© {{ now()->year }} Bliss Coffee. All rights reserved.</p>
    </div>
</body>
</html>