import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { balitaService } from '../services/api';
import { useAsyncData } from '../hooks/useAsyncData';
import type { Balita } from '../types';
import { hitungUmur, formatTanggalId } from '../utils/helpers';
import { DUSUN_LIST } from '../utils/constants';
import { StatusBadge } from '../components/ui/StatusBadge';
import { ChildModal } from '../components/ui/ChildModal';

export const BalitaPage: React.FC = () => {
  const navigate = useNavigate();
  const [search, setSearch] = useState('');
  const [filterDusun, setFilterDusun] = useState('semua');
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [editingBalita, setEditingBalita] = useState<Balita | null>(null);

  const { data: daftarBalita = [], loading, error, reload } = useAsyncData(
    () => balitaService.getAll(filterDusun, search),
    [filterDusun, search]
  );

  const handleSaveBalita = async (formData: Omit<Balita, 'id'>) => {
    if (editingBalita) {
      await balitaService.update(editingBalita.id, formData);
    } else {
      await balitaService.create(formData);
    }
    setIsModalOpen(false);
    setEditingBalita(null);
    reload();
  };

  return (
    <div className="balita-page space-y-6">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl font-extrabold text-[#0D47A1]">Daftar Balita Binaan</h1>
          <p className="text-xs text-ink-500 mt-1">
            Total {daftarBalita.length} balita terdaftar di Posyandu Desa Sukatani
          </p>
        </div>

        <button
          onClick={() => {
            setEditingBalita(null);
            setIsModalOpen(true);
          }}
          className="px-4 py-2.5 bg-[#2196F3] hover:bg-[#1976D2] text-white text-xs font-bold rounded-xl flex items-center gap-2 self-start sm:self-auto shadow-xs transition-base"
        >
          <i className="bi bi-person-plus"></i>
          <span>Tambah Balita Baru</span>
        </button>
      </div>

      <div className="balita-filters bg-white border border-line rounded-2xl p-4 shadow-xs flex flex-col sm:flex-row items-center gap-3">
        <div className="relative flex-1 w-full">
          <i className="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-ink-400 text-xs"></i>
          <input
            type="text"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            placeholder="Cari nama balita, NIK, atau nama ortu..."
            className="w-full pl-9 pr-4 py-2.5 text-xs bg-slate-50 border border-line rounded-xl focus:outline-none focus:border-[#2196F3] focus:bg-white transition-base"
          />
        </div>

        <div className="balita-filter-actions flex items-center gap-2 w-full sm:w-auto">
          <select
            value={filterDusun}
            onChange={(e) => setFilterDusun(e.target.value)}
            className="w-full sm:w-48 px-3 py-2.5 text-xs font-semibold bg-slate-50 border border-line rounded-xl focus:outline-none focus:border-[#2196F3] focus:bg-white"
          >
            <option value="semua">Semua Dusun</option>
            {DUSUN_LIST.map((d) => (
              <option key={d} value={d}>Dusun {d}</option>
            ))}
          </select>

          <button
            onClick={reload}
            className="px-4 py-2.5 bg-[#2196F3] hover:bg-[#1976D2] text-white text-xs font-bold rounded-xl transition-base shrink-0"
          >
            Filter
          </button>
        </div>
      </div>

      <div className="table-panel bg-white border border-line rounded-2xl overflow-hidden shadow-xs">
        {loading ? (
          <div className="p-12 text-center text-ink-400">
            <i className="bi bi-arrow-repeat animate-spin text-2xl text-[#2196F3]"></i>
            <p className="mt-2 text-xs font-semibold">Memuat data balita...</p>
          </div>
        ) : daftarBalita.length === 0 ? (
          <div className="p-12 text-center text-ink-400">
            <i className={`bi ${error ? 'bi-exclamation-circle text-amber-500' : 'bi-inbox'} text-3xl mb-2 block`}></i>
            <p className="text-xs font-semibold">{error || 'Tidak ditemukan data balita sesuai kriteria pencarian.'}</p>
          </div>
        ) : (
          <div className="overflow-x-auto">
            <table className="w-full text-left text-xs border-collapse">
              <thead>
                <tr className="border-b border-line text-[#3D5F7D] font-bold text-xs">
                  <th className="py-4 px-4">Nama &amp; NIK</th>
                  <th className="py-4 px-3">Usia</th>
                  <th className="py-4 px-3 text-center">JK</th>
                  <th className="py-4 px-3">Dusun</th>
                  <th className="py-4 px-4">Orang Tua</th>
                  <th className="py-4 px-4">Pengukuran Terakhir</th>
                  <th className="py-4 px-3 text-center">Status Skrining</th>
                  <th className="py-4 px-4 text-right">Aksi</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-line">
                {daftarBalita.map((anak) => {
                  const umur = hitungUmur(anak.tanggal_lahir);
                  const tglTerakhir = anak.tanggal_ukur_terakhir
                    ? formatTanggalId(anak.tanggal_ukur_terakhir, true)
                    : 'Belum diukur';
                  return (
                    <tr key={anak.id} className="hover:bg-slate-50/70 transition-base">
                      <td className="py-3.5 px-4">
                        <div className="flex items-center gap-2.5">
                          <div className="w-8 h-8 rounded-full bg-[#E3F2FD] text-[#2196F3] font-bold flex items-center justify-center shrink-0 text-xs">
                            {anak.nama.charAt(0)}
                          </div>
                          <div>
                            <button
                              onClick={() => navigate(`/balita/${anak.id}`)}
                              className="font-bold text-[#0D47A1] hover:text-[#2196F3] transition-base block text-left"
                            >
                              {anak.nama}
                            </button>
                            <span className="text-[11px] text-ink-500">
                              {anak.nik || '-'}
                            </span>
                          </div>
                        </div>
                      </td>
                      <td className="py-3.5 px-3 font-medium text-ink-700 whitespace-nowrap">
                        {umur.teks}
                      </td>
                      <td className="py-3.5 px-3 text-center">
                        {anak.jenis_kelamin === 'L' ? (
                          <span className="font-bold text-[#2196F3] text-xs">L</span>
                        ) : (
                          <span className="font-bold text-rose-500 text-xs">P</span>
                        )}
                      </td>
                      <td className="py-3.5 px-3 text-ink-700 whitespace-nowrap">
                        {anak.dusun}
                      </td>
                      <td className="py-3.5 px-4 text-ink-700 max-w-xs truncate">
                        {anak.nama_ortu}
                      </td>
                      <td className="py-3.5 px-4 text-ink-700">
                        {anak.berat_terakhir ? (
                          <div>
                            <strong className="text-[#0D47A1] font-bold">
                              {anak.berat_terakhir} kg
                            </strong> &middot; {anak.tinggi_terakhir} cm
                            <span className="block text-[11px] text-ink-400 mt-0.5">{tglTerakhir}</span>
                          </div>
                        ) : (
                          <span className="text-ink-400 italic">Belum diukur</span>
                        )}
                      </td>
                      <td className="py-3.5 px-3 text-center">
                        <StatusBadge status={anak.status_terakhir} />
                      </td>
                      <td className="py-3.5 px-4 text-right whitespace-nowrap">
                        <div className="inline-flex items-center gap-1.5">
                          <button
                            onClick={() => navigate(`/pengukuran?anak=${anak.id}`)}
                            className="px-3 py-1.5 bg-[#2196F3] hover:bg-[#1976D2] text-white text-xs font-semibold rounded-lg shadow-xs flex items-center gap-1.5 transition-base"
                          >
                            <i className="bi bi-clipboard-plus"></i>
                            <span>Ukur</span>
                          </button>
                          <button
                            onClick={() => navigate(`/balita/${anak.id}`)}
                            className="px-3 py-1.5 bg-white hover:bg-blue-50 text-[#2196F3] text-xs font-semibold rounded-lg border border-[#2196F3] flex items-center gap-1.5 transition-base"
                          >
                            <i className="bi bi-eye"></i>
                            <span>Detail</span>
                          </button>
                        </div>
                      </td>
                    </tr>
                  );
                })}
              </tbody>
            </table>
          </div>
        )}
      </div>

      <ChildModal
        isOpen={isModalOpen}
        initialData={editingBalita}
        onClose={() => {
          setIsModalOpen(false);
          setEditingBalita(null);
        }}
        onSave={handleSaveBalita}
      />
    </div>
  );
};
