import React from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { beritaService } from '../services/api';
import { useAsyncData } from '../hooks/useAsyncData';
import type { Berita } from '../types';
import { formatTanggalId } from '../utils/helpers';

const panduan: Berita = {
  id: 4,
  judul: 'Kenapa penimbangan balita perlu dilakukan secara rutin setiap bulan?',
  kategori: 'PANDUAN POSYANDU',
  penulis: 'Bidan Siti Nurhaliza',
  published_at: '2026-08-24',
  waktu_baca: '5 menit baca',
  gambar: '/images/panduan-penimbangan.png',
  ringkasan: '',
  isi: ''
};

export const DetailBeritaPage: React.FC = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const isPanduan = !id;

  const { data: berita, loading } = useAsyncData(
    () => (isPanduan ? Promise.resolve(panduan) : beritaService.getById(Number(id))),
    [id]
  );

  if (loading) {
    return (
      <div className="py-24 text-center text-ink-400">
        <i className="bi bi-arrow-repeat animate-spin text-2xl"></i>
        <p className="mt-2 text-sm">Memuat artikel edukasi...</p>
      </div>
    );
  }

  if (!berita) {
    return (
      <div className="max-w-3xl mx-auto py-24 px-4 text-center space-y-4">
        <i className="bi bi-file-earmark-x text-5xl text-ink-300"></i>
        <h2 className="text-xl font-bold text-forest-900">Artikel Tidak Ditemukan</h2>
        <p className="text-sm text-ink-500">Artikel yang Anda cari tidak tersedia atau telah diarsipkan.</p>
        <button
          onClick={() => navigate('/')}
          className="px-4 py-2 bg-primary text-white text-sm font-semibold rounded-lg"
        >
          Kembali ke Beranda
        </button>
      </div>
    );
  }

  return (
    <div className={`article-page ${isPanduan ? 'guide-page' : ''} min-h-screen font-sans`}>
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {!isPanduan && <button
          onClick={() => navigate('/')}
          className="inline-flex items-center gap-2 px-4 py-2 bg-white hover:bg-slate-50 text-xs font-semibold text-[#3D5F7D] rounded-xl border border-[#D1E3F2] shadow-xs transition-base mb-6"
        >
          <i className="bi bi-arrow-left"></i>
          <span>Kembali ke Beranda</span>
        </button>}

        <div className="article-grid">
          <article className="bg-white rounded-2xl border border-[#D1E3F2] p-6 sm:p-8 shadow-xs space-y-6">
            <div className="space-y-2">
              <span className="text-[11px] font-extrabold uppercase tracking-widest text-[#2196F3] block">
                {berita.kategori || 'PANDUAN POSYANDU'}
              </span>
              <h1 className="text-2xl sm:text-3xl font-extrabold text-[#0D47A1] leading-tight">
                {berita.judul}
              </h1>
              <div className="article-meta flex items-center gap-2 text-xs text-[#607D99] pt-1">
                <i className="bi bi-person-circle text-primary" /><span>{berita.penulis || 'Bidan Desa Sukatani'}</span>
                <span>&bull;</span>
                <i className="bi bi-calendar3" /><span>{berita.published_at ? formatTanggalId(berita.published_at) : '10 Agustus 2026'}</span>
                {berita.waktu_baca && (
                  <>
                    <span>&bull;</span>
                    <i className="bi bi-clock" /><span>{berita.waktu_baca}</span>
                  </>
                )}
              </div>
            </div>

            {berita.gambar && (
              <div className="article-photo overflow-hidden aspect-video w-full bg-[#F0F7FF]">
                <img
                  src={berita.gambar}
                  alt={berita.judul}
                  className="w-full h-full object-cover"
                />
              </div>
            )}

            <div className="article-body space-y-5">
              {isPanduan ? <>
                <p>Penimbangan rutin setiap bulan membantu kader dan bidan melihat perubahan berat dan tinggi<br className="hidden lg:block" /> badan balita secara berkesinambungan.</p>
                <p>Catatan pertumbuhan yang konsisten memudahkan pemantauan ketika berat badan tidak bertambah sesuai harapan dan membantu keluarga mengambil tindak lanjut lebih cepat.</p>
                <h2>Apa yang dipantau?</h2>
                <p>Setiap kunjungan dapat mencatat berat badan, tinggi atau panjang badan, usia, serta catatan kondisi anak. Data tersebut menjadi dasar diskusi dengan kader dan bidan saat pelayanan Posyandu.</p>
                <h2>Apa yang perlu dibawa?</h2>
                <p>Orang tua dapat membawa Buku KIA atau catatan pertumbuhan, memastikan anak nyaman saat ditimbang, dan menyiapkan pertanyaan yang ingin dikonsultasikan.</p>
                <div className="article-note"><i className="bi bi-lightbulb-fill text-amber-400" /> <strong>Catatan:</strong> Skrining dan pemantauan pada website ini merupakan pendamping informasi. Penilaian resmi tetap dilakukan oleh tenaga kesehatan sesuai pelayanan Posyandu.</div>
              </> : (berita.isi || berita.ringkasan).split(/<\/p>|\n\n/).map(paragraph => paragraph.replace(/<[^>]*>/g, '').trim()).filter(Boolean).map((paragraph, index) => <p key={index}>{paragraph}</p>)}
            </div>

            <div className="pt-6 border-t border-line flex flex-col sm:flex-row sm:items-center justify-between gap-4">
              <span className="text-xs text-[#607D99]">
                Dipublikasikan oleh Tim Layanan Kesehatan Desa Sukatani
              </span>
              <button
                onClick={() => navigate('/')}
                className="px-4 py-2 bg-[#2196F3] hover:bg-[#1976D2] text-white text-xs font-bold rounded-xl flex items-center gap-1.5 transition-base self-start sm:self-auto shadow-xs"
              >
                <span>Lihat Panduan Lainnya</span>
                <i className="bi bi-arrow-right"></i>
              </button>
            </div>
          </article>

          <aside className="space-y-6">
            <div className="bg-white rounded-2xl border border-[#D1E3F2] p-6 shadow-xs space-y-4">
              <h3 className="font-bold text-sm text-[#0D47A1] flex items-center gap-2">
                <i className="bi bi-newspaper text-[#2196F3]"></i>
                <span>Berita &amp; Artikel Lainnya</span>
              </h3>
              <div className="article-related-list space-y-3">
                {(isPanduan ? [
                  { id: 3, kategori: 'GIZI BALITA', judul: 'Protein hewani dan perannya dalam masa pertumbuhan', tanggal: '19 Agustus 2026' },
                  { id: 4, kategori: 'TIPS KELUARGA', judul: 'Daftar persiapan sederhana orang tua sebelum berangkat ke Posyandu', tanggal: '12 Agustus 2026' },
                  { id: 1, kategori: 'GIZI BALITA', judul: 'Vitamin A dan dukungannya untuk tumbuh kembang', tanggal: '10 Agustus 2026' }
                ] : [
                  { id: 2, kategori: 'KEGIATAN', judul: 'Kunjungan Bidan ke Posyandu', tanggal: '5 Agustus 2026' },
                  { id: 3, kategori: 'GIZI BALITA', judul: 'PMT dan Gizi Seimbang', tanggal: '29 Juli 2026' }
                ]).map(item => <button key={item.id} onClick={() => navigate(item.id === 4 ? '/panduan' : `/berita/${item.id}`)} className="w-full text-left p-3 bg-[#F0F7FF] rounded border border-line space-y-2">
                  <span className="block text-[11px] text-primary">{item.kategori}</span>
                  <span className="block text-xs font-bold text-[#0D47A1]">{item.judul}</span>
                  <span className="block text-xs text-ink-500"><i className="bi bi-calendar3" /> {item.tanggal}</span>
                </button>)}
              </div>
            </div>

            <div className="bg-white rounded-2xl border border-[#D1E3F2] p-6 shadow-xs space-y-3">
              <h3 className="font-bold text-base text-[#0D47A1]">Posyandu Pintar</h3>
              <p className="text-xs text-[#607D99] leading-relaxed">
                Pusat informasi, pemantauan kurva KMS digital, dan penimbangan balita Desa Sukatani.
              </p>
              <button
                onClick={() => navigate('/login')}
                className="w-full py-2.5 bg-[#2196F3] hover:bg-[#1976D2] text-white text-xs font-bold rounded-xl flex items-center justify-center gap-1.5 transition-base shadow-xs"
              >
                <i className="bi bi-box-arrow-in-right"></i>
                <span>Masuk ke Sistem</span>
              </button>
            </div>
          </aside>
        </div>
      </div>
    </div>
  );
};
