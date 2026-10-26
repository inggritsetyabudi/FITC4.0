import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';

const NAV_LINKS = [
  { href: '/', label: 'Beranda' },
  { href: '#tentang', label: 'Tentang Kami' },
  { href: '#jadwal', label: 'Jadwal Posyandu' },
  { href: '#blog', label: 'Blog' },
  { href: '#berita', label: 'Berita' }
];

export const Navbar: React.FC = () => {
  const navigate = useNavigate();
  const { user } = useAuth();
  const [open, setOpen] = useState(false);

  return (
    <header className="public-navbar sticky top-0 z-50 bg-[#0D47A1] text-white shadow-md font-sans">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-20">
          <Link to="/" className="flex items-center gap-3 text-left focus:outline-none">
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
          </Link>

          <nav className="hidden md:flex items-center gap-8 text-sm font-semibold">
            {NAV_LINKS.map((l) => (
              <a
                key={l.label}
                href={l.href}
                className="text-blue-100 hover:text-white transition-base"
              >
                {l.label}
              </a>
            ))}
          </nav>

          <div className="flex items-center gap-3">
            {user ? (
              <button
                onClick={() => navigate('/dashboard')}
                title={`Masuk sebagai ${user.nama}`}
                className="public-login-button px-5 py-2 bg-white text-[#1976D2] text-xs font-bold flex items-center gap-2"
              >
                <i className="bi bi-speedometer2" />
                <span className="max-w-[110px] truncate">{user.nama.split(' ')[0]}</span>
              </button>
            ) : (
              <button
                onClick={() => navigate('/login')}
                className="public-login-button px-5 py-2 bg-white text-[#1976D2] text-xs font-bold flex items-center gap-2"
              >
                <i className="bi bi-box-arrow-in-right" /><span>Masuk</span>
              </button>
            )}
            <button
              onClick={() => setOpen((o) => !o)}
              className="md:hidden p-2 rounded-lg border border-white/30 text-white"
              aria-label="Buka menu navigasi"
            >
              <i className={`bi ${open ? 'bi-x-lg' : 'bi-list'} text-xl`}></i>
            </button>
          </div>
        </div>
      </div>

      {open && (
        <div className="md:hidden bg-[#1565C0] border-t border-white/10 px-4 pb-4 pt-2 space-y-1">
          {NAV_LINKS.map((l) =>
            l.href === '/' ? (
              <button
                key={l.label}
                onClick={() => { setOpen(false); navigate('/'); }}
                className="block w-full text-left px-3 py-2.5 rounded-lg text-sm font-semibold text-blue-50 hover:bg-white/10"
              >
                {l.label}
              </button>
            ) : (
              <a
                key={l.label}
                href={l.href}
                onClick={() => setOpen(false)}
                className="block px-3 py-2.5 rounded-lg text-sm font-semibold text-blue-50 hover:bg-white/10"
              >
                {l.label}
              </a>
            )
          )}
        </div>
      )}
    </header>
  );
};
