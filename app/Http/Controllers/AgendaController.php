<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Kelas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AgendaController extends Controller
{
    /**
     * Menampilkan halaman form untuk membuat agenda baru.
     */
    public function create()
    {
        // Mengambil semua kelas yang tersedia
        $kelasList = Kelas::all();
        return view('agenda.create', compact('kelasList'));
    }

    /**
     * Menyimpan agenda yang baru dibuat.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $data = $request->validate([
            'tanggal' => 'required|date',
            'izin' => 'nullable|string',
            'sakit' => 'nullable|string',
            'alpa' => 'nullable|string',
        ]);

        // Ambil kelas_id dari user yang login
        $kelas_id = auth()->user()->kelas_id;

        // Periksa jika kelas_id kosong atau null
        if (!$kelas_id) {
            return redirect()->back()->with('error', 'Kelas belum diatur untuk user ini.');
        }

        // Mendapatkan kelas berdasarkan kelas_id yang dimiliki oleh user
        $kelas = Kelas::findOrFail($kelas_id);

        // Mengonversi string yang dipisah koma menjadi array, lalu membersihkan spasi
        $izin = collect(explode(',', $data['izin'] ?? ''))->map('trim')->filter();
        $sakit = collect(explode(',', $data['sakit'] ?? ''))->map('trim')->filter();
        $alpa = collect(explode(',', $data['alpa'] ?? ''))->map('trim')->filter();

        // Menghitung jumlah siswa yang hadir
        $jumlah_hadir = max(0, $kelas->jumlah_siswa - ($izin->count() + $sakit->count() + $alpa->count()));

        // Membuat entri agenda baru
        Agenda::create([
            'tanggal' => $data['tanggal'],
            'kelas_id' => $kelas_id, // Menggunakan kelas_id dari user yang login
            'jumlah_siswa' => $jumlah_hadir,
            'izin' => $izin->implode(', '),
            'sakit' => $sakit->implode(', '),
            'alpa' => $alpa->implode(', '),
            'created_by' => auth()->id(), // Menyimpan ID user yang membuat agenda
        ]);

        // Mengarahkan kembali dengan pesan sukses
        return redirect()->route('agenda.index')->with('success', 'Agenda tersimpan.');
    }


   public function laporan(Request $request)
    {
        // Ambil input tanggal mulai dan selesai dari request
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        // Ambil kelas dari user yang login
        $kelas = auth()->user()->kelas;

        // Query untuk mengambil agenda berdasarkan filter tanggal
        $agendas = Agenda::with('kelas', 'user')
            ->where('created_by', auth()->id()); // Pastikan hanya agenda yang dibuat oleh user yang ditampilkan

        // Filter berdasarkan tanggal mulai
        if ($tanggalMulai) {
            $agendas->whereDate('tanggal', '>=', Carbon::parse($tanggalMulai));
        }

        // Filter berdasarkan tanggal selesai
        if ($tanggalSelesai) {
            $agendas->whereDate('tanggal', '<=', Carbon::parse($tanggalSelesai));
        }

        // Ambil data agenda
        $agendas = $agendas->get();

        // Kirim data agenda ke view
        return view('agenda.laporan', compact('agendas', 'kelas'));
    }





    /**
     * Menampilkan agenda hari ini untuk user yang sedang login.
     */
   public function index()
    {
        // Ambil data kelas yang terkait dengan user yang sedang login
        $kelas = auth()->user()->kelas;

        // Ambil agenda berdasarkan kelas yang dimiliki user dan tanggal hari ini
        $agenda = Agenda::with('kelas', 'user', 'details')
            ->whereDate('tanggal', Carbon::today())  // Mengambil agenda untuk hari ini
            ->where('kelas_id', $kelas->id)          // Mengambil agenda berdasarkan kelas yang dimiliki user
            ->where('created_by', auth()->id())      // Mengambil agenda yang dibuat oleh user yang sedang login
            ->first();

        // Kirim data agenda dan kelas ke view
        return view('agenda.index', compact('agenda', 'kelas'));
    }


    public function laporanExcel(Request $request)
    {
        // Ambil input filter tanggal dari request
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');

        // Query agenda yang dibuat user yang login
        $agendas = Agenda::with('kelas', 'user')
            ->where('created_by', auth()->id());

        // Filter berdasarkan tanggal mulai jika ada
        if ($tanggalMulai) {
            $agendas->whereDate('tanggal', '>=', Carbon::parse($tanggalMulai));
        }

        // Filter berdasarkan tanggal selesai jika ada
        if ($tanggalSelesai) {
            $agendas->whereDate('tanggal', '<=', Carbon::parse($tanggalSelesai));
        }

        // Ambil data yang sudah difilter
        $agendas = $agendas->get();

        // Buat spreadsheet sama seperti sebelumnya
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header kolom
        $sheet->setCellValue('A1', 'Tanggal');
        $sheet->setCellValue('B1', 'Kelas');
        $sheet->setCellValue('C1', 'Jumlah Siswa');
        $sheet->setCellValue('D1', 'Jumlah Hadir');
        $sheet->setCellValue('E1', 'Izin');
        $sheet->setCellValue('F1', 'Sakit');
        $sheet->setCellValue('G1', 'Alpa');

        // Isi data agenda
        $row = 2;
        foreach ($agendas as $agenda) {
            $sheet->setCellValue('A' . $row, Carbon::parse($agenda->tanggal)->toDateString());
            $sheet->setCellValue('B' . $row, $agenda->kelas->nama_kelas ?? '-');
            $sheet->setCellValue('C' . $row, $agenda->kelas->jumlah_siswa ?? '-');
            $sheet->setCellValue('D' . $row, $agenda->jumlah_siswa);
            $sheet->setCellValue('E' . $row, $agenda->izin ?: 'Tidak Ada');
            $sheet->setCellValue('F' . $row, $agenda->sakit ?: 'Tidak Ada');
            $sheet->setCellValue('G' . $row, $agenda->alpa ?: 'Tidak Ada');
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan_agenda_' . Carbon::now()->format('Y_m_d_H_i_s') . '.xlsx';

        return response()->stream(function() use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => "attachment; filename={$filename}",
        ]);
    }

}
