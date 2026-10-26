import React, { useState } from 'react';

export const PrioritasPage: React.FC<{ onNavigate: (page: string, params?: any) => void }> = ({ onNavigate }) => {
  const [filterDusun, setFilterDusun] = useState('semua');
  const [activeTab, setActiveTab] = useState<'semua' | 'prioritas' | 'pemantauan'>('semua');
  const [search, setSearch] = useState('');

  const totalKasus = 3;
  const totalMendesak = 1;
  const totalPemantauan = 2;

  const mockDisplayList = [
    {
      id: 2,
      nama: 'Farel Aditya',
      nik: '3515085408240002',
      status: 'prioritas',
      statusText: 'Prioritas Bidan',
      borderColor: 'border-l-rose-500',
      badgeClass: 'bg-rose-50 text-rose-700 border border-rose-200',
      avatarBg: 'bg-rose-100 text-rose-700',
      initial: 'F',
      usia: '2 tahun 10 bulan',
      dusun: 'Boharan',
      ortu: 'Rina Wulandari',
      bb: '11.10 kg',
      tb: '86.4 cm',
      tgl: '17 Agu 2026',
      catatanBg: 'bg-rose-50/60 border border-rose-200/60',
      catatanIcon: 'bi-exclamation-circle-fill text-rose-600',
      catatan: 'Kenaikan berat badan belum sesuai target.'
    },
    {
      id: 1,
      nama: 'Dinda Aulia',
      nik: '3515085408240001',
      status: 'pemantauan',
      statusText: 'Perlu Pemantauan',
      borderColor: 'border-l-amber-500',
      badgeClass: 'bg-amber-50 text-amber-800 border border-amber-200',
      avatarBg: 'bg-amber-100 text-amber-800',
      initial: 'D',
      usia: '2 tahun 4 bulan',
      dusun: 'Gamping Wetan',
      ortu: 'Siti Aminah',
      bb: '10.80 kg',
      tb: '83.1 cm',
      tgl: '18 Agu 2026',
      catatanBg: 'bg-amber-50/60 border border-amber-200/60',
      catatanIcon: 'bi-exclamation-circle-fill text-amber-600',
      catatan: 'Nafsu makan agak berkurang sejak 2 minggu lalu.'
    },
    {
      id: 5,
      nama: 'Almira Safitri',
      nik: '3515085408240005',
      status: 'pemantauan',
      statusText: 'Perlu Pemantauan',
      borderColor: 'border-l-amber-500',
      badgeClass: 'bg-amber-50 text-amber-800 border border-amber-200',
      avatarBg: 'bg-amber-100 text-amber-800',
      initial: 'A',
      usia: '3 tahun 1 bulan',
      dusun: 'Gamping Wetan',
      ortu: 'Siti Nurhayati',
      bb: '12.00 kg',
      tb: '88.0 cm',
      tgl: '12 Agu 2026',
      catatanBg: 'bg-amber-50/60 border border-amber-200/60',
      catatanIcon: 'bi-exclamation-circle-fill text-amber-600',
      catatan: 'Perlu pemantauan asupan makan.'
    }
  ];

  const filtered = mockDisplayList.filter((b) => {
    if (activeTab === 'prioritas' && b.status !== 'prioritas') return false;
    if (activeTab === 'pemantauan' && b.status !== 'pemantauan') return false;
    if (filterDusun !== 'semua' && b.dusun !== filterDusun) return false;
    if (search.trim()) {
      const q = search.toLowerCase();
      return b.nama.toLowerCase().includes(q) || b.nik.includes(q) || b.ortu.toLowerCase().includes(q);
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
              <option value="Gamping Wetan">Dusun Gamping Wetan</option>
              <option value="Boharan">Dusun Boharan</option>
              <option value="Kemangsen">Dusun Kemangsen</option>
              <option value="Bendomungal">Dusun Bendomungal</option>
            </select>
          </div>

          <div className="flex items-center bg-[#F0F7FF] p-1 rounded-xl border border-line text-xs font-bold">
            <button
              onClick={() => setActiveTab('semua')}
              className={`px-3 py-1.5 rounded-lg transition-base ${activeTab === 'semua' ? 'bg-white text-[#0D47A1] shadow-xs' : 'text-[#607D99]'}`}
            >
              Semua (3)
            </button>
            <button
              onClick={() => setActiveTab('prioritas')}
              className={`px-3 py-1.5 rounded-lg transition-base ${activeTab === 'prioritas' ? 'bg-white text-rose-600 shadow-xs' : 'text-[#607D99]'}`}
            >
              Prioritas (1)
            </button>
            <button
              onClick={() => setActiveTab('pemantauan')}
              className={`px-3 py-1.5 rounded-lg transition-base ${activeTab === 'pemantauan' ? 'bg-white text-amber-600 shadow-xs' : 'text-[#607D99]'}`}
            >
              Pantau (2)
            </button>
          </div>
        </div>
      </div>

      <div className="space-y-4">
        {filtered.map((b) => (
          <div
            key={b.id}
            className={`bg-white border border-line border-l-4 ${b.borderColor} rounded-2xl p-6 shadow-xs flex flex-col lg:flex-row lg:items-center justify-between gap-6`}
          >
            <div className="priority-child-info flex-1 space-y-4">
              <div className="flex items-start gap-4">
                <div className={`w-12 h-12 rounded-full font-black text-base flex items-center justify-center shrink-0 ${b.avatarBg}`}>
                  {b.initial}
                </div>
                <div>
                  <div className="flex items-center gap-2 flex-wrap mb-1">
                    <span className="text-lg font-black text-[#0D47A1]">{b.nama}</span>
                    <span className={`px-2.5 py-0.5 rounded-full text-xs font-bold ${b.badgeClass}`}>
                      {b.statusText}
                    </span>
                    <span className="px-2 py-0.5 rounded-md bg-[#F0F7FF] text-[#607D99] font-mono text-[11px] border border-line">
                      NIK: {b.nik}
                    </span>
                  </div>
                  <div className="text-xs text-[#607D99] flex items-center gap-2 flex-wrap">
                    <span><i className="bi bi-clock me-1 text-[#2196F3]"></i>{b.usia}</span>
                    <span>&bull;</span>
                    <span><i className="bi bi-geo-alt me-1 text-[#2196F3]"></i>Dusun {b.dusun}</span>
                    <span>&bull;</span>
                    <span><i className="bi bi-people me-1 text-[#2196F3]"></i>Orang Tua: {b.ortu}</span>
                  </div>
                </div>
              </div>

              <div className="grid grid-cols-3 gap-3">
                <div className="p-3 bg-[#F0F7FF]/60 rounded-xl border border-line">
                  <span className="text-[10px] text-[#607D99] font-bold uppercase block">Berat Badan</span>
                  <strong className="text-sm font-black text-[#0D47A1]">{b.bb}</strong>
                </div>
                <div className="p-3 bg-[#F0F7FF]/60 rounded-xl border border-line">
                  <span className="text-[10px] text-[#607D99] font-bold uppercase block">Tinggi Badan</span>
                  <strong className="text-sm font-black text-[#0D47A1]">{b.tb}</strong>
                </div>
                <div className="p-3 bg-[#F0F7FF]/60 rounded-xl border border-line">
                  <span className="text-[10px] text-[#607D99] font-bold uppercase block">Tgl Penimbangan</span>
                  <strong className="text-sm font-black text-[#0D47A1]">{b.tgl}</strong>
                </div>
              </div>

              <div className={`p-3.5 rounded-xl text-xs flex items-center gap-2.5 ${b.catatanBg}`}>
                <i className={`bi ${b.catatanIcon} text-sm shrink-0`}></i>
                <div className="text-[#234A73]">
                  <strong>Catatan Bidan &amp; Indikasi:</strong> <span className="block mt-2">{b.catatan}</span>
                </div>
              </div>
            </div>

            <div className="priority-child-actions flex flex-col gap-2 shrink-0 lg:w-48">
              <button
                onClick={() => onNavigate('detail_balita', { id: b.id })}
                className="w-full px-4 py-2.5 bg-[#2196F3] hover:bg-[#1976D2] text-white text-xs font-bold rounded-xl flex items-center justify-center gap-1.5 shadow-xs transition-base"
              >
                <i className="bi bi-graph-up"></i>
                <span>Tinjau Riwayat &amp; Grafik</span>
              </button>
              <button
                onClick={() => onNavigate('pengukuran', { preselectedId: b.id })}
                className="w-full px-4 py-2.5 bg-white hover:bg-slate-50 text-[#1976D2] text-xs font-bold rounded-xl border border-[#90CAF9] flex items-center justify-center gap-1.5 transition-base"
              >
                <i className="bi bi-clipboard-plus"></i>
                <span>Catat Timbangan Baru</span>
              </button>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};
