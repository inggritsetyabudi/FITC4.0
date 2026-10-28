import React from 'react';
import { useLocation, useNavigate } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';
import type { UserRole } from '../../types';

const MENU_ITEMS: Record<UserRole, { to: string; label: string; icon: string }[]> = {
  kader: [
    { to: '/dashboard', label: 'Beranda Tugas', icon: 'bi-house-door' },
    { to: '/balita', label: 'Data Balita', icon: 'bi-people' },
    { to: '/pengukuran', label: 'Catat Pengukuran', icon: 'bi-clipboard-plus' }
  ],
  bidan: [
    { to: '/dashboard', label: 'Overview & Prioritas', icon: 'bi-columns-gap' },
    { to: '/prioritas', label: 'Balita Prioritas', icon: 'bi-exclamation-triangle' },
    { to: '/rekap', label: 'Rekap Pengukuran', icon: 'bi-journal-text' },
    { to: '/blog', label: 'Blog & Berita', icon: 'bi-newspaper' }
  ],
  admin: [
    { to: '/dashboard', label: 'Ringkasan Dasbor', icon: 'bi-speedometer2' },
    { to: '/balita', label: 'Data Balita', icon: 'bi-people' },
    { to: '/petugas', label: 'Kader & Bidan', icon: 'bi-person-badge' },
    { to: '/jadwal', label: 'Jadwal Posyandu', icon: 'bi-calendar-event' },
    { to: '/blog', label: 'Blog & Berita', icon: 'bi-newspaper' },
    { to: '/laporan', label: 'Laporan (PDF/CSV)', icon: 'bi-file-earmark-text' }
  ]
};

const PANEL_SUBTITLE: Record<UserRole, string> = {
  kader: 'Panel Kader Desa',
  bidan: 'Panel Bidan Desa',
  admin: 'Panel Admin Desa'
};

const USER_ROLE_TEXT: Record<UserRole, string> = {
  kader: 'Kader Posyandu',
  bidan: 'Bidan Desa',
  admin: 'Administrator'
};

const menuClass = (isActive: boolean) =>
  `w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-base text-left ${
    isActive
      ? 'bg-[#E3F2FD] text-[#0D47A1]'
      : 'text-[#3D5F7D] hover:bg-[#F0F7FF] hover:text-[#0D47A1]'
  }`;

export const Sidebar: React.FC = () => {
  const { user, logout } = useAuth();
  const navigate = useNavigate();
  const { pathname } = useLocation();

  const role = user?.role || 'kader';
  const menuItems = MENU_ITEMS[role];
  const isActive = (to: string) => pathname === to || (to === '/balita' && pathname.startsWith('/balita/'));

  const handleLogout = () => {
    logout();
    navigate('/login');
  };

  return (
    <aside className="app-sidebar w-64 bg-white border-r border-line flex flex-col h-screen sticky top-0 shrink-0 select-none z-30">
      <div className="flex-1 min-h-0 overflow-y-auto">
        <div className="p-5 border-b border-line flex items-center gap-3">
          <div className="sidebar-brand-mark hidden md:flex w-9 h-9 rounded-xl bg-white border border-line overflow-hidden items-center justify-center shrink-0">
            <img src="/images/logo sementara.png" alt="Logo Posyandu Pintar" className="w-full h-full object-contain" />
          </div>
          <div>
            <h1 className="font-bold text-[#0D47A1] text-base leading-tight">Posyandu Pintar</h1>
            <span className="text-xs text-[#607D99] font-medium">{PANEL_SUBTITLE[role]}</span>
          </div>
        </div>

        <div className="p-4 space-y-6">
          <div>
            <div className="px-3 mb-2 text-[11px] font-bold text-[#607D99] tracking-wider uppercase">
              Menu Utama
            </div>
            <nav className="space-y-1">
              {menuItems.map((item) => (
                <button
                  key={item.to}
                  onClick={() => navigate(item.to)}
                  className={menuClass(isActive(item.to))}
                >
                  <i className={`bi ${item.icon} text-base shrink-0 ${isActive(item.to) ? 'text-[#0D47A1]' : 'text-[#607D99]'}`}></i>
                  <span>{item.label}</span>
                </button>
              ))}
            </nav>
          </div>

          <div>
            <div className="px-3 mb-2 text-[11px] font-bold text-[#607D99] tracking-wider uppercase">
              Akun &amp; Panduan
            </div>
            <nav className="space-y-1">
              <button onClick={() => navigate('/profil')} className={menuClass(isActive('/profil'))}>
                <i className="bi bi-person text-base text-[#607D99] shrink-0"></i>
                <span>Profil Petugas</span>
              </button>
              <button
                onClick={() => navigate('/')}
                className="w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold text-[#3D5F7D] hover:bg-[#F0F7FF] hover:text-[#0D47A1] transition-base text-left"
              >
                <i className="bi bi-box-arrow-up-right text-base text-[#607D99] shrink-0"></i>
                <span>Portal Publik</span>
              </button>
            </nav>
          </div>
        </div>
      </div>

      <div className="p-4 border-t border-line flex items-center justify-between">
        <div className="flex items-center gap-3 overflow-hidden">
          <div className="w-10 h-10 rounded-full bg-[#0D47A1] text-white font-bold text-xs flex items-center justify-center shrink-0">
            {user?.nama.charAt(0)}
          </div>
          <div className="overflow-hidden">
            <div className="font-bold text-xs text-[#0D47A1] truncate">{user?.nama}</div>
            <div className="text-[11px] text-[#607D99] truncate">
              {user?.dusun_binaan && role === 'kader' ? `Kader · ${user.dusun_binaan}` : USER_ROLE_TEXT[role]}
            </div>
          </div>
        </div>

        <button
          onClick={handleLogout}
          title="Keluar"
          className="w-8 h-8 rounded-lg flex items-center justify-center text-rose-500 hover:bg-rose-50 transition-base shrink-0"
        >
          <i className="bi bi-box-arrow-right text-lg"></i>
        </button>
      </div>
    </aside>
  );
};
