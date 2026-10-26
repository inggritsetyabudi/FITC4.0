import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { balitaService, pengukuranService } from '../services/api';
import { useAsyncData } from '../hooks/useAsyncData';

export const LaporanPage: React.FC = () => {
  const navigate = useNavigate();
  const [bulanInput, setBulanInput] = useState<string>('2026-08');

  const { data: balitaList = [] } = useAsyncData(() => balitaService.getAll());
  const { data: pengukuranList = [], reload } = useAsyncData(
    () => pengukuranService.getAll(bulanInput),
    [bulanInput]
  );

  const handleExportCSV = () => {
    const headers = ['ID', 'Nama', 'NIK', 'Tgl Lahir', 'JK', 'Nama Ortu', 'Dusun', 'Status Gizi', 'BB Terakhir (kg)', 'TB Terakhir (cm)'];
    const rows = balitaList.map((b) => [
      b.id,
      `"${b.nama}"`,
      `"${b.nik || '-'}"`,
      b.tanggal_lahir,
      b.jenis_kelamin,
      `"${b.nama_ortu}"`,
      `"${b.dusun}"`,
      b.status_terakhir || '-',
      b.berat_terakhir || '-',
      b.tinggi_terakhir || '-'
    ]);

    const csvContent = 'data:text/csv;charset=utf-8,' + [headers.join(','), ...rows.map((e) => e.join(','))].join('\n');
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', `laporan_balita_${bulanInput}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  };

  const handlePrint = () => {
    window.print();
  };

  return (
    <div className="report-page space-y-6 max-w-5xl">
      <div>
        <h1 className="text-2xl font-extrabold text-[#0D47A1]">Laporan Pertumbuhan Balita</h1>
        <p className="text-xs text-ink-500 mt-1">
          Unduh rekap pengukuran balita dalam format PDF atau CSV.
        </p>
      </div>

      <div className="bg-white border border-line rounded-2xl p-6 shadow-xs space-y-3">
        <label className="block text-xs font-bold text-ink-700">
          Bulan Pengukuran
        </label>
        <div className="flex flex-col sm:flex-row items-center gap-3">
          <input
            type="month"
            value={bulanInput}
            onChange={(e) => setBulanInput(e.target.value)}
            className="w-full sm:w-80 px-4 py-2.5 text-xs font-semibold bg-white border border-line rounded-xl focus:outline-none focus:border-[#2196F3]"
          />
          <button
            onClick={reload}
            className="w-full sm:w-auto px-6 py-2.5 bg-white hover:bg-slate-50 text-[#2196F3] text-xs font-bold rounded-xl border border-[#2196F3] transition-base"
          >
            Tampilkan
          </button>
        </div>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div className="bg-white border border-line rounded-2xl p-6 shadow-xs flex flex-col justify-between space-y-6">
          <div className="flex items-start gap-4">
            <div className="w-12 h-12 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center text-2xl shrink-0">
              <i className="bi bi-file-earmark-pdf-fill"></i>
            </div>
            <div>
              <h2 className="text-base font-extrabold text-ink-900">Laporan PDF</h2>
              <p className="text-xs text-ink-500 mt-0.5">
                Siap dicetak atau disimpan sebagai PDF.
              </p>
            </div>
          </div>

          <button
            onClick={handlePrint}
            className="w-full py-3 bg-[#2196F3] hover:bg-[#1976D2] text-white text-xs font-bold rounded-xl shadow-xs transition-base"
          >
            Cetak Laporan PDF
          </button>
        </div>

        <div className="bg-white border border-line rounded-2xl p-6 shadow-xs flex flex-col justify-between space-y-6">
          <div className="flex items-start gap-4">
            <div className="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl shrink-0">
              <i className="bi bi-file-earmark-spreadsheet-fill"></i>
            </div>
            <div>
              <h2 className="text-base font-extrabold text-ink-900">Rekap CSV</h2>
              <p className="text-xs text-ink-500 mt-0.5">
                Data pengukuran balita untuk spreadsheet.
              </p>
            </div>
          </div>

          <button
            onClick={handleExportCSV}
            className="w-full py-3 bg-white hover:bg-slate-50 text-[#2196F3] text-xs font-bold rounded-xl border border-[#2196F3] transition-base"
          >
            Unduh Data CSV
          </button>
        </div>
      </div>

      <div className="bg-white border border-line rounded-2xl p-6 shadow-xs flex flex-col sm:flex-row items-center justify-between gap-4">
        <div>
          <h2 className="text-sm font-extrabold text-ink-900">Ringkasan Data</h2>
          <p className="text-xs text-ink-500 mt-0.5">
            {pengukuranList.length} data balita/pengukuran untuk periode terpilih.
          </p>
        </div>

        <button
          onClick={() => navigate('/rekap')}
          className="w-full sm:w-auto px-5 py-2.5 bg-white hover:bg-slate-50 text-[#2196F3] text-xs font-bold rounded-xl border border-[#2196F3] transition-base"
        >
          Lihat Rekap Lengkap
        </button>
      </div>
    </div>
  );
};
