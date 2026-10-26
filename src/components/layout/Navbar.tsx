import React from 'react';

export const Navbar: React.FC<{ onNavigate: (page: string) => void; currentPage: string }> = ({ onNavigate, currentPage }) => {

  return (
    <header className="public-navbar sticky top-0 z-50 bg-[#0D47A1] text-white shadow-md font-sans">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-20">
          <button
            onClick={() => onNavigate('landing')}
            className="flex items-center gap-3 text-left focus:outline-none"
          >
            <img
              src="/images/logo sementara.png"
              alt="Logo Posyandu Pintar"
              className="h-11 w-auto object-contain"
            />
            <div>
              <span className="block font-bold text-white text-xl leading-tight tracking-tight">
                Posyandu Pintar
              </span>
              <span className="block text-xs text-[#90CAF9] font-medium">
                Layanan Tumbuh Kembang Desa
              </span>
            </div>
          </button>

          <nav className="hidden md:flex items-center gap-8 text-sm font-semibold">
            <button
              onClick={() => onNavigate('landing')}
              className={`transition-base ${
                currentPage === 'landing' ? 'text-white font-bold' : 'text-blue-100 hover:text-white'
              }`}
            >
              Beranda
            </button>
            <a href="#tentang" className="text-blue-100 hover:text-white transition-base">
              Tentang Kami
            </a>
            <a href="#jadwal" className="text-blue-100 hover:text-white transition-base">
              Jadwal Posyandu
            </a>
            <a href="#blog" className="text-blue-100 hover:text-white transition-base">
              Blog
            </a>
            <a href="#berita" className="text-blue-100 hover:text-white transition-base">
              Berita
            </a>
          </nav>

          <div className="flex items-center gap-3">
            <button onClick={() => onNavigate('login')} className="public-login-button px-5 py-2 bg-white text-[#1976D2] text-xs font-bold flex items-center gap-2">
              <i className="bi bi-box-arrow-in-right" /><span>Masuk</span>
            </button>
          </div>
        </div>
      </div>
    </header>
  );
};
