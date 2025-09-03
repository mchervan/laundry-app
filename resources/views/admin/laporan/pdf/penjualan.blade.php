<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 12px; 
            margin: 20px; 
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            border-bottom: 2px solid #000; 
            padding-bottom: 10px; 
        }
        .header h1 { 
            margin: 0; 
            font-size: 24px; 
            color: #000; 
        }
        .header p { 
            margin: 5px 0; 
            color: #666; 
        }
        .company-info { 
            margin-bottom: 20px; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0; 
        }
        table, th, td { 
            border: 1px solid #000; 
        }
        th { 
            background-color: #000; 
            color: white; 
            padding: 12px; 
            text-align: left; 
            font-weight: bold; 
        }
        td { 
            padding: 10px; 
        }
        .text-right { 
            text-align: right; 
        }
        .text-center { 
            text-align: center; 
        }
        .total-row { 
            font-weight: bold; 
            background-color: #f8f9fa; 
        }
        .footer { 
            margin-top: 50px; 
            border-top: 1px solid #000; 
            padding-top: 20px; 
        }
        .signature { 
            float: right; 
            text-align: center; 
            width: 200px; 
        }
        .print-date { 
            margin-top: 30px; 
            font-style: italic; 
            color: #666; 
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAUNDRY EXPRESS</h1>
        <p>Jln. Contoh No. 123, Kota Contoh</p>
        <p>Telp: (021) 123-4567 | Email: info@laundryexpress.com</p>
    </div>

    <div class="company-info">
        <h2 style="text-align: center; margin: 0; color: #000;">LAPORAN PENJUALAN</h2>
        <p style="text-align: center; margin: 5px 0;">Periode: {{ $start }} s/d {{ $end }}</p>
        @if(isset($cabang) && $cabang != 'all')
        <p style="text-align: center; margin: 5px 0;">Cabang: {{ $cabang }}</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Kode Transaksi</th>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 20%;">Pelanggan</th>
                <th style="width: 15%;">Cabang</th>
                <th style="width: 15%;" class="text-right">Total Harga</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksis as $index => $transaksi)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $transaksi->kode_transaksi }}</td>
                <td>{{ $transaksi->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $transaksi->pelanggan->nama }}</td>
                <td>{{ $transaksi->branch }}</td>
                <td class="text-right">Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                <td class="text-center"><span style="background-color: #28a745; color: white; padding: 3px 8px; border-radius: 3px; font-size: 10px;">LUNAS</span></td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" class="text-right"><strong>TOTAL</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($total, 0, ',', '.') }}</strong></td>
                <td></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div class="print-date">
            Dicetak pada: {{ now()->format('d/m/Y H:i') }}
        </div>
        <div class="signature">
            <div style="margin-bottom: 60px;"></div>
            <div style="border-top: 1px solid #000; width: 200px; padding-top: 10px;">
                <strong>Manager</strong>
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>
</html>