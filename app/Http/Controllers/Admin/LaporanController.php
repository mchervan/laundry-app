<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\PaketLaundry;
use App\Models\TransaksiDetail;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;

class LaporanController extends Controller
{
    public function penjualan(Request $request)
    {
        $start = $request->input('start_date', now()->subDays(7)->format('Y-m-d'));
        $end = $request->input('end_date', now()->format('Y-m-d'));
        $cabang = $request->input('cabang', 'all');
        
        $endDateTime = $end . ' 23:59:59';
        
        $query = Transaksi::with('pelanggan')
            ->whereBetween('created_at', [$start, $endDateTime])
            ->where('status_pembayaran', 'lunas')
            ->byCabang($cabang);
            
        $transaksis = $query->orderBy('created_at', 'desc')->get();
        $total = $transaksis->sum('total_harga');
        
        $branches = Transaksi::select('branch')->distinct()->pluck('branch');
        
        return view('admin.laporan.penjualan', compact('transaksis', 'start', 'end', 'total', 'branches', 'cabang'));
    }
    
    public function exportPenjualan($format, Request $request)
    {
        $start = $request->input('start_date', now()->subDays(7)->format('Y-m-d'));
        $end = $request->input('end_date', now()->format('Y-m-d'));
        $cabang = $request->input('cabang', 'all');
        
        $endDateTime = $end . ' 23:59:59';
        
        $query = Transaksi::with('pelanggan')
            ->whereBetween('created_at', [$start, $endDateTime])
            ->where('status_pembayaran', 'lunas')
            ->byCabang($cabang);
            
        $transaksis = $query->orderBy('created_at', 'desc')->get();
        $total = $transaksis->sum('total_harga');
        
        $filename = 'Laporan_Penjualan_' . $start . '_sd_' . $end;
        if ($cabang !== 'all') {
            $filename .= '_' . strtolower(str_replace(' ', '_', $cabang));
        }
        
        if ($format === 'excel') {
            $filename .= '.xlsx';
            return $this->exportExcel($transaksis, $start, $end, $cabang, $total, $filename);
        } elseif ($format === 'pdf') {
            $filename .= '.pdf';
            return $this->exportPDF($transaksis, $start, $end, $cabang, $total, $filename);
        }
        
        return redirect()->back()->with('error', 'Format export tidak valid');
    }
    
    private function exportExcel($transaksis, $start, $end, $cabang, $total, $filename)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set header
        $sheet->setCellValue('A1', 'LAPORAN PENJUALAN');
        $sheet->setCellValue('A2', 'Laundry Express');
        $sheet->setCellValue('A3', 'Periode: ' . $start . ' s/d ' . $end);
        
        $rowOffset = 3;
        if ($cabang !== 'all') {
            $sheet->setCellValue('A4', 'Cabang: ' . $cabang);
            $rowOffset = 4;
        }
        
        // Set table header
        $headerRow = $rowOffset + 1;
        $sheet->setCellValue('A' . $headerRow, 'No');
        $sheet->setCellValue('B' . $headerRow, 'Kode Transaksi');
        $sheet->setCellValue('C' . $headerRow, 'Tanggal');
        $sheet->setCellValue('D' . $headerRow, 'Pelanggan');
        $sheet->setCellValue('E' . $headerRow, 'Cabang');
        $sheet->setCellValue('F' . $headerRow, 'Total Harga');
        $sheet->setCellValue('G' . $headerRow, 'Status');
        
        // Style header
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '000000']],
            'font' => ['color' => ['rgb' => 'FFFFFF']]
        ];
        
        $sheet->getStyle('A' . $headerRow . ':G' . $headerRow)->applyFromArray($headerStyle);
        
        // Fill data
        $row = $headerRow + 1;
        foreach ($transaksis as $index => $transaksi) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $transaksi->kode_transaksi);
            $sheet->setCellValue('C' . $row, $transaksi->created_at->format('d/m/Y H:i'));
            $sheet->setCellValue('D' . $row, $transaksi->pelanggan->nama);
            $sheet->setCellValue('E' . $row, $transaksi->branch);
            $sheet->setCellValue('F' . $row, $transaksi->total_harga);
            $sheet->setCellValue('G' . $row, 'Lunas');
            
            // Format currency
            $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('"Rp" #,##0');
            
            $row++;
        }
        
        // Total row
        $sheet->setCellValue('A' . $row, 'TOTAL');
        $sheet->mergeCells('A' . $row . ':E' . $row);
        $sheet->setCellValue('F' . $row, $total);
        $sheet->setCellValue('G' . $row, '');
        
        // Style total row
        $totalStyle = [
            'font' => ['bold' => true],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FFFF00']]
        ];
        
        $sheet->getStyle('A' . $row . ':G' . $row)->applyFromArray($totalStyle);
        $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('"Rp" #,##0');
        
        // Footer
        $sheet->setCellValue('A' . ($row + 2), 'Dicetak pada: ' . now()->format('d/m/Y H:i'));
        
        // Auto size columns
        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Create writer and save to temporary file
        $writer = new Xlsx($spreadsheet);
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp_file);
        
        // Return file as download
        return response()->download($temp_file, $filename)->deleteFileAfterSend(true);
    }
    
    private function exportPDF($transaksis, $start, $end, $cabang, $total, $filename)
    {
        // Render HTML view
        $html = view('admin.laporan.pdf.penjualan', 
            compact('transaksis', 'start', 'end', 'total', 'cabang')
        )->render();
        
        // Create DomPDF instance
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        
        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');
        
        // Render PDF
        $dompdf->render();
        
        // Return PDF as download
        return $dompdf->stream($filename);
    }
    
    public function piutang(Request $request)
    {
        $cabang = $request->input('cabang', 'all');
        
        $query = Transaksi::with('pelanggan')
            ->where('status_pembayaran', 'belum lunas')
            ->byCabang($cabang);
            
        $transaksis = $query->orderBy('created_at', 'desc')->get();
        
        $branches = Transaksi::select('branch')
            ->whereNotNull('branch')
            ->distinct()
            ->pluck('branch');
            
        return view('admin.laporan.piutang', compact('transaksis', 'branches', 'cabang'));
    }

    public function performa(Request $request)
    {
        $start = $request->input('start_date', now()->subMonth()->format('Y-m-d'));
        $end = $request->input('end_date', now()->format('Y-m-d'));
        $cabang = $request->input('cabang', 'all');
        
        $query = PaketLaundry::withCount(['transaksiDetails as jumlah_transaksi' => function($query) use ($start, $end, $cabang) {
                $query->whereHas('transaksi', function($q) use ($start, $end, $cabang) {
                    $q->whereBetween('created_at', [$start, $end])
                      ->where('status_pembayaran', 'lunas')
                      ->byCabang($cabang);
                });
            }])
            ->withSum(['transaksiDetails as total_pendapatan' => function($query) use ($start, $end, $cabang) {
                $query->whereHas('transaksi', function($q) use ($start, $end, $cabang) {
                    $q->whereBetween('created_at', [$start, $end])
                      ->where('status_pembayaran', 'lunas')
                      ->byCabang($cabang);
                });
            }], 'subtotal')
            ->orderBy('total_pendapatan', 'desc');
            
        $pakets = $query->get();
        
        $branches = Transaksi::select('branch')->distinct()->pluck('branch');
            
        return view('admin.laporan.performa', compact('pakets', 'start', 'end', 'branches', 'cabang'));
    }

    public function pengeluaran(Request $request)
    {
        $start = $request->input('start_date', now()->subDays(7)->format('Y-m-d'));
        $end = $request->input('end_date', now()->format('Y-m-d'));
        $cabang = $request->input('cabang', 'all');
        
        $query = Pengeluaran::with('user')
            ->whereBetween('tanggal', [$start, $end]);
            
        if ($cabang !== 'all') {
            $query->whereHas('user', function($q) use ($cabang) {
                $q->where('branch', $cabang);
            });
        }
            
        $pengeluarans = $query->orderBy('tanggal', 'desc')->get();
        $total = $pengeluarans->sum('jumlah');
        
        $kategoriSummary = Pengeluaran::whereBetween('tanggal', [$start, $end]);
        
        if ($cabang !== 'all') {
            $kategoriSummary->whereHas('user', function($q) use ($cabang) {
                $q->where('branch', $cabang);
            });
        }
        
        $kategoriSummary = $kategoriSummary->select('kategori', \DB::raw('SUM(jumlah) as total'))
            ->groupBy('kategori')
            ->get();
        
        $branches = \App\Models\User::select('branch')->distinct()->whereNotNull('branch')->pluck('branch');
        
        return view('admin.laporan.pengeluaran', compact('pengeluarans', 'start', 'end', 'total', 'kategoriSummary', 'branches', 'cabang'));
    }
}