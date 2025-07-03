<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdminController extends Controller
{
    public function index()
    {
        $agendaCount = Agenda::count();
        $userCount = User::count();

        // Statistik agenda per bulan (tahun berjalan)
        $data = Agenda::selectRaw('MONTH(tanggal) as month, COUNT(*) as total')
            ->whereYear('tanggal', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $bulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        $chartLabels = [];
        $chartData = [];

        foreach ($bulan as $key => $nama) {
            $found = $data->firstWhere('month', $key);
            $chartLabels[] = $nama;
            $chartData[] = $found ? $found->total : 0;
        }

        return view('admin.index', compact('agendaCount', 'userCount', 'chartLabels', 'chartData'));
    }

    public function laporan(Request $request)
    {
        $kelasName = $request->input('kelas');
        $bulan     = $request->input('bulan');
        $tahun     = $request->input('tahun', date('Y'));

        $query = Agenda::with([
            'kelas',
            'user',
            'details.guru',
            'details.mataPelajaran'
        ])->whereHas('user', function ($q) {
            $q->where('role', 'kalas'); // Only agendas from kalas users
        });

        // Apply filters
        if ($kelasName) {
            // IMPORTANT: Changed to 'nama_kelas'
            $query->whereHas('kelas', function ($q) use ($kelasName) {
                $q->where('nama_kelas', $kelasName);
            });
        }

        if ($bulan) {
            $query->whereMonth('tanggal', $bulan);
        }

        if ($tahun) {
            $query->whereYear('tanggal', $tahun);
        }

        $agendas = $query->orderBy('tanggal', 'desc')->get();

        // Get all unique class names for the filter dropdown.
        // IMPORTANT: Changed to 'nama_kelas'
        $kelasList = Kelas::pluck('nama_kelas')->unique()->sort()->values()->toArray();

        return view('admin.laporan', compact('agendas', 'kelasList'));
    }

    /**
     * Export the daily agenda report to Excel.
     */
    public function laporanExcel(Request $request)
    {
        $kelasName = $request->input('kelas');
        $bulan     = $request->input('bulan');
        $tahun     = $request->input('tahun', date('Y'));

        $query = Agenda::with([
            'kelas',
            'user',
            'details.guru',
            'details.mataPelajaran'
        ])->whereHas('user', function ($q) {
            $q->where('role', 'kalas');
        });

        // Apply filters (same logic as 'laporan' method to ensure consistency)
        if ($kelasName) {
            // IMPORTANT: Changed to 'nama_kelas'
            $query->whereHas('kelas', function ($q) use ($kelasName) {
                $q->where('nama_kelas', $kelasName);
            });
        }

        if ($bulan) {
            $query->whereMonth('tanggal', $bulan);
        }

        if ($tahun) {
            $query->whereYear('tanggal', $tahun);
        }

        $agendas = $query->orderBy('tanggal', 'desc')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // --- Header Columns ---
        // Main Agenda Headers
        $headerColumns = [
            'No', 'Tanggal', 'User (Kalas)', 'Kelas', 'Jumlah Total Siswa',
            'Siswa Hadir', 'Siswa Izin (Jumlah)', 'Siswa Izin (Nama)',
            'Siswa Sakit (Jumlah)', 'Siswa Sakit (Nama)',
            'Siswa Alpa (Jumlah)', 'Siswa Alpa (Nama)',
            'Jam Mulai', 'Jam Selesai', 'Mata Pelajaran', 'Guru Pengampu',
            'Status Guru', 'Keterangan Detail', 'Link Foto Kegiatan'
        ];
        $sheet->fromArray($headerColumns, null, 'A1');

        $row = 2;
        foreach ($agendas as $index => $agenda) {
            // Ensure there's at least one detail row to print main agenda data
            $details = $agenda->details->isNotEmpty() ? $agenda->details : collect([null]);

            foreach ($details as $detailIndex => $detail) {
                $rowData = [];

                // Only write main agenda data on the first detail row for this agenda
                if ($detailIndex === 0) {
                    $rowData[] = $index + 1;
                    $rowData[] = Carbon::parse($agenda->tanggal)->format('d M Y');
                    $rowData[] = $agenda->user->name ?? '-';
                    // Using nama_kelas here as well for display
                    $rowData[] = ($agenda->kelas->nama_kelas ?? '-') . ' ' . ($agenda->kelas->jurusan ?? '-') . ' ' . ($agenda->kelas->tingkat ?? '-');
                    $rowData[] = $agenda->kelas->jumlah_siswa ?? '-';
                    $rowData[] = $agenda->jumlah_siswa ?? '0';
                    $rowData[] = $agenda->izin ? count(explode(',', $agenda->izin)) : 0;
                    $rowData[] = $agenda->izin ?: '-';
                    $rowData[] = $agenda->sakit ? count(explode(',', $agenda->sakit)) : 0;
                    $rowData[] = $agenda->sakit ?: '-';
                    $rowData[] = $agenda->alpa ? count(explode(',', $agenda->alpa)) : 0;
                    $rowData[] = $agenda->alpa ?: '-';
                } else {
                    // For subsequent detail rows, leave main agenda columns blank for visual merging
                    $rowData = array_pad([], 12, ''); // 12 empty cells for main agenda columns
                }

                // Add Agenda Detail data
                if ($detail) {
                    $rowData[] = Carbon::parse($detail->jam_mulai)->format('H:i');
                    $rowData[] = Carbon::parse($detail->jam_selesai)->format('H:i');
                    $rowData[] = $detail->mataPelajaran->nama ?? '-';
                    $rowData[] = $detail->guru->nama ?? '-';
                    $rowData[] = ucfirst(str_replace('_', ' ', $detail->status_guru)) ?? '-';
                    $rowData[] = $detail->keterangan ?? '-';
                    $rowData[] = $detail->foto_kegiatan ? asset('storage/' . $detail->foto_kegiatan) : '-';
                } else {
                    // No details for this agenda, fill detail columns with dashes
                    $rowData = array_merge($rowData, array_pad([], 7, '-')); // 7 dashes for detail columns
                }

                $sheet->fromArray($rowData, null, 'A' . $row);
                $row++; // Move to the next row for the next detail or next agenda
            }
        }

        // Set auto-width for columns
        foreach (range('A', $sheet->getHighestDataColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan_agenda_harian_' . Carbon::now()->format('Y_m_d_H_i_s') . '.xlsx';

        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment;filename="' . $filename . '"',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
