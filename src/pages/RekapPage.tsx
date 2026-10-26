import React, { useState, useEffect } from 'react';
import { pengukuranService } from '../services/api';
import type { Pengukuran } from '../types';
import { formatTanggalId } from '../utils/helpers';
import { StatusBadge } from '../components/ui/StatusBadge';

export const RekapPage: React.FC<{ onNavigate: (page: string, params?: any) => void }> = ({ onNavigate }) => {
  const [data, setData] = useState<Pengukuran[]>([]);
  const [filterBulan, setFilterBulan] = useState('2026-08');
  const [filterDusun, setFilterDusun] = useState('semua');
  const [loading, setLoading] = useState(true);

  const loadData = async () => {
    setLoading(true);
    try {
      const list = await pengukuranService.getAll(filterBulan, filterDusun);
      setData(list);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadData();
  }, [filterBulan, filterDusun]);

  const handleExportCsv = () => {
    if (data.length === 0) return;
    const headers = ['Tanggal', 'Nama Balita', 'Dusun', 'Usia (Bulan)', 'Berat (kg)', 'Tinggi (cm)', 'Status', 'Catatan'];
    const rows = data.map((d) => [
      d.tanggal_ukur,
      `"${d.nama_anak || '-'}"`,
      `"${d.dusun || '-'}"`,
      d.usia_bulan,
      d.berat_kg,
      d.tinggi_cm,
      d.kategori_status,
      `"${d.catatan || ''}"`
    ]);

    const csvContent = 'data:text/csv;charset=utf-8,' + [headers.join(','), ...rows.map((e) => e.join(','))].join('\n');
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', `rekap_pengukuran_${filterBulan}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  };

  const handleExportPdf = () => {
    window.print();
  };

  return (
    <div className="recap-page space-y-6">
      <div>
        <h1 className="text-2xl font-extrabold text-[#0D47A1]">Rekapitulasi Pengukuran Desa</h1>
        <p className="text-xs text-ink-500 mt-1">
          Total {data.length} riwayat penimbangan tercatat
        </p>
      </div>

      <div className="bg-white border border-line rounded-2xl p-4 shadow-xs flex flex-col sm:flex-row items-center gap-3">
        <select
          value={filterDusun}
          onChange={(e) => setFilterDusun(e.target.value)}
          className="w-full sm:w-56 px-4 py-2.5 text-xs font-semibold bg-slate-50 border border-line rounded-xl focus:outline-none focus:border-[#2196F3] focus:bg-white"
        >
          <option value="semua">Semua Dusun</option>
          <option value="Gamping Wetan">Dusun Gamping Wetan</option>
          <option value="Gamping Tengah">Dusun Gamping Tengah</option>
          <option value="Boharan">Dusun Boharan</option>
          <option value="Kasengan">Dusun Kasengan</option>
          <option value="Bendomungal">Dusun Bendomungal</option>
        </select>

        <select
          value={filterBulan}
          onChange={(e) => setFilterBulan(e.target.value)}
          className="w-full sm:w-56 px-4 py-2.5 text-xs font-semibold bg-slate-50 border border-line rounded-xl focus:outline-none focus:border-[#2196F3] focus:bg-white"
        >
          <option value="2026-08">Agustus 2026</option>
          <option value="2026-07">Juli 2026</option>
          <option value="2026-06">Juni 2026</option>
          <option value="semua">Semua Periode</option>
        </select>

        <div className="flex items-center gap-2 w-full sm:w-auto sm:ml-auto">
          <button
            onClick={loadData}
            className="flex-1 sm:flex-none px-5 py-2.5 bg-[#2196F3] hover:bg-[#1976D2] text-white text-xs font-bold rounded-xl transition-base"
          >
            Filter
          </button>

          <button
            onClick={handleExportPdf}
            className="flex-1 sm:flex-none px-3.5 py-2.5 bg-white hover:bg-slate-50 text-ink-700 text-xs font-bold rounded-xl border border-line flex items-center justify-center gap-1.5 transition-base"
          >
            <i className="bi bi-file-earmark-pdf text-rose-500"></i>
            <span>PDF</span>
          </button>

          <button
            onClick={handleExportCsv}
            className="flex-1 sm:flex-none px-3.5 py-2.5 bg-white hover:bg-slate-50 text-ink-700 text-xs font-bold rounded-xl border border-line flex items-center justify-center gap-1.5 transition-base"
          >
            <i className="bi bi-file-earmark-spreadsheet text-emerald-600"></i>
            <span>CSV</span>
          </button>
        </div>
      </div>

      <div className="table-panel bg-white border border-line rounded-2xl overflow-hidden shadow-xs">
        {loading ? (
          <div className="p-12 text-center text-ink-400">
            <i className="bi bi-arrow-repeat animate-spin text-2xl text-[#2196F3]"></i>
            <p className="mt-2 text-xs font-semibold">Memuat rekapitulasi data...</p>
          </div>
        ) : data.length === 0 ? (
          <div className="p-12 text-center text-ink-400">
            <i className="bi bi-inbox text-3xl mb-2 block"></i>
            <p className="text-xs font-semibold">Belum ada riwayat pengukuran pada periode ini.</p>
          </div>
        ) : (
          <div className="overflow-x-auto">
            <table className="w-full text-left text-xs border-collapse">
              <thead>
                <tr className="border-b border-line text-[#3D5F7D] font-bold text-xs">
                  <th className="py-4 px-4">Tanggal</th>
                  <th className="py-4 px-4">Nama Balita</th>
                  <th className="py-4 px-3">Dusun</th>
                  <th className="py-4 px-3 text-center">Usia</th>
                  <th className="py-4 px-3 text-center">Berat (kg)</th>
                  <th className="py-4 px-3 text-center">Tinggi (cm)</th>
                  <th className="py-4 px-4 text-center">Status Skrining</th>
                  <th className="py-4 px-4">Catatan</th>
                  <th className="py-4 px-4 text-right">Aksi</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-line">
                {data.map((item) => {
                  const ortuMap: Record<number, string> = {
                    1: 'Siti Aminah',
                    2: 'Rina Wulandari',
                    3: 'Dewi Lestari',
                    4: 'Andi Saputra',
                    5: 'Siti Nurhayati'
                  };
                  return (
                    <tr key={item.id} className="hover:bg-slate-50/70 transition-base">
                      <td className="py-3.5 px-4 text-[#3D5F7D] whitespace-nowrap font-medium">
                        {formatTanggalId(item.tanggal_ukur, true)}
                      </td>
                      <td className="py-3.5 px-4">
                        <strong className="text-[#0D47A1] font-bold block">{item.nama_anak}</strong>
                        <span className="text-xs text-[#607D99]">{ortuMap[item.anak_id] || 'Siti Aminah'}</span>
                      </td>
                      <td className="py-3.5 px-3 text-ink-700 whitespace-nowrap">
                        {item.dusun}
                      </td>
                      <td className="py-3.5 px-3 text-center text-ink-700 whitespace-nowrap">
                        {item.usia_bulan} bln
                      </td>
                      <td className="py-3.5 px-3 text-center font-bold text-[#0D47A1] whitespace-nowrap">
                        {item.berat_kg}
                      </td>
                      <td className="py-3.5 px-3 text-center font-bold text-[#0D47A1] whitespace-nowrap">
                        {item.tinggi_cm}
                      </td>
                      <td className="py-3.5 px-4 text-center">
                        <StatusBadge status={item.kategori_status} />
                      </td>
                      <td className="py-3.5 px-4 text-[#3D5F7D] max-w-xs text-xs">
                        {item.catatan || '-'}
                      </td>
                      <td className="py-3.5 px-4 text-right whitespace-nowrap">
                        <button
                          onClick={() => onNavigate('detail_balita', { id: item.anak_id })}
                          className="px-3 py-1.5 bg-white hover:bg-blue-50 text-[#2196F3] text-xs font-semibold rounded-lg border border-[#2196F3] flex items-center gap-1.5 ml-auto transition-base"
                        >
                          <i className="bi bi-graph-up"></i>
                          <span>Detail</span>
                        </button>
                      </td>
                    </tr>
                  );
                })}
              </tbody>
            </table>
          </div>
        )}
      </div>
    </div>
  );
};
