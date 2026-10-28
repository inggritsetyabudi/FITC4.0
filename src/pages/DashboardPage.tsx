import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { balitaService, pengukuranService, petugasService } from '../services/api';
import { useAsyncData } from '../hooks/useAsyncData';
import { ChildModal } from '../components/ui/ChildModal';
import { StatusBadge } from '../components/ui/StatusBadge';
import { hitungUmur, formatTanggalId } from '../utils/helpers';
import { DUSUN_LIST } from '../utils/constants';
import type { Balita, Pengukuran } from '../types';
import type { ChartData, ChartOptions } from 'chart.js';

const BarChartView = React.lazy(() => import('../components/ui/ChartViews').then((module) => ({ default: module.BarChartView })));
const LineChartView = React.lazy(() => import('../components/ui/ChartViews').then((module) => ({ default: module.LineChartView })));

const BULAN_NAMA = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

function getBulanBerjalan(pengukuran: Pengukuran[]): string {
  const terakhir = pengukuran.map((p) => p.tanggal_ukur.slice(0, 7)).sort().at(-1);
  return terakhir || new Date().toISOString().slice(0, 7);
}

function labelBulan(bulan: string): string {
  const [tahun, bulanNum] = bulan.split('-');
  return `${BULAN_NAMA[Number(bulanNum) - 1]} ${tahun}`;
}

function ukurTerakhirPerAnak(pengukuran: Pengukuran[]): Map<number, Pengukuran> {
  const map = new Map<number, Pengukuran>();
  for (const p of [...pengukuran].sort((a, b) => a.tanggal_ukur.localeCompare(b.tanggal_ukur))) {
    map.set(p.anak_id, p);
  }
  return map;
}

function statusTidakNormal(b: Balita | undefined): boolean {
  return Boolean(b?.status_terakhir && b.status_terakhir !== 'normal');
}

export const DashboardPage: React.FC = () => {
  const { user } = useAuth();
  const navigate = useNavigate();
  const [isModalOpen, setIsModalOpen] = useState(false);

  const role = user?.role || 'kader';
  const { data: balita = [] } = useAsyncData(() => balitaService.getAll());
  const { data: pengukuran = [] } = useAsyncData(() => pengukuranService.getAll());
  const { data: petugas = [] } = useAsyncData(
    () => (role === 'bidan' ? Promise.resolve([]) : petugasService.getAll()),
    [role]
  );

  const bulanBerjalan = getBulanBerjalan(pengukuran);
  const sudahDitimbang = new Set(
    pengukuran.filter((p) => p.tanggal_ukur.startsWith(bulanBerjalan)).map((p) => p.anak_id)
  );
  const perluPerhatian = balita.filter(statusTidakNormal);
  const belumDitimbang = balita.filter((b) => !sudahDitimbang.has(b.id));
  const terakhirMap = ukurTerakhirPerAnak(pengukuran);

  const handleAddChild = async (data: Omit<Balita, 'id'>) => {
    await balitaService.create(data);
    setIsModalOpen(false);
  };

  if (role === 'bidan') {
    const normal = balita.filter((b) => b.status_terakhir === 'normal').length;
    const pemantauan = balita.filter((b) => b.status_terakhir === 'pemantauan').length;
    const mendesak = balita.filter((b) => b.status_terakhir === 'prioritas' || b.status_terakhir === 'berisiko').length;

    const sebaran = DUSUN_LIST.map((dusun) => {
      const anakDusun = balita.filter((b) => b.dusun === dusun);
      return {
        name: dusun.split(' ')[0],
        normal: anakDusun.filter((b) => b.status_terakhir === 'normal').length,
        pemantauan: anakDusun.filter((b) => b.status_terakhir === 'pemantauan').length,
        prioritas: anakDusun.filter((b) => ['prioritas', 'berisiko'].includes(b.status_terakhir || '')).length
      };
    });
    const enamBulan = [...new Set(pengukuran.map((p) => p.tanggal_ukur.slice(0, 7)))].sort().slice(-6);
    const tren = enamBulan.map((bulan) => ({
      bulan,
      jumlah: pengukuran.filter((p) => p.tanggal_ukur.startsWith(bulan)).length
    }));

    const sebaranChartData: ChartData<'bar'> = {
      labels: sebaran.map((dusun) => dusun.name),
      datasets: [
        { label: 'Normal', data: sebaran.map((dusun) => dusun.normal), backgroundColor: '#0D47A1', borderRadius: 3, borderSkipped: false, stack: 'status' },
        { label: 'Pemantauan', data: sebaran.map((dusun) => dusun.pemantauan), backgroundColor: '#F59E0B', borderRadius: 3, borderSkipped: false, stack: 'status' },
        { label: 'Prioritas Bidan', data: sebaran.map((dusun) => dusun.prioritas), backgroundColor: '#E11D48', borderRadius: 3, borderSkipped: false, stack: 'status' }
      ]
    };
    const sebaranChartOptions: ChartOptions<'bar'> = {
      responsive: true,
      maintainAspectRatio: false,
      interaction: { mode: 'index', intersect: false },
      plugins: {
        legend: { display: false },
        tooltip: { enabled: true, backgroundColor: '#0D2744', padding: 10, usePointStyle: true }
      },
      scales: {
        x: { stacked: true, grid: { display: false }, border: { display: false }, ticks: { color: '#607D99', font: { size: 11 } } },
        y: { stacked: true, beginAtZero: true, border: { display: false }, grid: { color: '#E8EFF5' }, ticks: { precision: 0, color: '#607D99', font: { size: 10 } } }
      }
    };
    const trendChartData: ChartData<'line'> = {
      labels: tren.map(({ bulan }) => `${BULAN_NAMA[Number(bulan.split('-')[1]) - 1].slice(0, 3)} '${bulan.slice(2, 4)}`),
      datasets: [{
        label: 'Pengukuran',
        data: tren.map(({ jumlah }) => jumlah),
        borderColor: '#1976D2',
        backgroundColor: 'rgba(25, 118, 210, .10)',
        fill: true,
        tension: .35,
        borderWidth: 3,
        pointRadius: 4,
        pointHoverRadius: 6,
        pointBackgroundColor: '#0D47A1',
        pointBorderColor: '#fff',
        pointBorderWidth: 2
      }]
    };
    const trendChartOptions: ChartOptions<'line'> = {
      responsive: true,
      maintainAspectRatio: false,
      interaction: { mode: 'index', intersect: false },
      plugins: {
        legend: { display: false },
        tooltip: { enabled: true, backgroundColor: '#0D2744', padding: 10, callbacks: { label: (context) => `${context.parsed.y} pengukuran` } }
      },
      scales: {
        x: { grid: { display: false }, border: { display: false }, ticks: { color: '#607D99', maxRotation: 0, font: { size: 10 } } },
        y: { beginAtZero: true, border: { display: false }, grid: { color: '#E8EFF5' }, ticks: { precision: 0, color: '#607D99', font: { size: 10 } } }
      }
    };

    return (
      <div className="dashboard-page dashboard-bidan space-y-6 max-w-6xl mx-auto">
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <h1 className="text-2xl font-black text-[#0D47A1]">Panel Evaluasi Bidan Desa</h1>
            <p className="text-xs text-[#607D99] mt-1 font-medium">
              Wilayah Kerja Desa Sukatani &bull; {labelBulan(bulanBerjalan)}
            </p>
          </div>
          <div className="flex items-center gap-2.5">
            <button
              onClick={() => navigate('/rekap')}
              className="dashboard-action px-4 py-2 bg-white hover:bg-slate-50 text-[#1976D2] text-xs font-bold rounded-xl border border-[#90CAF9] flex items-center gap-1.5 transition-base shadow-xs"
            >
              <i className="bi bi-journal-text text-sm"></i>
              <span>Rekap Pengukuran</span>
            </button>
            <button
              onClick={() => navigate('/prioritas')}
              className="dashboard-action px-4 py-2 bg-[#2196F3] hover:bg-[#1976D2] text-white text-xs font-bold rounded-xl flex items-center gap-1.5 shadow-xs transition-base"
            >
              <i className="bi bi-exclamation-triangle-fill text-sm"></i>
              <span>Semua Prioritas ({perluPerhatian.length})</span>
            </button>
          </div>
        </div>

        <section className="bg-white rounded-2xl border-2 border-[#FFC107] p-6 shadow-xs">
          <div className="flex flex-col sm:flex-row sm:items-center justify-between pb-3 mb-4 border-b border-line gap-2">
            <div>
              <span className="dashboard-eyebrow inline-block mb-1.5">
                ★ Tinjauan Utama
              </span>
              <h2 className="text-base font-bold text-[#0D47A1]">Daftar Balita Perlu Ditinjau Bidan</h2>
              <p className="text-xs text-[#607D99]">
                Balita dengan perlambatan kenaikan berat badan atau status prioritas dari kunjungan penimbangan terakhir.
              </p>
            </div>
            <button
              onClick={() => navigate('/prioritas')}
              className="dashboard-action px-3.5 py-1.5 bg-white hover:bg-[#F0F7FF] text-[#1976D2] text-xs font-bold rounded-xl border border-[#90CAF9] self-start sm:self-auto transition-base"
            >
              Buka Halaman Lengkap &rarr;
            </button>
          </div>

          <div className="space-y-3">
            {perluPerhatian.length === 0 && (
              <p className="text-xs text-[#607D99] py-4 text-center">Tidak ada balita yang memerlukan tinjauan saat ini.</p>
            )}
            {perluPerhatian.map((b) => {
              const ukur = terakhirMap.get(b.id);
              const umur = hitungUmur(b.tanggal_lahir, ukur?.tanggal_ukur);
              return (
                <div
                  key={b.id}
                  className="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-white hover:bg-[#F0F7FF]/50 border border-line rounded-xl transition-base gap-3"
                >
                  <div className="flex items-start gap-3.5">
                    <div className="w-10 h-10 rounded-full bg-amber-100 text-amber-800 font-bold flex items-center justify-center shrink-0 text-sm">
                      {b.nama.charAt(0)}
                    </div>
                    <div className="space-y-1">
                      <div className="flex items-center gap-2 flex-wrap">
                        <span className="font-bold text-[#0D47A1] text-sm">{b.nama}</span>
                        <StatusBadge status={b.status_terakhir} />
                        <span className="text-[11px] text-[#607D99]">· Terakhir ukur: {formatTanggalId(ukur?.tanggal_ukur, true)}</span>
                      </div>
                      <div className="text-xs text-[#607D99]">
                        {umur.teks} · Dusun {b.dusun} · BB: {b.berat_terakhir} kg, TB: {b.tinggi_terakhir} cm
                      </div>
                      {ukur?.catatan && (
                        <div className="text-xs text-[#234A73] flex items-center gap-1.5">
                          <i className="bi bi-info-circle text-[#2196F3]"></i>
                          <span className="italic">{ukur.catatan}</span>
                        </div>
                      )}
                    </div>
                  </div>
                  <button
                    onClick={() => navigate(`/balita/${b.id}`)}
                    className="dashboard-action px-4 py-2 bg-[#2196F3] hover:bg-[#1976D2] text-white text-xs font-bold rounded-xl self-end sm:self-center shrink-0 flex items-center gap-1.5 shadow-xs transition-base"
                  >
                    <i className="bi bi-graph-up"></i>
                    <span>Tinjau Riwayat</span>
                  </button>
                </div>
              );
            })}
          </div>
        </section>

        <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
          <div className="bg-white p-5 rounded-2xl border border-line shadow-xs">
            <span className="text-xs text-[#607D99] font-medium block mb-1">Total Balita Binaan</span>
            <div className="text-3xl font-black text-[#0D47A1] leading-none mb-1">{balita.length}</div>
            <span className="text-[11px] text-[#607D99]">Tersebar di {sebaran.filter((d) => d.normal + d.pemantauan + d.prioritas > 0).length} dusun</span>
          </div>

          <div className="bg-white p-5 rounded-2xl border border-line shadow-xs">
            <span className="text-xs text-[#607D99] font-medium block mb-1">Status Normal</span>
            <div className="text-3xl font-black text-[#2196F3] leading-none mb-1">{normal}</div>
            <span className="text-[11px] text-[#607D99]">Pertumbuhan optimal</span>
          </div>

          <div className="bg-white p-5 rounded-2xl border border-line shadow-xs">
            <span className="text-xs text-[#607D99] font-medium block mb-1">Perlu Pemantauan</span>
            <div className="text-3xl font-black text-amber-500 leading-none mb-1">{pemantauan}</div>
            <span className="text-[11px] text-[#607D99]">BB stagnan / fluktuatif</span>
          </div>

          <div className="bg-white p-5 rounded-2xl border border-line shadow-xs">
            <span className="text-xs text-[#607D99] font-medium block mb-1">Prioritas Intervensi</span>
            <div className="text-3xl font-black text-rose-600 leading-none mb-1">{mendesak}</div>
            <span className="text-[11px] text-[#607D99]">Perlu konseling bidan</span>
          </div>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-5">
          <div className="bg-white p-6 rounded-2xl border border-line shadow-xs">
            <h3 className="font-bold text-sm text-[#0D47A1]">Sebaran Status Gizi per Dusun</h3>
            <p className="text-xs text-[#607D99] mb-6">Kondisi balita berdasarkan hasil penimbangan terakhir</p>
            <div className="h-44 w-full relative">
              <React.Suspense fallback={<div className="h-full animate-pulse rounded-xl bg-slate-50" />}>
                <BarChartView data={sebaranChartData} options={sebaranChartOptions} ariaLabel="Sebaran status gizi per dusun" />
              </React.Suspense>
            </div>
            <div className="flex items-center justify-center gap-5 mt-4 text-[11px] font-semibold text-[#607D99]">
              <div className="flex items-center gap-1.5">
                <span className="w-2.5 h-2.5 rounded-sm bg-[#0D47A1]"></span>
                <span>Normal</span>
              </div>
              <div className="flex items-center gap-1.5">
                <span className="w-2.5 h-2.5 rounded-sm bg-amber-500"></span>
                <span>Pemantauan</span>
              </div>
              <div className="flex items-center gap-1.5">
                <span className="w-2.5 h-2.5 rounded-sm bg-rose-600"></span>
                <span>Prioritas Bidan</span>
              </div>
            </div>
          </div>

          <div className="bg-white p-6 rounded-2xl border border-line shadow-xs">
            <h3 className="font-bold text-sm text-[#0D47A1]">Tren Cakupan Penimbangan Desa</h3>
            <p className="text-xs text-[#607D99] mb-4">Jumlah pengukuran yang tercatat tiap bulan</p>
            <div className="h-48 w-full relative">
              {tren.length === 0 ? (
                <p className="text-center text-ink-500 py-16 text-xs">Belum ada data pengukuran.</p>
              ) : (
                <React.Suspense fallback={<div className="h-full animate-pulse rounded-xl bg-slate-50" />}>
                  <LineChartView data={trendChartData} options={trendChartOptions} ariaLabel="Tren cakupan penimbangan desa" />
                </React.Suspense>
              )}
            </div>
          </div>
        </div>
      </div>
    );
  }

  if (role === 'admin') {
    const kaderCount = petugas.filter((p) => p.role === 'kader').length;
    const bidanCount = petugas.filter((p) => p.role === 'bidan').length;

    return (
      <div className="dashboard-page dashboard-admin space-y-6 max-w-6xl mx-auto">
        <section className="bg-white rounded-2xl border border-line border-l-4 border-l-[#2196F3] p-6 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <div className="flex items-center gap-2 mb-1.5">
              <span className="dashboard-eyebrow">
                Admin Posyandu
              </span>
              <span className="text-xs text-[#607D99]">{labelBulan(bulanBerjalan)}</span>
            </div>
            <h1 className="text-2xl font-black text-[#0D47A1]">Selamat Datang, {user?.nama}</h1>
            <p className="text-xs text-[#607D99] mt-1 font-medium">
              Kelola data balita, petugas, dan jadwal penimbangan dari satu tempat.
            </p>
          </div>
          <div className="flex items-center gap-2 flex-wrap">
            <button
              onClick={() => navigate('/balita')}
              className="dashboard-action px-4 py-2 bg-[#2196F3] hover:bg-[#1976D2] text-white text-xs font-bold rounded-xl flex items-center gap-1.5 shadow-xs transition-base"
            >
              <i className="bi bi-people-fill"></i>
              <span>Data Balita</span>
            </button>
            <button
              onClick={() => navigate('/jadwal')}
              className="dashboard-action px-4 py-2 bg-white hover:bg-slate-50 text-[#1976D2] text-xs font-bold rounded-xl border border-[#90CAF9] flex items-center gap-1.5 transition-base shadow-xs"
            >
              <i className="bi bi-calendar-plus"></i>
              <span>Jadwal</span>
            </button>
            <button
              onClick={() => navigate('/laporan')}
              className="dashboard-action px-4 py-2 bg-white hover:bg-slate-50 text-[#1976D2] text-xs font-bold rounded-xl border border-[#90CAF9] flex items-center gap-1.5 transition-base shadow-xs"
            >
              <i className="bi bi-file-earmark-text"></i>
              <span>Laporan</span>
            </button>
          </div>
        </section>

        <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
          <div className="bg-white p-5 rounded-2xl border border-line shadow-xs flex items-center gap-4">
            <div className="w-12 h-12 rounded-xl bg-[#E3F2FD] text-[#1976D2] flex items-center justify-center text-xl shrink-0">
              <i className="bi bi-heart-fill"></i>
            </div>
            <div>
              <span className="text-xs text-[#607D99] font-medium block">Balita Aktif</span>
              <div className="text-2xl font-black text-[#0D47A1]">{balita.length}</div>
              <span className="text-[11px] text-[#607D99]">Data sasaran utama</span>
            </div>
          </div>

          <div className="bg-white p-5 rounded-2xl border border-line shadow-xs flex items-center gap-4">
            <div className="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
              <i className="bi bi-clipboard-check"></i>
            </div>
            <div>
              <span className="text-xs text-[#607D99] font-medium block">Ukur Bulan Ini</span>
              <div className="text-2xl font-black text-[#0D47A1]">{sudahDitimbang.size}</div>
              <span className="text-[11px] text-[#607D99]">Balita sudah dicatat</span>
            </div>
          </div>

          <div className="bg-white p-5 rounded-2xl border border-line shadow-xs flex items-center gap-4">
            <div className="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
              <i className="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div>
              <span className="text-xs text-[#607D99] font-medium block">Perlu Perhatian</span>
              <div className="text-2xl font-black text-[#0D47A1]">{perluPerhatian.length}</div>
              <span className="text-[11px] text-[#607D99]">Pantau bersama bidan</span>
            </div>
          </div>

          <div className="bg-white p-5 rounded-2xl border border-line shadow-xs flex items-center gap-4">
            <div className="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
              <i className="bi bi-person-badge-fill"></i>
            </div>
            <div>
              <span className="text-xs text-[#607D99] font-medium block">Petugas</span>
              <div className="text-2xl font-black text-[#0D47A1]">{petugas.length}</div>
              <span className="text-[11px] text-[#607D99]">{kaderCount} Kader · {bidanCount} Bidan</span>
            </div>
          </div>
        </div>

        <div>
          <h2 className="text-base font-bold text-[#0D47A1] mb-3">Menu Utama</h2>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            <button
              onClick={() => navigate('/balita')}
              className="dashboard-menu-card bg-white p-5 rounded-2xl border border-line shadow-xs text-left hover:border-[#1976D2] hover:shadow-sm transition-base flex items-center gap-4"
            >
              <div className="w-12 h-12 rounded-xl bg-[#E3F2FD] text-[#1976D2] flex items-center justify-center text-xl shrink-0">
                <i className="bi bi-people-fill"></i>
              </div>
              <div>
                <h3 className="font-bold text-sm text-[#0D47A1]">Data Balita</h3>
                <p className="text-xs text-[#607D99]">Tambah, lihat, dan buka detail balita</p>
              </div>
            </button>

            <button
              onClick={() => navigate('/petugas')}
              className="dashboard-menu-card bg-white p-5 rounded-2xl border border-line shadow-xs text-left hover:border-[#1976D2] hover:shadow-sm transition-base flex items-center gap-4"
            >
              <div className="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
                <i className="bi bi-person-badge-fill"></i>
              </div>
              <div>
                <h3 className="font-bold text-sm text-[#0D47A1]">Kader &amp; Bidan</h3>
                <p className="text-xs text-[#607D99]">Kelola petugas yang melakukan pemantauan</p>
              </div>
            </button>

            <button
              onClick={() => navigate('/jadwal')}
              className="dashboard-menu-card bg-white p-5 rounded-2xl border border-line shadow-xs text-left hover:border-[#1976D2] hover:shadow-sm transition-base flex items-center gap-4"
            >
              <div className="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
                <i className="bi bi-calendar-event-fill"></i>
              </div>
              <div>
                <h3 className="font-bold text-sm text-[#0D47A1]">Jadwal Posyandu</h3>
                <p className="text-xs text-[#607D99]">Atur jadwal penimbangan balita</p>
              </div>
            </button>
          </div>
        </div>

        <div className="bg-white p-6 rounded-2xl border border-line shadow-xs">
          <h2 className="font-bold text-sm text-[#0D47A1] mb-1">Alur Sistem</h2>
          <p className="text-xs text-[#607D99] mb-5">Fokus aplikasi hanya pada pertumbuhan dan pemantauan balita.</p>
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            {[
              { num: '1', title: 'Data Balita', desc: 'Kader mendaftarkan balita', icon: 'bi-person-plus-fill', color: 'text-[#2196F3]' },
              { num: '2', title: 'Pengukuran', desc: 'BB, TB, dan usia dicatat', icon: 'bi-clipboard-plus', color: 'text-[#2196F3]' },
              { num: '3', title: 'Status', desc: 'Hasil dikelompokkan', icon: 'bi-graph-up-arrow', color: 'text-amber-500' },
              { num: '4', title: 'Pemantauan', desc: 'Bidan melihat prioritas', icon: 'bi-heart-fill', color: 'text-emerald-600' }
            ].map((step) => (
              <div key={step.num} className="p-4 bg-[#F0F7FF]/50 rounded-xl border border-line text-center">
                <div className={`text-2xl ${step.color} mb-2`}>
                  <i className={`bi ${step.icon}`}></i>
                </div>
                <div className="font-bold text-xs text-[#0D47A1] mb-0.5">{step.num}. {step.title}</div>
                <div className="text-[11px] text-[#607D99]">{step.desc}</div>
              </div>
            ))}
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="dashboard-page dashboard-kader space-y-6 max-w-6xl mx-auto">
      <div>
        <h1 className="text-2xl font-black text-[#0D47A1]">Selamat datang, {user?.nama}</h1>
        <p className="text-xs text-[#607D99] mt-1 font-medium">
          {user?.dusun_binaan ? `Dusun ${user.dusun_binaan}` : 'Desa Sukatani'} &bull; {labelBulan(bulanBerjalan)}
        </p>
      </div>

      <div className="bg-[#0D47A1] text-white rounded-2xl p-6 shadow-xs">
        <div className="flex items-center gap-2 mb-2 text-blue-200 text-xs font-semibold">
          <i className="bi bi-calendar-event"></i>
          <span>Jadwal Penimbangan {labelBulan(bulanBerjalan)}</span>
        </div>
        <h2 className="text-xl sm:text-2xl font-black text-white mb-1.5">
          {belumDitimbang.length} balita belum ditimbang bulan ini.
        </h2>
        <p className="text-blue-100 text-xs max-w-xl mb-5 opacity-90">
          {sudahDitimbang.size} dari {balita.length} balita binaan sudah memiliki catatan penimbangan bulan {labelBulan(bulanBerjalan)}.
        </p>
        <div className="flex items-center gap-3 flex-wrap">
          <button
            onClick={() => navigate('/pengukuran')}
            className="dashboard-action px-4 py-2 bg-[#2196F3] hover:bg-[#1976D2] text-white text-xs font-bold rounded-xl flex items-center gap-2 shadow-xs transition-base"
          >
            <i className="bi bi-clipboard-plus"></i>
            <span>Mulai Pengukuran</span>
          </button>
          <button
            onClick={() => setIsModalOpen(true)}
            className="dashboard-action px-4 py-2 bg-white/15 hover:bg-white/25 text-white text-xs font-bold rounded-xl border border-white/60 flex items-center gap-2 transition-base"
          >
            <i className="bi bi-person-plus"></i>
            <span>Tambah Balita</span>
          </button>
        </div>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div className="bg-white p-5 rounded-2xl border border-line shadow-xs">
          <span className="text-xs text-[#607D99] font-medium block mb-1">Total Anak Binaan</span>
          <div className="text-3xl font-black text-[#0D47A1] leading-none mb-1">{balita.length}</div>
          <span className="text-[11px] text-[#607D99]">Balita terdaftar aktif</span>
        </div>

        <div className="bg-white p-5 rounded-2xl border border-line shadow-xs">
          <span className="text-xs text-[#607D99] font-medium block mb-1">Sudah Ditimbang</span>
          <div className="text-3xl font-black text-[#2196F3] leading-none mb-1">{sudahDitimbang.size}</div>
          <span className="text-[11px] text-[#607D99]">Bulan {labelBulan(bulanBerjalan)}</span>
        </div>

        <div className="bg-white p-5 rounded-2xl border border-line shadow-xs">
          <span className="text-xs text-[#607D99] font-medium block mb-1">Perlu Diperhatikan</span>
          <div className="text-3xl font-black text-amber-500 leading-none mb-1">{perluPerhatian.length}</div>
          <span className="text-[11px] text-[#607D99]">Perlu pemantauan gizi</span>
        </div>
      </div>

      <section className="bg-white rounded-2xl border border-line p-6 shadow-xs">
        <div className="flex flex-col sm:flex-row sm:items-center justify-between pb-3 mb-4 border-b border-line gap-2">
          <div>
            <h2 className="text-base font-bold text-[#0D47A1]">Belum Ditimbang Bulan Ini</h2>
            <p className="text-xs text-[#607D99]">
              Balita yang belum memiliki catatan pengukuran pada bulan berjalan
            </p>
          </div>
          <button
            onClick={() => navigate('/balita')}
            className="dashboard-action px-3.5 py-1.5 bg-white hover:bg-[#F0F7FF] text-[#1976D2] text-xs font-bold rounded-xl border border-[#90CAF9] transition-base self-start sm:self-auto"
          >
            Lihat Semua Balita
          </button>
        </div>

        <div className="space-y-3">
          {belumDitimbang.length === 0 && (
            <p className="text-xs text-[#607D99] py-4 text-center">Semua balita binaan sudah ditimbang bulan ini. Kerja bagus!</p>
          )}
          {belumDitimbang.map((b) => {
            const umur = hitungUmur(b.tanggal_lahir);
            return (
              <div
                key={b.id}
                className="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-white hover:bg-[#F0F7FF]/50 border border-line rounded-xl transition-base gap-3"
              >
                <div className="flex items-start gap-3.5">
                  <div className="w-10 h-10 rounded-full bg-[#E3F2FD] text-[#1976D2] font-bold flex items-center justify-center shrink-0 text-sm">
                    {b.nama.charAt(0)}
                  </div>
                  <div>
                    <div className="font-bold text-[#0D47A1] text-sm mb-0.5">{b.nama}</div>
                    <div className="text-xs text-[#607D99]">
                      {umur.teks} &bull; Dusun {b.dusun} &bull; Orang Tua: {b.nama_ortu}
                    </div>
                    <div className="text-[11px] text-[#607D99] mt-0.5">
                      Terakhir ditimbang:{' '}
                      <strong className="text-[#234A73]">
                        {b.tanggal_ukur_terakhir
                          ? `${formatTanggalId(b.tanggal_ukur_terakhir, true)} (${b.berat_terakhir} kg · ${b.tinggi_terakhir} cm)`
                          : 'Belum pernah'}
                      </strong>
                    </div>
                  </div>
                </div>
                <button
                  onClick={() => navigate(`/pengukuran?anak=${b.id}`)}
                  className="dashboard-action px-4 py-2 bg-[#2196F3] hover:bg-[#1976D2] text-white text-xs font-bold rounded-xl flex items-center gap-1.5 self-end sm:self-center shrink-0 shadow-xs transition-base"
                >
                  <i className="bi bi-clipboard-plus"></i>
                  <span>Catat</span>
                </button>
              </div>
            );
          })}
        </div>
      </section>

      <section className="bg-white rounded-2xl border border-line p-6 shadow-xs">
        <div className="pb-3 mb-4 border-b border-line">
          <h2 className="text-base font-bold text-[#0D47A1]">Perlu Diperhatikan</h2>
          <p className="text-xs text-[#607D99]">
            Balita dengan catatan pemantauan atau perlambatan kenaikan berat badan
          </p>
        </div>

        <div className="space-y-3">
          {perluPerhatian.length === 0 && (
            <p className="text-xs text-[#607D99] py-4 text-center">Tidak ada balita dalam status perlu perhatian.</p>
          )}
          {perluPerhatian.map((b) => {
            const umur = hitungUmur(b.tanggal_lahir);
            const ukur = terakhirMap.get(b.id);
            return (
              <div
                key={b.id}
                className="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-white hover:bg-[#F0F7FF]/50 border border-line rounded-xl transition-base gap-3"
              >
                <div className="flex items-start gap-3.5">
                  <div className="w-10 h-10 rounded-full bg-[#FFF5DF] text-[#C58A32] font-bold flex items-center justify-center shrink-0 text-sm">
                    {b.nama.charAt(0)}
                  </div>
                  <div>
                    <div className="flex items-center gap-2 flex-wrap mb-0.5">
                      <span className="font-bold text-[#0D47A1] text-sm">{b.nama}</span>
                      <StatusBadge status={b.status_terakhir} />
                    </div>
                    <div className="text-xs text-[#607D99]">
                      {umur.teks} &bull; Dusun {b.dusun}
                    </div>
                    {ukur?.catatan && (
                      <div className="text-xs text-[#234A73] mt-0.5 italic">
                        Catatan: {ukur.catatan}
                      </div>
                    )}
                  </div>
                </div>
                <button
                  onClick={() => navigate(`/balita/${b.id}`)}
                  className="dashboard-action px-3.5 py-1.5 bg-white hover:bg-[#F0F7FF] text-[#1976D2] text-xs font-bold rounded-xl border border-[#90CAF9] flex items-center gap-1.5 self-end sm:self-center shrink-0 transition-base"
                >
                  <i className="bi bi-graph-up"></i>
                  <span>Lihat Riwayat</span>
                </button>
              </div>
            );
          })}
        </div>
      </section>

      <ChildModal
        isOpen={isModalOpen}
        onClose={() => setIsModalOpen(false)}
        onSave={handleAddChild}
      />
    </div>
  );
};
