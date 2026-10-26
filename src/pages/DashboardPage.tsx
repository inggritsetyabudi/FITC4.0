import React, { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import { balitaService } from '../services/api';
import { ChildModal } from '../components/ui/ChildModal';
import { StatusBadge } from '../components/ui/StatusBadge';
import type { StatusGizi } from '../types';

export const DashboardPage: React.FC<{ onNavigate: (page: string, params?: any) => void }> = ({ onNavigate }) => {
  const { user } = useAuth();
  const role = user?.role || 'kader';

  const [isModalOpen, setIsModalOpen] = useState<boolean>(false);

  const handleAddChild = async (data: any) => {
    await balitaService.create(data);
    setIsModalOpen(false);
  };

  if (role === 'bidan') {
    return (
      <div className={`dashboard-page dashboard-${role} space-y-6 max-w-6xl mx-auto`}>
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <h1 className="text-2xl font-black text-[#0D47A1]">Panel Evaluasi Bidan Desa</h1>
            <p className="text-xs text-[#607D99] mt-1 font-medium">
              Wilayah Kerja Desa Sukatani &bull; 24 September 2026
            </p>
          </div>
          <div className="flex items-center gap-2.5">
            <button
              onClick={() => onNavigate('rekap')}
              className="dashboard-action px-4 py-2 bg-white hover:bg-slate-50 text-[#1976D2] text-xs font-bold rounded-xl border border-[#90CAF9] flex items-center gap-1.5 transition-base shadow-xs"
            >
              <i className="bi bi-journal-text text-sm"></i>
              <span>Rekap Pengukuran</span>
            </button>
            <button
              onClick={() => onNavigate('prioritas')}
              className="dashboard-action px-4 py-2 bg-[#2196F3] hover:bg-[#1976D2] text-white text-xs font-bold rounded-xl flex items-center gap-1.5 shadow-xs transition-base"
            >
              <i className="bi bi-exclamation-triangle-fill text-sm"></i>
              <span>Semua Prioritas (3)</span>
            </button>
          </div>
        </div>

        <section className="bg-white rounded-2xl border-2 border-[#FFC107] p-6 shadow-xs">
          <div className="flex flex-col sm:flex-row sm:items-center justify-between pb-3 mb-4 border-b border-line gap-2">
            <div>
              <span className="bg-amber-100 text-amber-900 text-[10px] font-bold px-2.5 py-0.5 rounded-full inline-block mb-1.5">
                ★ Tinjauan Utama
              </span>
              <h2 className="text-base font-bold text-[#0D47A1]">Daftar Balita Perlu Ditinjau Bidan</h2>
              <p className="text-xs text-[#607D99]">
                Balita dengan perlambatan kenaikan berat badan atau status prioritas dari kunjungan penimbangan terakhir.
              </p>
            </div>
            <button
              onClick={() => onNavigate('prioritas')}
              className="dashboard-action px-3.5 py-1.5 bg-white hover:bg-[#F0F7FF] text-[#1976D2] text-xs font-bold rounded-xl border border-[#90CAF9] self-start sm:self-auto transition-base"
            >
              Buka Halaman Lengkap &rarr;
            </button>
          </div>

          <div className="space-y-3">
            {[
              {
                id: 2,
                nama: 'Farel Aditya',
                status: 'prioritas',
                tgl: '17 Agu 2026',
                meta: '2 tahun 10 bulan · Dusun Boharan · BB: 11.1 kg, TB: 86.4 cm',
                catatan: 'Kenaikan berat badan belum sesuai target.',
                avatarBg: 'bg-rose-100 text-rose-700',
                initial: 'F'
              },
              {
                id: 1,
                nama: 'Dinda Aulia',
                status: 'pemantauan',
                tgl: '18 Agu 2026',
                meta: '2 tahun 4 bulan · Dusun Gamping Wetan · BB: 10.8 kg, TB: 83.1 cm',
                catatan: 'Nafsu makan agak berkurang sejak 2 minggu lalu.',
                avatarBg: 'bg-amber-100 text-amber-800',
                initial: 'D'
              },
              {
                id: 5,
                nama: 'Almira Safitri',
                status: 'pemantauan',
                tgl: '12 Agu 2026',
                meta: '3 tahun 1 bulan · Dusun Gamping Wetan · BB: 12 kg, TB: 88 cm',
                catatan: 'Perlu pemantauan asupan makan.',
                avatarBg: 'bg-amber-100 text-amber-800',
                initial: 'A'
              }
            ].map((item) => (
              <div
                key={item.id}
                className="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-white hover:bg-[#F0F7FF]/50 border border-line rounded-xl transition-base gap-3"
              >
                <div className="flex items-start gap-3.5">
                  <div className={`w-10 h-10 rounded-full font-bold flex items-center justify-center shrink-0 text-sm ${item.avatarBg}`}>
                    {item.initial}
                  </div>
                  <div className="space-y-1">
                    <div className="flex items-center gap-2 flex-wrap">
                      <span className="font-bold text-[#0D47A1] text-sm">{item.nama}</span>
                      <StatusBadge status={item.status as StatusGizi} />
                      <span className="text-[11px] text-[#607D99]">· Terakhir ukur: {item.tgl}</span>
                    </div>
                    <div className="text-xs text-[#607D99]">
                      {item.meta}
                    </div>
                    <div className="text-xs text-[#234A73] flex items-center gap-1.5">
                      <i className="bi bi-info-circle text-[#2196F3]"></i>
                      <span className="italic">{item.catatan}</span>
                    </div>
                  </div>
                </div>
                <button
                  onClick={() => onNavigate('detail_balita', { id: item.id })}
                  className="dashboard-action px-4 py-2 bg-[#2196F3] hover:bg-[#1976D2] text-white text-xs font-bold rounded-xl self-end sm:self-center shrink-0 flex items-center gap-1.5 shadow-xs transition-base"
                >
                  <i className="bi bi-graph-up"></i>
                  <span>Tinjau Riwayat</span>
                </button>
              </div>
            ))}
          </div>
        </section>

        <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
          <div className="bg-white p-5 rounded-2xl border border-line shadow-xs">
            <span className="text-xs text-[#607D99] font-medium block mb-1">Total Balita Binaan</span>
            <div className="text-3xl font-black text-[#0D47A1] leading-none mb-1">16</div>
            <span className="text-[11px] text-[#607D99]">Tersebar di 4 dusun</span>
          </div>

          <div className="bg-white p-5 rounded-2xl border border-line shadow-xs">
            <span className="text-xs text-[#607D99] font-medium block mb-1">Status Normal</span>
            <div className="text-3xl font-black text-[#2196F3] leading-none mb-1">3</div>
            <span className="text-[11px] text-[#607D99]">Pertumbuhan optimal</span>
          </div>

          <div className="bg-white p-5 rounded-2xl border border-line shadow-xs">
            <span className="text-xs text-[#607D99] font-medium block mb-1">Perlu Pemantauan</span>
            <div className="text-3xl font-black text-amber-500 leading-none mb-1">2</div>
            <span className="text-[11px] text-[#607D99]">BB stagnan / fluktuatif</span>
          </div>

          <div className="bg-white p-5 rounded-2xl border border-line shadow-xs">
            <span className="text-xs text-[#607D99] font-medium block mb-1">Prioritas Intervensi</span>
            <div className="text-3xl font-black text-rose-600 leading-none mb-1">1</div>
            <span className="text-[11px] text-[#607D99]">Perlu konseling bidan</span>
          </div>
        </div>

        <div className="grid grid-cols-1 lg:grid-cols-2 gap-5">
          <div className="bg-white p-6 rounded-2xl border border-line shadow-xs">
            <h3 className="font-bold text-sm text-[#0D47A1]">Sebaran Status Gizi per Dusun</h3>
            <p className="text-xs text-[#607D99] mb-6">Kondisi balita berdasarkan hasil penimbangan terakhir</p>
            <div className="h-44 flex items-end justify-between px-6 pb-4 border-b border-line gap-4">
              {[
                { name: 'Gamping', normal: 54, pemantauan: 39, prioritas: 0 },
                { name: 'Boharan', normal: 0, pemantauan: 0, prioritas: 70 },
                { name: 'Kemangsen', normal: 64, pemantauan: 0, prioritas: 0 },
                { name: 'Bendomungal', normal: 58, pemantauan: 0, prioritas: 0 }
              ].map((d) => (
                <div key={d.name} className="flex flex-col items-center gap-2 flex-1">
                  <div className="w-full flex items-end justify-center gap-1.5 h-32">
                    {d.normal > 0 && <div style={{ height: `${d.normal}%` }} className="w-3 bg-[#0D47A1] rounded-t-sm"></div>}
                    {d.pemantauan > 0 && <div style={{ height: `${d.pemantauan}%` }} className="w-3 bg-amber-500 rounded-t-sm"></div>}
                    {d.prioritas > 0 && <div style={{ height: `${d.prioritas}%` }} className="w-3 bg-rose-600 rounded-t-sm"></div>}
                  </div>
                  <span className="text-[11px] text-[#607D99] font-medium">{d.name}</span>
                </div>
              ))}
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
            <p className="text-xs text-[#607D99] mb-4">Kehadiran balita di posyandu 6 bulan terakhir</p>
            <div className="h-48 w-full">
              <svg viewBox="0 0 450 160" className="w-full h-full select-none">
                <defs>
                  <linearGradient id="chartGrad" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stopColor="#0D47A1" stopOpacity="0.09" />
                    <stop offset="100%" stopColor="#0D47A1" stopOpacity="0.09" />
                  </linearGradient>
                </defs>
                <path
                  d="M 47,120 L 120,78 L 193,96 L 267,51 L 340,68 L 416,40 L 416,148 L 47,148 Z"
                  fill="url(#chartGrad)"
                />
                <path
                  d="M 47,120 L 120,78 L 193,96 L 267,51 L 340,68 L 416,40"
                  fill="none"
                  stroke="#1976D2"
                  strokeWidth="3"
                  strokeLinecap="round"
                />
                {[
                  { cx: 47, cy: 120 },
                  { cx: 120, cy: 78 },
                  { cx: 193, cy: 96 },
                  { cx: 267, cy: 51 },
                  { cx: 340, cy: 68 },
                  { cx: 416, cy: 40 }
                ].map((p, i) => (
                  <circle key={i} cx={p.cx} cy={p.cy} r="4" fill="#0D47A1" />
                ))}
                {['Mar 2026', 'Apr 2026', 'Mei 2026', 'Jun 2026', 'Jul 2026', 'Agu 2026'].map((m, i) => (
                  <text key={m} x={47 + i * 74} y={155} fontSize="10" fill="#607D99" textAnchor="middle">
                    {m}
                  </text>
                ))}
              </svg>
            </div>
          </div>
        </div>
      </div>
    );
  }

  if (role === 'admin') {
    return (
      <div className={`dashboard-page dashboard-${role} space-y-6 max-w-6xl mx-auto`}>
        <section className="bg-white rounded-2xl border border-line border-l-4 border-l-[#2196F3] p-6 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <div className="flex items-center gap-2 mb-1.5">
              <span className="bg-[#E3F2FD] text-[#0D47A1] text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">
                Admin Posyandu
              </span>
              <span className="text-xs text-[#607D99]">24 September 2026</span>
            </div>
            <h1 className="text-2xl font-black text-[#0D47A1]">Selamat Datang, {user?.nama}</h1>
            <p className="text-xs text-[#607D99] mt-1 font-medium">
              Kelola data balita, petugas, dan jadwal penimbangan dari satu tempat.
            </p>
          </div>
          <div className="flex items-center gap-2 flex-wrap">
            <button
              onClick={() => onNavigate('balita')}
              className="dashboard-action px-4 py-2 bg-[#2196F3] hover:bg-[#1976D2] text-white text-xs font-bold rounded-xl flex items-center gap-1.5 shadow-xs transition-base"
            >
              <i className="bi bi-people-fill"></i>
              <span>Data Balita</span>
            </button>
            <button
              onClick={() => onNavigate('jadwal')}
              className="dashboard-action px-4 py-2 bg-white hover:bg-slate-50 text-[#1976D2] text-xs font-bold rounded-xl border border-[#90CAF9] flex items-center gap-1.5 transition-base shadow-xs"
            >
              <i className="bi bi-calendar-plus"></i>
              <span>Jadwal</span>
            </button>
            <button
              onClick={() => onNavigate('laporan')}
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
              <div className="text-2xl font-black text-[#0D47A1]">16</div>
              <span className="text-[11px] text-[#607D99]">Data sasaran utama</span>
            </div>
          </div>

          <div className="bg-white p-5 rounded-2xl border border-line shadow-xs flex items-center gap-4">
            <div className="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
              <i className="bi bi-clipboard-check"></i>
            </div>
            <div>
              <span className="text-xs text-[#607D99] font-medium block">Ukur Bulan Ini</span>
              <div className="text-2xl font-black text-[#0D47A1]">13</div>
              <span className="text-[11px] text-[#607D99]">Balita sudah dicatat</span>
            </div>
          </div>

          <div className="bg-white p-5 rounded-2xl border border-line shadow-xs flex items-center gap-4">
            <div className="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl shrink-0">
              <i className="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div>
              <span className="text-xs text-[#607D99] font-medium block">Perlu Perhatian</span>
              <div className="text-2xl font-black text-[#0D47A1]">3</div>
              <span className="text-[11px] text-[#607D99]">Pantau bersama bidan</span>
            </div>
          </div>

          <div className="bg-white p-5 rounded-2xl border border-line shadow-xs flex items-center gap-4">
            <div className="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl shrink-0">
              <i className="bi bi-person-badge-fill"></i>
            </div>
            <div>
              <span className="text-xs text-[#607D99] font-medium block">Petugas</span>
              <div className="text-2xl font-black text-[#0D47A1]">10</div>
              <span className="text-[11px] text-[#607D99]">8 Kader · 2 Bidan</span>
            </div>
          </div>
        </div>

        <div>
          <h2 className="text-base font-bold text-[#0D47A1] mb-3">Menu Utama</h2>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
            <button
              onClick={() => onNavigate('balita')}
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
              onClick={() => onNavigate('petugas')}
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
              onClick={() => onNavigate('jadwal')}
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
    <div className={`dashboard-page dashboard-${role} space-y-6 max-w-6xl mx-auto`}>
      <div>
        <h1 className="text-2xl font-black text-[#0D47A1]">Selamat datang, {user?.nama}</h1>
        <p className="text-xs text-[#607D99] mt-1 font-medium">
          Dusun Gamping Wetan &bull; 24 September 2026
        </p>
      </div>

      <div className="bg-[#0D47A1] text-white rounded-2xl p-6 shadow-xs">
        <div className="flex items-center gap-2 mb-2 text-blue-200 text-xs font-semibold">
          <i className="bi bi-calendar-event"></i>
          <span>Jadwal Penimbangan Agustus 2026</span>
        </div>
        <h2 className="text-xl sm:text-2xl font-black text-white mb-1.5">
          2 balita belum ditimbang bulan ini.
        </h2>
        <p className="text-blue-100 text-xs max-w-xl mb-5 opacity-90">
          3 dari 5 balita binaan sudah memiliki catatan penimbangan bulan ini.
        </p>
        <div className="flex items-center gap-3 flex-wrap">
          <button
            onClick={() => onNavigate('pengukuran')}
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
          <div className="text-3xl font-black text-[#0D47A1] leading-none mb-1">5</div>
          <span className="text-[11px] text-[#607D99]">Balita terdaftar aktif</span>
        </div>

        <div className="bg-white p-5 rounded-2xl border border-line shadow-xs">
          <span className="text-xs text-[#607D99] font-medium block mb-1">Sudah Ditimbang</span>
          <div className="text-3xl font-black text-[#2196F3] leading-none mb-1">3</div>
          <span className="text-[11px] text-[#607D99]">Bulan Agustus 2026</span>
        </div>

        <div className="bg-white p-5 rounded-2xl border border-line shadow-xs">
          <span className="text-xs text-[#607D99] font-medium block mb-1">Perlu Diperhatikan</span>
          <div className="text-3xl font-black text-amber-500 leading-none mb-1">2</div>
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
            onClick={() => onNavigate('balita')}
            className="dashboard-action px-3.5 py-1.5 bg-white hover:bg-[#F0F7FF] text-[#1976D2] text-xs font-bold rounded-xl border border-[#90CAF9] transition-base self-start sm:self-auto"
          >
            Lihat Semua Balita
          </button>
        </div>

        <div className="space-y-3">
          {[
            {
              id: 4,
              nama: 'Raka Pratama',
              usia: '2 tahun 2 bulan',
              dusun: 'Bendomungal',
              ortu: 'Andi Saputra',
              terakhir: '16 Agu 2026 (9.7 kg · 79.8 cm)',
              initial: 'R'
            },
            {
              id: 5,
              nama: 'Almira Safitri',
              usia: '3 tahun 1 bulan',
              dusun: 'Gamping Wetan',
              ortu: 'Siti Nurhayati',
              terakhir: '12 Agu 2026 (12 kg · 88 cm)',
              initial: 'A'
            }
          ].map((b) => (
            <div
              key={b.id}
              className="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-white hover:bg-[#F0F7FF]/50 border border-line rounded-xl transition-base gap-3"
            >
              <div className="flex items-start gap-3.5">
                <div className="w-10 h-10 rounded-full bg-[#E3F2FD] text-[#1976D2] font-bold flex items-center justify-center shrink-0 text-sm">
                  {b.initial}
                </div>
                <div>
                  <div className="font-bold text-[#0D47A1] text-sm mb-0.5">{b.nama}</div>
                  <div className="text-xs text-[#607D99]">
                    {b.usia} &bull; Dusun {b.dusun} &bull; Orang Tua: {b.ortu}
                  </div>
                  <div className="text-[11px] text-[#607D99] mt-0.5">
                    Terakhir ditimbang: <strong className="text-[#234A73]">{b.terakhir}</strong>
                  </div>
                </div>
              </div>
              <button
                onClick={() => onNavigate('pengukuran', { preselectedId: b.id })}
                className="dashboard-action px-4 py-2 bg-[#2196F3] hover:bg-[#1976D2] text-white text-xs font-bold rounded-xl flex items-center gap-1.5 self-end sm:self-center shrink-0 shadow-xs transition-base"
              >
                <i className="bi bi-clipboard-plus"></i>
                <span>Catat</span>
              </button>
            </div>
          ))}
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
          {[
            {
              id: 1,
              nama: 'Dinda Aulia',
              status: 'pemantauan' as const,
              usia: '2 tahun 4 bulan',
              dusun: 'Gamping Wetan',
              catatan: 'Nafsu makan agak berkurang sejak 2 minggu lalu.',
              initial: 'D',
              avatarBg: 'bg-amber-100 text-amber-800'
            },
            {
              id: 2,
              nama: 'Farel Aditya',
              status: 'prioritas' as const,
              usia: '2 tahun 10 bulan',
              dusun: 'Boharan',
              catatan: 'Kenaikan berat badan belum sesuai target.',
              initial: 'F',
              avatarBg: 'bg-[#FFF5DF] text-[#C58A32]'
            }
          ].map((b) => (
            <div
              key={b.id}
              className="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-white hover:bg-[#F0F7FF]/50 border border-line rounded-xl transition-base gap-3"
            >
              <div className="flex items-start gap-3.5">
                <div className={`w-10 h-10 rounded-full font-bold flex items-center justify-center shrink-0 text-sm ${b.avatarBg}`}>
                  {b.initial}
                </div>
                <div>
                  <div className="flex items-center gap-2 flex-wrap mb-0.5">
                    <span className="font-bold text-[#0D47A1] text-sm">{b.nama}</span>
                    <StatusBadge status={b.status} />
                  </div>
                  <div className="text-xs text-[#607D99]">
                    {b.usia} &bull; Dusun {b.dusun}
                  </div>
                  <div className="text-xs text-[#234A73] mt-0.5 italic">
                    Catatan: {b.catatan}
                  </div>
                </div>
              </div>
              <button
                onClick={() => onNavigate('detail_balita', { id: b.id })}
                className="dashboard-action px-3.5 py-1.5 bg-white hover:bg-[#F0F7FF] text-[#1976D2] text-xs font-bold rounded-xl border border-[#90CAF9] flex items-center gap-1.5 self-end sm:self-center shrink-0 transition-base"
              >
                <i className="bi bi-graph-up"></i>
                <span>Lihat Riwayat</span>
              </button>
            </div>
          ))}
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
