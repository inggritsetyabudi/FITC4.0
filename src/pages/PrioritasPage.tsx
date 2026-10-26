import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { balitaService, pengukuranService } from '../services/api';
import { useAsyncData } from '../hooks/useAsyncData';
import { hitungUmur, formatTanggalId } from '../utils/helpers';
import { DUSUN_LIST } from '../utils/constants';
import type { Pengukuran } from '../types';

const STATUS_VISUAL: Record<string, { border: string; badge: string; avatar: string; text: string }> = {
  prioritas: {
    border: 'border-l-rose-500',
    badge: 'bg-rose-50 text-rose-700 border border-rose-200',
    avatar: 'bg-rose-100 text-rose-700',
    text: 'Prioritas Bidan'
  },
  berisiko: {
    border: 'border-l-rose-500',
    badge: 'bg-rose-50 text-rose-700 border border-rose-200',
    avatar: 'bg-rose-100 text-rose-700',
    text: 'Prioritas Bidan'
  },
  pemantauan: {
    border: 'border-l-amber-500',
    badge: 'bg-amber-50 text-amber-800 border border-amber-200',
    avatar: 'bg-amber-100 text-amber-800',
    text: 'Perlu Pemantauan'
  }
};

function latestByAnak(pengukuran: Pengukuran[]): Map<number, Pengukuran> {
  const map = new Map<number, Pengukuran>();
  for (const p of [...pengukuran].sort((a, b) => a.tanggal_ukur.localeCompare(b.tanggal_ukur))) {
    map.set(p.anak_id, p);
  }
  return map;
}

export const PrioritasPage: React.FC = () => {
  const navigate = useNavigate();
  const [filterDusun, setFilterDusun] = useState('semua');
  const [activeTab, setActiveTab] = useState<'semua' | 'prioritas' | 'pemantauan'>('semua');
  const [search, setSearch] = useState('');

  const { data: balita = [] } = useAsyncData(() => balitaService.getAll());
  const { data: pengukuran = [] } = useAsyncData(() => pengukuranService.getAll());
  const terakhirMap = latestByAnak(pengukuran);

  const daftarPrioritas = balita.filter((b) => b.status_terakhir && b.status_terakhir !== 'normal');

  const totalKasus = daftarPrioritas.length;
  const totalMendesak = daftarPrioritas.filter((b) => ['prioritas', 'berisiko'].includes(b.status_terakhir || '')).length;
  const totalPemantauan = daftarPrioritas.filter((b) => b.status_terakhir === 'pemantauan').length;

  const filtered = daftarPrioritas.filter((b) => {
    const mendesak = ['prioritas', 'berisiko'].includes(b.status_terakhir || '');
    if (activeTab === 'prioritas' && !mendesak) return false;
    if (activeTab === 'pemantauan' && b.status_terakhir !== 'pemantauan') return false;
    if (filterDusun !== 'semua' && b.dusun !== filterDusun) return false;
    if (search.trim()) {
      const q = search.toLowerCase();
      return b.nama.toLowerCase().includes(q) || (b.nik || '').includes(q) || b.nama_ortu.toLowerCase().includes(q);
    }
    return true;
  });

  return (
    <div className="priority-page space-y-6 max-w-6xl mx-auto">
      <div>
        <h1 className="text-2xl font-black text-[#0D47A1]">Daftar Balita Prioritas Tinjauan</h1>
        <p className="text-xs text-[#607D99] mt-1 font-medium">
          Daftar anak yang memerlukan evaluasi kurva tumbuh kembang, intervensi gizi, atau rujukan klinis Bidan Desa.
        </p>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div className="bg-white p-5 rounded-2xl border border-line shadow-xs flex items-center gap-4">
          <div className="w-12 h-12 rounded-full bg-[#E3F2FD] text-[#0D47A1] flex items-center justify-center text-xl shrink-0">
            <i className="bi bi-people-fill"></i>
          </div>
          <div>
            <span className="text-[11px] font-bold text-[#607D99] uppercase tracking-wider block">Total Butuh Tinjauan</span>
            <div className="text-2xl font-black text-[#0D47A1] leading-none">
              {totalKasus} <span className="text-xs font-semibold text-[#607D99]">Balita</span>
            </div>
          </div>
        </div>

        <div className="bg-white p-5 rounded-2xl border border-line border-l-4 border-l-rose-500 shadow-xs flex items-center gap-4">
          <div className="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center text-xl shrink-0">
            <i className="bi bi-exclamation-triangle-fill"></i>
          </div>
          <div>
            <span className="text-[11px] font-bold text-[#607D99] uppercase tracking-wider block">Prioritas Bidan (Mendesak)</span>
            <div className="text-2xl font-black text-rose-600 leading-none">
              {totalMendesak} <span className="text-xs font-semibold text-[#607D99]">Balita</span>
            </div>
          </div>
        </div>

        <div className="bg-white p-5 rounded-2xl border border-line border-l-4 border-l-amber-500 shadow-xs flex items-center gap-4">
          <div className="w-12 h-12 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-xl shrink-0">
            <i className="bi bi-exclamation-circle-fill"></i>
          </div>
          <div>
            <span className="text-[11px] font-bold text-[#607D99] uppercase tracking-wider block">Perlu Pemantauan</span>
            <div className="text-2xl font-black text-amber-600 leading-none">
              {totalPemantauan} <span className="text-xs font-semibold text-[#607D99]">Balita</span>
            </div>
          </div>
        </div>
      </div>

      <div className="bg-white p-4 rounded-2xl border border-line shadow-xs flex flex-col md:flex-row items-center gap-3 justify-between">
        <div className="relative flex-1 w-full">
          <i className="bi bi-search absolute left-3.5 top-1/2 -translate-y-1/2 text-[#607D99] text-sm"></i>
          <input
            type="text"
            placeholder="Cari nama balita, ortu, atau NIK..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            className="w-full pl-10 pr-4 py-2 text-xs font-medium border border-line rounded-xl focus:outline-none focus:border-[#2196F3]"
          />
        </div>

        <div className="flex items-center gap-2 w-full md:w-auto">
          <div className="relative">
            <i className="bi bi-geo-alt-fill absolute left-3 top-1/2 -translate-y-1/2 text-[#2196F3] text-xs"></i>
            <select
              value={filterDusun}
              onChange={(e) => setFilterDusun(e.target.value)}
              className="pl-8 pr-6 py-2 text-xs font-bold border border-line rounded-xl bg-white text-[#3D5F7D]"
            >
              <option value="semua">Semua Dusun Binaan</option>
              {DUSUN_LIST.map((d) => (
                <option key={d} value={d}>Dusun {d}</option>
              ))}
            </select>
          </div>

          <div className="flex items-center bg-[#F0F7FF] p-1 rounded-xl border border-line text-xs font-bold">
            <button
              onClick={() => setActiveTab('semua')}
              className={`px-3 py-1.5 rounded-lg transition-base ${activeTab === 'semua' ? 'bg-white text-[#0D47A1] shadow-xs' : 'text-[#607D99]'}`}
            >
              Semua ({totalKasus})
            </button>
            <button
              onClick={() => setActiveTab('prioritas')}
              className={`px-3 py-1.5 rounded-lg transition-base ${activeTab === 'prioritas' ? 'bg-white text-rose-600 shadow-xs' : 'text-[#607D99]'}`}
            >
              Prioritas ({totalMendesak})
            </button>
            <button
              onClick={() => setActiveTab('pemantauan')}
              className={`px-3 py-1.5 rounded-lg transition-base ${activeTab === 'pemantauan' ? 'bg-white text-amber-600 shadow-xs' : 'text-[#607D99]'}`}
            >
              Pantau ({totalPemantauan})
            </button>
          </div>
        </div>
      </div>

      <div className="space-y-4">
        {filtered.length === 0 && (
          <div className="p-12 text-center bg-white border border-line rounded-2xl text-[#607D99]">
            <i className="bi bi-inbox text-3xl mb-2 block"></i>
            <p className="text-xs font-semibold">Tidak ada balita prioritas sesuai kriteria.</p>
          </div>
        )}
        {filtered.map((b) => {
          const visual = STATUS_VISUAL[b.status_terakhir || 'pemantauan'];
          const umur = hitungUmur(b.tanggal_lahir);
          const ukur = terakhirMap.get(b.id);
          const mendesak = ['prioritas', 'berisiko'].includes(b.status_terakhir || '');

          return (
            <div
              key={b.id}
              className={`bg-white border border-line border-l-4 ${visual.border} rounded-2xl p-6 shadow-xs flex flex-col lg:flex-row lg:items-center justify-between gap-6`}
            >
              <div className="priority-child-info flex-1 space-y-4">
                <div className="flex items-start gap-4">
                  <div className={`w-12 h-12 rounded-full font-black text-base flex items-center justify-center shrink-0 ${visual.avatar}`}>
                    {b.nama.charAt(0)}
                  </div>
                  <div>
                    <div className="flex items-center gap-2 flex-wrap mb-1">
                      <span className="text-lg font-black text-[#0D47A1]">{b.nama}</span>
                      <span className={`px-2.5 py-0.5 rounded-full text-xs font-bold ${visual.badge}`}>
                        {visual.text}
                      </span>
                      <span className="px-2 py-0.5 rounded-md bg-[#F0F7FF] text-[#607D99] font-mono text-[11px] border border-line">
                        NIK: {b.nik || '-'}
                      </span>
                    </div>
                    <div className="text-xs text-[#607D99] flex items-center gap-2 flex-wrap">
                      <span><i className="bi bi-clock me-1 text-[#2196F3]"></i>{umur.teks}</span>
                      <span>&bull;</span>
                      <span><i className="bi bi-geo-alt me-1 text-[#2196F3]"></i>Dusun {b.dusun}</span>
                      <span>&bull;</span>
                      <span><i className="bi bi-people me-1 text-[#2196F3]"></i>Orang Tua: {b.nama_ortu}</span>
                    </div>
                  </div>
                </div>

                <div className="grid grid-cols-3 gap-3">
                  <div className="p-3 bg-[#F0F7FF]/60 rounded-xl border border-line">
                    <span className="text-[10px] text-[#607D99] font-bold uppercase block">Berat Badan</span>
                    <strong className="text-sm font-black text-[#0D47A1]">{b.berat_terakhir ?? '-'} kg</strong>
                  </div>
                  <div className="p-3 bg-[#F0F7FF]/60 rounded-xl border border-line">
                    <span className="text-[10px] text-[#607D99] font-bold uppercase block">Tinggi Badan</span>
                    <strong className="text-sm font-black text-[#0D47A1]">{b.tinggi_terakhir ?? '-'} cm</strong>
                  </div>
                  <div className="p-3 bg-[#F0F7FF]/60 rounded-xl border border-line">
                    <span className="text-[10px] text-[#607D99] font-bold uppercase block">Tgl Penimbangan</span>
                    <strong className="text-sm font-black text-[#0D47A1]">
                      {ukur ? formatTanggalId(ukur.tanggal_ukur, true) : '-'}
                    </strong>
                  </div>
                </div>

                <div className={`p-3.5 rounded-xl text-xs flex items-center gap-2.5 ${mendesak ? 'bg-rose-50/60 border border-rose-200/60' : 'bg-amber-50/60 border border-amber-200/60'}`}>
                  <i className={`bi bi-exclamation-circle-fill text-sm shrink-0 ${mendesak ? 'text-rose-600' : 'text-amber-600'}`}></i>
                  <div className="text-[#234A73]">
                    <strong>Catatan Bidan &amp; Indikasi:</strong> <span className="block mt-2">{ukur?.catatan || 'Belum ada catatan pengukuran.'}</span>
                  </div>
                </div>
              </div>

              <div className="priority-child-actions flex flex-col gap-2 shrink-0 lg:w-48">
                <button
                  onClick={() => navigate(`/balita/${b.id}`)}
                  className="w-full px-4 py-2.5 bg-[#2196F3] hover:bg-[#1976D2] text-white text-xs font-bold rounded-xl flex items-center justify-center gap-1.5 shadow-xs transition-base"
                >
                  <i className="bi bi-graph-up"></i>
                  <span>Tinjau Riwayat &amp; Grafik</span>
                </button>
                <button
                  onClick={() => navigate(`/pengukuran?anak=${b.id}`)}
                  className="w-full px-4 py-2.5 bg-white hover:bg-slate-50 text-[#1976D2] text-xs font-bold rounded-xl border border-[#90CAF9] flex items-center justify-center gap-1.5 transition-base"
                >
                  <i className="bi bi-clipboard-plus"></i>
                  <span>Catat Timbangan Baru</span>
                </button>
              </div>
            </div>
          );
        })}
      </div>
    </div>
  );
};
