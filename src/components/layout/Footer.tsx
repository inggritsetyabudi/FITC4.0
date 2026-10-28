import React, { useState } from 'react';
import { Link } from 'react-router-dom';

export const Footer: React.FC = () => {
  const [email, setEmail] = useState('');
  const [subscribed, setSubscribed] = useState(false);

  const handleSubscribe = (e: React.FormEvent) => {
    e.preventDefault();
    if (email) {
      setSubscribed(true);
      setTimeout(() => {
        setEmail('');
        setSubscribed(false);
      }, 4000);
    }
  };

  return (
    <footer className="w-full bg-[#0D47A1] text-white font-sans border-t border-blue-900/50">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-8">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 pb-10 border-b border-white/15">
          <div className="lg:col-span-4 space-y-4">
            <Link
              to="/"
              className="flex items-center gap-2.5 text-left focus:outline-none"
            >
              <img
                src="/images/logo sementara.png"
                alt="Logo Posyandu Pintar"
                className="h-10 w-10 object-contain"
              />
              <span className="text-xl font-bold text-white tracking-tight">Posyandu Pintar</span>
            </Link>
            <p className="text-sm text-white/70 leading-relaxed max-w-sm">
              Sistem informasi posyandu untuk pemantauan tumbuh kembang balita dan percepatan penurunan risiko stunting di tingkat desa. Menghubungkan kader, bidan desa, dan para orang tua balita.
            </p>
            <div className="flex gap-2.5 pt-2">
              <a
                href="https://wa.me/6281234567890"
                target="_blank"
                rel="noopener noreferrer"
                className="w-9 h-9 rounded-full bg-white/15 hover:bg-white/25 flex items-center justify-center text-white transition-base text-base"
                aria-label="WhatsApp"
              >
                <i className="bi bi-whatsapp"></i>
              </a>
              <a
                href="https://instagram.com"
                target="_blank"
                rel="noopener noreferrer"
                className="w-9 h-9 rounded-full bg-white/15 hover:bg-white/25 flex items-center justify-center text-white transition-base text-base"
                aria-label="Instagram"
              >
                <i className="bi bi-instagram"></i>
              </a>
              <a
                href="https://facebook.com"
                target="_blank"
                rel="noopener noreferrer"
                className="w-9 h-9 rounded-full bg-white/15 hover:bg-white/25 flex items-center justify-center text-white transition-base text-base"
                aria-label="Facebook"
              >
                <i className="bi bi-facebook"></i>
              </a>
              <a
                href="https://youtube.com"
                target="_blank"
                rel="noopener noreferrer"
                className="w-9 h-9 rounded-full bg-white/15 hover:bg-white/25 flex items-center justify-center text-white transition-base text-base"
                aria-label="YouTube"
              >
                <i className="bi bi-youtube"></i>
              </a>
            </div>
          </div>

          <div className="lg:col-span-2">
            <h4 className="text-xs font-bold text-white uppercase tracking-wider mb-4">Menu Utama</h4>
            <ul className="space-y-2.5 text-sm text-white/70 font-medium">
              <li>
                <Link to="/" className="hover:text-white transition-base">
                  Beranda
                </Link>
              </li>
              <li>
                <a href="#tentang" className="hover:text-white transition-base">Tentang Kami</a>
              </li>
              <li>
                <a href="#layanan" className="hover:text-white transition-base">Layanan Posyandu</a>
              </li>
              <li>
                <a href="#jadwal" className="hover:text-white transition-base">Jadwal Agenda</a>
              </li>
              <li>
                <a href="#kalkulator" className="hover:text-white transition-base">Kalkulator KMS</a>
              </li>
              <li>
                <a href="#blog" className="hover:text-white transition-base">Blog &amp; Panduan</a>
              </li>
              <li>
                <a href="#berita" className="hover:text-white transition-base">Berita Kegiatan</a>
              </li>
            </ul>
          </div>

          <div className="lg:col-span-3">
            <h4 className="text-xs font-bold text-white uppercase tracking-wider mb-4">Kontak &amp; Posko</h4>
            <ul className="space-y-3 text-sm text-white/70">
              <li className="flex items-start gap-2.5">
                <i className="bi bi-geo-alt-fill text-blue-200 text-base mt-0.5 shrink-0"></i>
                <span>Balai Desa Sukatani, Kec. Prambon, Sidoarjo</span>
              </li>
              <li className="flex items-center gap-2.5">
                <i className="bi bi-telephone-fill text-blue-200 text-base shrink-0"></i>
                <span>(031) 897-1234</span>
              </li>
              <li className="flex items-center gap-2.5">
                <i className="bi bi-envelope-fill text-blue-200 text-base shrink-0"></i>
                <span>posyandu@sukatani.desa.id</span>
              </li>
            </ul>
          </div>

          <div className="lg:col-span-3 space-y-4">
            <h4 className="text-xs font-bold text-white uppercase tracking-wider mb-2">Dapatkan Info Terbaru</h4>
            <p className="text-xs text-white/70 leading-relaxed">
              Daftarkan email untuk pengumuman jadwal posyandu dan tips nutrisi balita.
            </p>
            {subscribed ? (
              <div className="p-2.5 bg-emerald-500/20 border border-emerald-400 text-emerald-200 text-xs rounded-xl flex items-center gap-2">
                <i className="bi bi-check-circle-fill"></i>
                <span>Terima kasih! Email Anda telah terdaftar.</span>
              </div>
            ) : (
              <form onSubmit={handleSubscribe} className="flex">
                <input
                  type="email"
                  required
                  placeholder="Alamat email Anda"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  className="w-full px-3.5 py-2 text-xs bg-white/10 text-white placeholder-white/50 border border-white/20 rounded-l-xl focus:outline-none focus:border-white transition-base"
                />
                <button
                  type="submit"
                  className="px-4 bg-[#2196F3] hover:bg-blue-600 text-white rounded-r-xl transition-base flex items-center justify-center"
                >
                  <i className="bi bi-arrow-right-short text-xl"></i>
                </button>
              </form>
            )}

            <div className="p-3 bg-white/8 border border-white/15 rounded-xl text-xs text-white/80 leading-relaxed">
              <strong className="text-white block mb-1">
                <i className="bi bi-clock-fill text-blue-200 me-1"></i> Jam Pelayanan Posyandu
              </strong>
              Setiap bulan sesuai jadwal dusun masing-masing<br />
              Pukul 08.00 – 11.00 WIB
            </div>
          </div>
        </div>

        <div className="pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-white/70">
          <div>
            Copyright &copy; 2026 <strong className="text-white">Posyandu Pintar</strong>. All rights reserved.
          </div>
          <div className="flex gap-4">
            <a href="#tentang" className="hover:text-white transition-base">Tentang Kami</a>
            <span>|</span>
            <a href="#layanan" className="hover:text-white transition-base">Layanan</a>
            <span>|</span>
            <a href="#blog" className="hover:text-white transition-base">Blog &amp; Panduan</a>
          </div>
        </div>
      </div>
    </footer>
  );
};
