import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import type { Jadwal } from '../types';
import { useAsyncData } from '../hooks/useAsyncData';
import { beritaService, jadwalService } from '../services/api';
import { formatTanggalId } from '../utils/helpers';

const FAQ_LIST = [
  {
    q: 'Apa itu Posyandu Pintar?',
    a: 'Posyandu Pintar adalah sistem informasi pencatatan tumbuh kembang balita yang membantu kader mencatat hasil timbang bulanan dan membantu bidan memantau perkembangan anak secara digital, tanpa buku catatan fisik.'
  },
  {
    q: 'Siapa saja yang boleh menggunakan aplikasi ini?',
    a: 'Aplikasi ini dipakai oleh tiga peran: Kader untuk mencatat timbangan, Bidan Desa untuk memantau dan menindaklanjuti balita prioritas, dan Admin Desa untuk mengelola petugas, jadwal, serta laporan.'
  },
  {
    q: 'Bagaimana cara mencatat timbangan balita?',
    a: 'Masuk (login) sebagai kader, buka menu "Catat Pengukuran", lalu pilih anak, tanggal timbang, berat, dan tinggi badan. Usia anak dan evaluasi status gizi akan terhitung otomatis.'
  },
  {
    q: 'Apakah data balita saya aman?',
    a: 'Ya. Data tersimpan di basis data desa dan hanya dapat diakses oleh petugas yang login. Setiap peran memiliki kewenangan berbeda sehingga kader tidak dapat mengelola data milik peran lain.'
  },
  {
    q: 'Di mana saya bisa melihat jadwal posyandu?',
    a: 'Jadwal pelayanan tersedia pada bagian "Jadwal Posyandu" di halaman publik ini, dan dapat dikelola oleh Admin Desa melalui dasbor.'
  }
];

export const LandingPage: React.FC = () => {
  const navigate = useNavigate();
  const [faqOpen, setFaqOpen] = useState<number | null>(0);
  const [kmsJk, setKmsJk] = useState<'L' | 'P'>('L');
  const [kmsUsia, setKmsUsia] = useState<number>(24);
  const [kmsBerat, setKmsBerat] = useState<number>(11.5);
  const [kmsTinggi, setKmsTinggi] = useState<number>(86.0);
  const { data: daftarBerita = [] } = useAsyncData(() => beritaService.getAll('berita'));
  const { data: daftarBlog = [] } = useAsyncData(() => beritaService.getAll('blog'));
  const { data: jadwalList = [], loading: jadwalLoading, error: jadwalError } = useAsyncData(() => jadwalService.getAll());

  const calculateKms = () => {
    let bbIdealMin = (kmsUsia * 0.2) + 7.5;
    let bbIdealMax = (kmsUsia * 0.3) + 11.0;
    if (kmsUsia < 12) {
      bbIdealMin = 3.2 + (kmsUsia * 0.5);
      bbIdealMax = 4.2 + (kmsUsia * 0.7);
    }

    if (kmsBerat < bbIdealMin * 0.85) {
      return {
        status: 'Prioritas Bidan',
        icon: 'bi-exclamation-triangle-fill',
        penjelasan: `Berat badan ${kmsBerat} kg berada di bawah batas kurva penimbangan standar untuk balita usia ${kmsUsia} bulan.`,
        saran: 'Sangat disarankan segera berkonsultasi dengan Bidan Desa atau petugas Puskesmas saat kunjungan Posyandu terdekat.'
      };
    } else if (kmsBerat < bbIdealMin || kmsBerat > bbIdealMax * 1.2) {
      return {
        status: 'Perlu Pemantauan',
        icon: 'bi-exclamation-circle-fill',
        penjelasan: `Berat badan ${kmsBerat} kg mendekati garis batas pemantauan pertumbuhan usia ${kmsUsia} bulan.`,
        saran: 'Evaluasi porsi makan harian dan pastikan asupan protein hewani terpenuhi. Lakukan penimbangan ulang bulan depan.'
      };
    }
    return {
      status: 'Normal & Ideal',
      icon: 'bi-check-circle-fill',
      penjelasan: `Berat ${kmsBerat} kg dan tinggi ${kmsTinggi} cm berada dalam kisaran pertumbuhan yang wajar untuk balita usia ${kmsUsia} bulan.`,
      saran: 'Pertahankan pemberian makanan bergizi seimbang (protein hewani, sayur, buah) dan timbang rutin di Posyandu setiap bulan.'
    };
  };

  const [kmsResult, setKmsResult] = useState(() => ({
    ...calculateKms(), usia: kmsUsia, berat: kmsBerat, tinggi: kmsTinggi
  }));

  const lokasiPelayanan = [...new Map(
    jadwalList.map((jadwal) => [`${jadwal.dusun}|${jadwal.lokasi}`, jadwal])
  ).values()];

  return (
    <div className="landing-page font-sans text-ink-900 bg-white">
      <section
        id="beranda"
        className="relative bg-cover bg-center overflow-hidden min-h-[580px] lg:min-h-[640px] flex items-center"
        style={{
          backgroundImage: "url('/images/hero_landing.png')"
        }}
      >
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 relative z-10 w-full">
          <div className="hero-copy max-w-2xl text-left">
            <h1 className="text-3xl sm:text-5xl lg:text-5xl font-black text-white leading-tight mb-5">
              <span className="hero-title-line">Tumbuh mereka berubah setiap</span>{' '}
              <span className="hero-title-line">bulan. Catatannya jangan sampai</span>{' '}
              <span className="hero-title-line">tertinggal.</span>
            </h1>

            <p className="text-sm sm:text-base text-slate-200 leading-normal max-w-xl">
              Posyandu Pintar membantu kader mencatat hasil timbang bulanan dan membantu bidan memantau perkembangan balita dengan lebih rapi.
            </p>

            <div className="hero-actions flex flex-col sm:flex-row items-start sm:items-center gap-3">
              <a
                href="#tentang"
                className="group px-6 py-3.5 bg-[#2196F3] hover:bg-white hover:text-[#0D47A1] text-white font-bold text-sm rounded-xl flex items-center gap-2.5 shadow-lg transition-base"
              >
                <span>Pelajari Tentang Kami</span>
                <i className="bi bi-arrow-right transition-transform group-hover:translate-x-1"></i>
              </a>
              <a
                href="#jadwal"
                className="px-6 py-3.5 bg-white/10 hover:bg-white/25 text-white font-bold text-sm rounded-xl border border-white/30 flex items-center gap-2.5 backdrop-blur-sm transition-base"
              >
                <i className="bi bi-calendar-event"></i>
                <span>Lihat Jadwal</span>
              </a>
            </div>
          </div>
        </div>
      </section>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 sm:-mt-12 relative z-20">
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
          <div className="bg-white p-6 rounded-2xl border border-line shadow-md flex items-center gap-4">
            <div className="w-12 h-12 rounded-xl bg-blue-50 text-[#2196F3] flex items-center justify-center text-2xl shrink-0">
              <i className="bi bi-phone"></i>
            </div>
            <div>
              <h3 className="font-extrabold text-[#0D47A1] text-sm sm:text-base mb-1">
                Catat Timbangan Langsung di HP
              </h3>
              <p className="text-xs text-ink-600 leading-relaxed">
                Kader memasukkan angka timbangan balita secara praktis. Umur dan selisih berat bulan lalu langsung terhitung otomatis.
              </p>
            </div>
          </div>

          <div className="bg-white p-6 rounded-2xl border border-line shadow-md flex items-center gap-4">
            <div className="w-12 h-12 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-2xl shrink-0">
              <i className="bi bi-graph-up-arrow"></i>
            </div>
            <div>
              <h3 className="font-extrabold text-[#0D47A1] text-sm sm:text-base mb-1">
                Pantau Grafik &amp; Risiko Gizi
              </h3>
              <p className="text-xs text-ink-600 leading-relaxed">
                Kenaikan berat badan dipantau berkala, memudahkan bidan desa mendeteksi balita yang membutuhkan tambahan asupan gizi.
              </p>
            </div>
          </div>
        </div>
      </div>

      <section className="py-20 bg-[#E5F4FF]" id="tentang">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center max-w-2xl mx-auto mb-14">
            <h2 className="text-2xl sm:text-3xl font-extrabold text-[#0D47A1] mb-2.5">
              Tentang Posyandu Pintar
            </h2>
            <p className="text-xs sm:text-sm text-ink-600 leading-relaxed">
              Sistem informasi pencatatan tumbuh kembang balita yang dirancang untuk mendukung kerja kader dan bidan desa di lapangan.
            </p>
          </div>

          <div className="space-y-6">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div className="p-7 sm:p-9 rounded-3xl bg-[#FFFDF5] border border-amber-200/90 shadow-xs space-y-3">
                <div className="w-11 h-11 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center text-xl">
                  <i className="bi bi-clipboard-check"></i>
                </div>
                <h3 className="text-base sm:text-lg font-extrabold text-[#0D47A1]">
                  Pencatatan Rapi Tanpa Buku Rusak
                </h3>
                <p className="text-xs sm:text-sm text-ink-600 leading-relaxed">
                  Riwayat pengukuran balita tersimpan terpusat di sistem desa. Kader tidak lagi khawatir buku catatan fisik tercecer, robek, atau catatan bulan lalu hilang saat pergantian tugas posyandu.
                </p>
              </div>

              <div className="p-7 sm:p-9 rounded-3xl bg-[#F4F9FF] border border-blue-200/90 shadow-xs space-y-3">
                <div className="w-11 h-11 rounded-xl bg-[#E3F2FD] text-[#2196F3] flex items-center justify-center text-xl">
                  <i className="bi bi-speedometer2"></i>
                </div>
                <h3 className="text-base sm:text-lg font-extrabold text-[#0D47A1]">
                  Hasil Timbangan Langsung Terplot
                </h3>
                <p className="text-xs sm:text-sm text-ink-600 leading-relaxed">
                  Begitu kader memasukkan angka berat dan tinggi badan, grafik kurva KMS anak langsung terbarui saat itu juga tanpa perlu menghitung usia anak berbulan-bulan secara manual.
                </p>
              </div>
            </div>

            <div className="p-7 sm:p-9 rounded-3xl bg-[#FAF7FF] border border-purple-200/90 shadow-xs space-y-3">
              <div className="w-11 h-11 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl">
                <i className="bi bi-people-fill"></i>
              </div>
              <h3 className="text-base sm:text-lg font-extrabold text-[#0D47A1]">
                Koordinasi Cepat Kader dan Bidan Desa
              </h3>
              <p className="text-xs sm:text-sm text-ink-600 leading-relaxed max-w-4xl">
                Bidan desa langsung menerima rekap anak yang memerlukan penanganan khusus pada hari yang sama. Tindakan intervensi gizi, vitamin, dan rujukan ke puskesmas dapat dilakukan tanpa menanti laporan rekap akhir bulan.
              </p>
            </div>
          </div>
        </div>
      </section>

      <section className="py-20 bg-white border-t border-line" id="layanan">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center max-w-2xl mx-auto mb-12">
            <h2 className="text-2xl sm:text-3xl font-extrabold text-[#0D47A1] mb-2.5">
              Layanan Posyandu
            </h2>
            <p className="text-xs sm:text-sm text-ink-600 leading-relaxed">
              Rangkaian pelayanan kesehatan dan pemantauan gizi balita yang diselenggarakan setiap bulan di desa.
            </p>
          </div>

          <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            <div className="p-5 bg-white rounded-2xl border border-line text-center shadow-xs">
              <div className="w-12 h-12 rounded-full bg-blue-50 text-[#2196F3] flex items-center justify-center text-xl mx-auto mb-3">
                <i className="bi bi-person-fill"></i>
              </div>
              <strong className="block text-xs font-extrabold text-[#0D47A1] mb-1">Balita 0–5 Tahun</strong>
              <span className="text-[11px] text-ink-500">Antropometri Rutin</span>
            </div>

            <div className="p-5 bg-white rounded-2xl border border-line text-center shadow-xs">
              <div className="w-12 h-12 rounded-full bg-blue-50 text-[#2196F3] flex items-center justify-center text-xl mx-auto mb-3">
                <i className="bi bi-heart-fill"></i>
              </div>
              <strong className="block text-xs font-extrabold text-[#0D47A1] mb-1">Ibu &amp; Balita</strong>
              <span className="text-[11px] text-ink-500">Konseling Menyusui</span>
            </div>

            <div className="p-5 bg-white rounded-2xl border border-line text-center shadow-xs">
              <div className="w-12 h-12 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center text-xl mx-auto mb-3">
                <i className="bi bi-speedometer2"></i>
              </div>
              <strong className="block text-xs font-extrabold text-[#0D47A1] mb-1">KMS Digital</strong>
              <span className="text-[11px] text-ink-500">Grafik Timbangan</span>
            </div>

            <div className="p-5 bg-white rounded-2xl border border-line text-center shadow-xs">
              <div className="w-12 h-12 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center text-xl mx-auto mb-3">
                <i className="bi bi-shield-plus"></i>
              </div>
              <strong className="block text-xs font-extrabold text-[#0D47A1] mb-1">Imunisasi &amp; Vitamin</strong>
              <span className="text-[11px] text-ink-500">Kapsul Vitamin A</span>
            </div>

            <div className="p-5 bg-white rounded-2xl border border-line text-center shadow-xs">
              <div className="w-12 h-12 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center text-xl mx-auto mb-3">
                <i className="bi bi-egg-fried"></i>
              </div>
              <strong className="block text-xs font-extrabold text-[#0D47A1] mb-1">Makanan Tambahan</strong>
              <span className="text-[11px] text-ink-500">Menu Protein Hewani</span>
            </div>

            <div className="p-5 bg-white rounded-2xl border border-line text-center shadow-xs">
              <div className="w-12 h-12 rounded-full bg-teal-50 text-teal-700 flex items-center justify-center text-xl mx-auto mb-3">
                <i className="bi bi-hospital"></i>
              </div>
              <strong className="block text-xs font-extrabold text-[#0D47A1] mb-1">Bidan Desa</strong>
              <span className="text-[11px] text-ink-500">Pemeriksaan &amp; Rujukan</span>
            </div>
          </div>
        </div>
      </section>

      <section className="py-20 bg-white border-t border-line" id="jadwal">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center max-w-2xl mx-auto mb-12">
            <span className="section-kicker mb-2.5">
              Agenda Terpadu Desa
            </span>
            <h2 className="text-2xl sm:text-3xl font-extrabold text-[#0D47A1] mb-2.5">
              Jadwal Pelayanan Posyandu
            </h2>
            <p className="text-xs sm:text-sm text-ink-600 leading-relaxed">
              Informasi resmi jadwal penimbangan dan pemantauan tumbuh kembang balita yang terhubung langsung dengan sistem desa.
            </p>
          </div>

          <div className="public-schedule-grid grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
            {jadwalLoading ? (
              <p className="md:col-span-2 py-10 text-center text-sm text-ink-500">Memuat jadwal pelayanan…</p>
            ) : jadwalError ? (
              <p className="md:col-span-2 py-10 text-center text-sm text-rose-700">Jadwal belum dapat dimuat. Silakan coba lagi nanti.</p>
            ) : jadwalList.length === 0 ? (
              <p className="md:col-span-2 py-10 text-center text-sm text-ink-500">Belum ada jadwal pelayanan yang diterbitkan.</p>
            ) : jadwalList.map((j: Jadwal) => (
              <div
                key={j.id}
                className="public-schedule-card bg-white p-6 rounded-2xl border border-line shadow-xs border-l-4 border-l-[#2196F3] flex flex-col justify-between"
              >
                <div>
                  <div className="public-schedule-heading flex items-center justify-between gap-2 mb-3">
                    <div className="flex items-center gap-2 min-w-0 flex-wrap">
                      <span className="schedule-location-label break-words">
                        Dusun {j.dusun}
                      </span>
                    </div>
                    {j.status === 'selesai' ? (
                      <span className="schedule-status schedule-status-done">
                        <i className="bi bi-check-circle"></i>
                        <span>Selesai</span>
                      </span>
                    ) : (
                      <span className="schedule-status schedule-status-upcoming">
                        <i className="bi bi-clock"></i>
                        <span>Akan Datang</span>
                      </span>
                    )}
                  </div>

                  <h3 className="font-extrabold text-[#0D47A1] text-base mb-3 break-words">{j.nama_kegiatan}</h3>

                  <div className="public-schedule-details space-y-2 text-xs text-ink-700 mb-4">
                    <div className="flex items-start gap-2">
                      <i className="bi bi-calendar text-[#2196F3]"></i>
                      <span><strong className="text-ink-900">{formatTanggalId(j.tanggal)}</strong><span className="text-ink-400"> · {j.waktu_mulai}–{j.waktu_selesai} WIB</span></span>
                    </div>
                    <div className="flex items-start gap-2">
                      <i className="bi bi-geo-alt-fill text-[#2196F3]"></i>
                      <span className="min-w-0 break-words">{j.lokasi}</span>
                    </div>
                    <div className="flex items-start gap-2">
                      <i className="bi bi-person-badge text-[#2196F3]"></i>
                      <span>Petugas: <strong>{j.petugas || 'Belum ditentukan'}</strong></span>
                    </div>
                  </div>

                  <div className="p-3 bg-[#F0F7FF] border border-blue-100 rounded-xl text-xs text-ink-600 flex items-start gap-2">
                    <i className="bi bi-info-circle text-[#2196F3]"></i>
                    <span>Pelayanan Posyandu untuk warga Dusun {j.dusun}.</span>
                  </div>
                </div>
              </div>
            ))}
          </div>

          <div className="p-6 bg-white rounded-2xl border border-line shadow-xs">
            <div className="flex items-center justify-between flex-wrap gap-2 mb-4 pb-3 border-b border-line">
              <div>
                <h3 className="font-extrabold text-[#0D47A1] text-sm sm:text-base flex items-center gap-2">
                  <i className="bi bi-hospital text-[#2196F3]"></i>
                  <span>Lokasi Agenda Pelayanan</span>
                </h3>
                <p className="text-xs text-ink-500 mt-0.5">
                  Lokasi dan waktu mengikuti agenda yang dikelola admin desa
                </p>
              </div>
              <span className="location-count-text">
                {lokasiPelayanan.length} Lokasi
              </span>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
              {lokasiPelayanan.length === 0 ? (
                <p className="sm:col-span-2 py-6 text-center text-sm text-ink-500">Lokasi pelayanan akan tampil setelah admin menambahkan agenda.</p>
              ) : lokasiPelayanan.map((pos) => (
                <div key={`${pos.dusun}-${pos.lokasi}`} className="p-4 rounded-xl bg-slate-50/70 border border-line flex flex-col justify-between">
                  <div>
                    <div className="flex items-center justify-between mb-1.5">
                      <strong className="text-[#0D47A1] text-xs font-bold">{pos.nama_kegiatan}</strong>
                    </div>
                    <div className="text-xs text-[#2196F3] font-semibold mb-1">
                      <i className="bi bi-geo-alt me-1"></i> Dusun {pos.dusun}
                    </div>
                    <p className="text-[11px] text-ink-500 mb-2">{pos.lokasi}</p>
                  </div>
                  <div className="pt-2 border-t border-line text-[11px] text-ink-700 space-y-0.5">
                    <div><i className="bi bi-calendar3 me-1 text-ink-400"></i> {formatTanggalId(pos.tanggal)} · {pos.waktu_mulai}–{pos.waktu_selesai}</div>
                    <div><i className="bi bi-person me-1 text-ink-400"></i> PJ: <strong>{pos.petugas || 'Belum ditentukan'}</strong></div>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      <section className="py-20 bg-white border-t border-line" id="kalkulator">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center max-w-2xl mx-auto mb-12">
            <span className="section-kicker mb-2.5">
              Skrining Mandiri
            </span>
            <h2 className="text-2xl sm:text-3xl font-extrabold text-[#0D47A1] mb-2.5">
              Kalkulator KMS Mandiri Balita
            </h2>
            <p className="text-xs sm:text-sm text-ink-600 leading-relaxed">
              Periksa estimasi kurva tumbuh kembang buah hati Anda secara mandiri di rumah berdasarkan standar antropometri Posyandu.
            </p>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch">
            <div className="kms-form-card bg-white p-6 sm:p-8 rounded-3xl border border-line shadow-xs">
              <form onSubmit={(event) => {
                event.preventDefault();
                setKmsResult({ ...calculateKms(), usia: kmsUsia, berat: kmsBerat, tinggi: kmsTinggi });
              }}>
              <div>
                <div className="flex items-center gap-3 pb-4 mb-6 border-b border-line">
                  <div className="w-12 h-12 rounded-2xl bg-blue-50 text-[#2196F3] flex items-center justify-center text-xl shrink-0">
                    <i className="bi bi-calculator"></i>
                  </div>
                  <div>
                    <h3 className="font-extrabold text-[#0D47A1] text-base">Parameter Pengukuran Anak</h3>
                    <span className="text-xs text-ink-500">Masukkan data fisik balita sesuai buku KIA</span>
                  </div>
                </div>

                <div className="kms-fields space-y-4">
                  <div>
                    <label className="block text-xs font-bold text-ink-700 mb-2">
                      Jenis Kelamin Balita
                    </label>
                    <div className="kms-gender-options grid grid-cols-2 gap-3">
                      <button
                        type="button"
                        aria-pressed={kmsJk === 'L'}
                        onClick={() => setKmsJk('L')}
                        className={`py-2.5 px-4 rounded-xl border text-xs font-bold flex items-center justify-center gap-2 transition-base ${
                          kmsJk === 'L'
                            ? 'bg-[#2196F3] text-white border-[#2196F3]'
                            : 'bg-white text-[#2196F3] border-[#2196F3] hover:bg-slate-50'
                        }`}
                      >
                        <i className="bi bi-gender-male text-sm"></i>
                        <span>Laki-laki</span>
                      </button>
                      <button
                        type="button"
                        aria-pressed={kmsJk === 'P'}
                        onClick={() => setKmsJk('P')}
                        className={`py-2.5 px-4 rounded-xl border text-xs font-bold flex items-center justify-center gap-2 transition-base ${
                          kmsJk === 'P'
                            ? 'bg-[#2196F3] text-white border-[#2196F3]'
                            : 'bg-white text-[#2196F3] border-[#2196F3] hover:bg-slate-50'
                        }`}
                      >
                        <i className="bi bi-gender-female text-sm"></i>
                        <span>Perempuan</span>
                      </button>
                    </div>
                  </div>

                  <div>
                    <label htmlFor="kms-usia" className="block text-xs font-bold text-ink-700 mb-1.5">
                      Usia Anak (Bulan)
                    </label>
                    <div className="kms-input-group">
                      <input
                        id="kms-usia"
                        type="number"
                        required
                        min="0"
                        max="60"
                        value={kmsUsia}
                        onChange={(e) => setKmsUsia(parseInt(e.target.value) || 0)}
                        className="w-full px-4 py-2.5 text-xs sm:text-sm font-semibold border border-line rounded-l-xl focus:outline-none focus:border-[#2196F3]"
                      />
                      <span className="px-4 py-2.5 bg-slate-50 border border-l-0 border-line text-ink-600 text-xs font-semibold rounded-r-xl">
                        bulan
                      </span>
                    </div>
                  </div>

                  <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                      <label htmlFor="kms-berat" className="block text-xs font-bold text-ink-700 mb-1.5">
                        Berat Badan (kg)
                      </label>
                      <div className="kms-input-group">
                        <input
                          id="kms-berat"
                          type="number"
                          required
                          step="0.1"
                          min="2"
                          max="35"
                          value={kmsBerat}
                          onChange={(e) => setKmsBerat(parseFloat(e.target.value) || 0)}
                          className="w-full px-4 py-2.5 text-xs sm:text-sm font-semibold border border-line rounded-l-xl focus:outline-none focus:border-[#2196F3]"
                        />
                        <span className="px-4 py-2.5 bg-slate-50 border border-l-0 border-line text-ink-600 text-xs font-semibold rounded-r-xl">
                          kg
                        </span>
                      </div>
                    </div>

                    <div>
                      <label htmlFor="kms-tinggi" className="block text-xs font-bold text-ink-700 mb-1.5">
                        Panjang / Tinggi (cm)
                      </label>
                      <div className="kms-input-group">
                        <input
                          id="kms-tinggi"
                          type="number"
                          required
                          step="0.1"
                          min="45"
                          max="130"
                          value={kmsTinggi}
                          onChange={(e) => setKmsTinggi(parseFloat(e.target.value) || 0)}
                          className="w-full px-4 py-2.5 text-xs sm:text-sm font-semibold border border-line rounded-l-xl focus:outline-none focus:border-[#2196F3]"
                        />
                        <span className="px-4 py-2.5 bg-slate-50 border border-l-0 border-line text-ink-600 text-xs font-semibold rounded-r-xl">
                          cm
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div className="kms-form-actions pt-6">
                <button
                  type="submit"
                  className="w-full py-3 bg-[#2196F3] hover:bg-[#1976D2] text-white text-xs sm:text-sm font-bold rounded-xl flex items-center justify-center gap-2 shadow-xs transition-base"
                >
                  <i className="bi bi-graph-up"></i>
                  <span>Hitung Estimasi Status Tumbuh Kembang</span>
                </button>
              </div>
              </form>
            </div>

            <div className="bg-white p-6 sm:p-8 rounded-3xl border border-line shadow-xs flex flex-col justify-between">
              <div>
                <div className="flex items-center gap-3 pb-4 mb-6 border-b border-line">
                  <div className="w-12 h-12 rounded-2xl bg-blue-50 text-[#2196F3] flex items-center justify-center text-xl shrink-0">
                    <i className="bi bi-clipboard-data"></i>
                  </div>
                  <div>
                    <h3 className="font-extrabold text-[#0D47A1] text-base">Hasil Pemantauan KMS</h3>
                    <span className="text-xs text-ink-500">Standar Kurva Kemenkes RI</span>
                  </div>
                </div>

                <div className="flex items-center justify-between mb-4 flex-wrap gap-2">
                  <span className="text-xs font-bold text-ink-900">Status Evaluasi:</span>
                  <span className={`result-status status-mark ${kmsResult.status === 'Prioritas Bidan' ? 'status-mark-prioritas' : kmsResult.status === 'Perlu Pemantauan' ? 'status-mark-pemantauan' : 'status-mark-normal'}`}>
                    <i className={`bi ${kmsResult.icon}`}></i>
                    <span>{kmsResult.status}</span>
                  </span>
                </div>

                <div className="kms-summary grid grid-cols-3 gap-2 py-4 px-3 rounded-2xl bg-[#F0F7FF] border border-blue-100 text-center my-4">
                  <div>
                    <span className="text-[11px] text-ink-500 block mb-0.5">Usia</span>
                    <strong className="text-sm sm:text-base font-extrabold text-[#0D47A1]">{kmsResult.usia} bln</strong>
                  </div>
                  <div className="border-x border-blue-200">
                    <span className="text-[11px] text-ink-500 block mb-0.5">Berat Badan</span>
                    <strong className="text-sm sm:text-base font-extrabold text-[#0D47A1]">{kmsResult.berat} kg</strong>
                  </div>
                  <div>
                    <span className="text-[11px] text-ink-500 block mb-0.5">Tinggi / Panjang</span>
                    <strong className="text-sm sm:text-base font-extrabold text-[#0D47A1]">{kmsResult.tinggi} cm</strong>
                  </div>
                </div>

                <p className="text-xs sm:text-sm text-ink-700 leading-relaxed mb-4">
                  {kmsResult.penjelasan}
                </p>

                <div className="kms-advice p-4 rounded-xl border border-line space-y-1 mb-4">
                  <strong className="text-xs font-bold text-[#0D47A1] flex items-center gap-1.5">
                    <i className="bi bi-lightbulb-fill text-amber-500"></i>
                    <span>Saran Tindakan:</span>
                  </strong>
                  <p className="text-xs text-ink-700 leading-relaxed">{kmsResult.saran}</p>
                </div>
              </div>

              <div className="kms-note p-3.5 rounded-xl bg-blue-50/60 border border-blue-100 text-xs text-ink-600 leading-relaxed">
                <i className="bi bi-info-circle-fill text-[#2196F3] me-1.5"></i>
                <strong>Catatan Bidan:</strong> Skrining awal mandiri ini merupakan estimasi keluarga di rumah. Pemeriksaan resmi dan evaluasi kurva KMS dipastikan oleh Bidan Desa saat hari buka Posyandu.
              </div>
            </div>
          </div>
        </div>
      </section>

      <section className="py-20 bg-white border-t border-line" id="blog">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center max-w-2xl mx-auto mb-12">
            <h2 className="text-2xl sm:text-3xl font-extrabold text-[#0D47A1] mb-2.5">
              Blog &amp; Panduan Posyandu
            </h2>
            <p className="text-xs sm:text-sm text-ink-600 leading-relaxed">
              Panduan praktis seputar menu MPASI gizi seimbang, pola asuh anak, dan tips perawatan balita dari tenaga kesehatan desa.
            </p>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {daftarBlog.length === 0 ? (
              <p className="text-center text-ink-400 text-sm py-10 lg:col-span-12">Belum ada artikel blog saat ini.</p>
            ) : (
              <>
                <div className="blog-feature">
                  <div
                    onClick={() => navigate(`/berita/${daftarBlog[0].id}`)}
                    className="cursor-pointer relative rounded-3xl overflow-hidden min-h-[380px] sm:min-h-[440px] flex flex-col justify-end p-6 sm:p-8 bg-cover bg-center shadow-xs group"
                    style={{
                      backgroundImage: `linear-gradient(to top, rgba(15, 23, 42, 0.95) 0%, rgba(15, 23, 42, 0.5) 50%, rgba(15, 23, 42, 0.2) 100%), url('${daftarBlog[0].gambar || '/images/panduan-penimbangan.png'}')`
                    }}
                  >
                    <div className="space-y-3 relative z-10 text-white">
                      <span className="article-category">
                        {daftarBlog[0].kategori}
                      </span>
                      <h3 className="text-xl sm:text-2xl font-black text-white group-hover:text-blue-200 transition-base leading-snug">
                        {daftarBlog[0].judul}
                      </h3>
                      <div className="blog-feature-meta flex items-center gap-3 text-xs text-slate-300">
                        <span><i className="bi bi-calendar3 me-1"></i> {daftarBlog[0].published_at ? formatTanggalId(daftarBlog[0].published_at) : ''}</span>
                        <span className="blog-feature-meta-separator">&bull;</span>
                        <span><i className="bi bi-person me-1"></i> {daftarBlog[0].penulis || 'Tim Posyandu'}</span>
                      </div>
                      <p className="blog-feature-summary text-xs sm:text-sm text-slate-200 leading-relaxed">
                        {daftarBlog[0].ringkasan}
                      </p>
                      <span className="inline-flex items-center gap-1.5 text-xs font-bold text-white pt-1">
                        <span>Baca Selengkapnya</span>
                        <i className="bi bi-arrow-right"></i>
                      </span>
                    </div>
                  </div>
                </div>

                <div className="blog-related flex flex-col space-y-4">
                  <div className="text-xs font-extrabold uppercase tracking-wider text-[#0D47A1] flex items-center gap-1.5">
                    <i className="bi bi-star-fill text-amber-500"></i>
                    <span>Artikel Lainnya</span>
                  </div>
                  <div className={`flex-1 flex flex-col ${daftarBlog.length > 1 ? 'justify-start space-y-4' : 'justify-center'}`}>
                    {daftarBlog.slice(1).map((b) => (
                      <div
                        key={b.id}
                        onClick={() => navigate(`/berita/${b.id}`)}
                        className="cursor-pointer p-5 bg-white hover:bg-slate-50 rounded-2xl border border-line shadow-xs transition-base space-y-2"
                      >
                        <span className="text-xs font-bold text-[#2196F3] block">{b.kategori}</span>
                        <h4 className="text-sm font-extrabold text-[#0D47A1]">{b.judul}</h4>
                        <div className="flex items-center justify-between pt-1 text-xs text-ink-400">
                          <span><i className="bi bi-calendar3 me-1"></i> {b.published_at ? formatTanggalId(b.published_at) : ''}</span>
                          <span className="text-[#2196F3] font-bold">Baca &rarr;</span>
                        </div>
                      </div>
                    ))}
                    {daftarBlog.length === 1 && (
                      <p className="text-xs text-ink-400 text-center py-8">Belum ada artikel lainnya.</p>
                    )}
                  </div>
                </div>
              </>
            )}
          </div>
        </div>
      </section>

      <section className="py-20 bg-[#E5F4FF] border-t border-line" id="berita">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center max-w-2xl mx-auto mb-12">
            <h2 className="text-2xl sm:text-3xl font-extrabold text-[#0D47A1] mb-2.5">
              Berita &amp; Kegiatan Posyandu
            </h2>
            <p className="text-xs sm:text-sm text-ink-600 leading-relaxed">
              Pengumuman jadwal posyandu, kegiatan penimbangan balita, dan informasi gizi anak Desa Sukatani.
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {daftarBerita.length === 0 ? (
              <p className="text-center text-ink-400 text-sm py-10 md:col-span-3">Belum ada berita saat ini.</p>
            ) : (
              daftarBerita.map((b) => (
                <div
                  key={b.id}
                  onClick={() => navigate(`/berita/${b.id}`)}
                  className="cursor-pointer bg-white rounded-2xl border border-line overflow-hidden shadow-xs hover:shadow-md transition-base flex flex-col justify-between"
                >
                  <div className="aspect-16/10 overflow-hidden bg-slate-100">
                    {b.gambar ? (
                      <img
                        src={b.gambar}
                        alt={b.judul}
                        className="w-full h-full object-cover hover:scale-105 transition-all duration-500"
                      />
                    ) : (
                      <div className="w-full h-full flex items-center justify-center text-ink-300">
                        <i className="bi bi-image text-3xl"></i>
                      </div>
                    )}
                  </div>
                  <div className="p-6 space-y-3 flex-1 flex flex-col justify-between">
                    <div>
                      <div className="flex items-center justify-between text-xs mb-2">
                        <span className="font-bold text-[#2196F3]">{b.kategori}</span>
                        <span className="text-ink-400"><i className="bi bi-calendar3 me-1"></i> {b.published_at ? formatTanggalId(b.published_at) : ''}</span>
                      </div>
                      <h3 className="text-base font-extrabold text-[#0D47A1] mb-1.5 leading-snug">
                        {b.judul}
                      </h3>
                      <p className="text-xs text-ink-600 leading-relaxed">
                        {b.ringkasan}
                      </p>
                    </div>
                    <span className="text-xs font-bold text-[#2196F3] pt-2 flex items-center gap-1">
                      <span>Baca Selengkapnya</span>
                      <i className="bi bi-arrow-right"></i>
                    </span>
                  </div>
                </div>
              ))
            )}
          </div>
        </div>
      </section>

      <section className="py-20 bg-white border-t border-line" id="faq">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center max-w-2xl mx-auto mb-12">
            <span className="section-kicker mb-2.5">
              Pertanyaan Umum
            </span>
            <h2 className="text-2xl sm:text-3xl font-extrabold text-[#0D47A1] mb-2.5">
              Pertanyaan yang Sering Diajukan
            </h2>
            <p className="text-xs sm:text-sm text-ink-600 leading-relaxed">
              Temukan jawaban seputar cara kerja Posyandu Pintar dan pengelolaan data tumbuh kembang balita.
            </p>
          </div>

          <div className="max-w-3xl mx-auto space-y-3">
            {FAQ_LIST.map((f, i) => (
              <div key={i} className="border border-line rounded-2xl bg-white overflow-hidden shadow-xs">
                <button
                  type="button"
                  onClick={() => setFaqOpen(faqOpen === i ? null : i)}
                  className="w-full flex items-center justify-between gap-3 px-5 py-4 text-left"
                >
                  <span className="text-sm font-bold text-[#0D47A1]">{f.q}</span>
                  <i className={`bi ${faqOpen === i ? 'bi-chevron-up' : 'bi-chevron-down'} text-[#2196F3] text-sm shrink-0`}></i>
                </button>
                {faqOpen === i && (
                  <div className="px-5 pb-4 -mt-1 text-xs text-ink-600 leading-relaxed">{f.a}</div>
                )}
              </div>
            ))}
          </div>
        </div>
      </section>
    </div>
  );
};
