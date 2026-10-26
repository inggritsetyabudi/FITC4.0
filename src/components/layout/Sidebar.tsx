import React from 'react';
import { useAuth } from '../../context/AuthContext';

export const Sidebar: React.FC<{ currentPage: string; onNavigate: (page: string) => void }> = ({ currentPage, onNavigate }) => {
  const { user, logout } = useAuth();
  const role = user?.role || 'kader';

  const menuItems = {
    kader: [
      { id: 'dashboard', label: 'Beranda Tugas', icon: 'bi-house-door' },
      { id: 'balita', label: 'Data Balita', icon: 'bi-people' },
      { id: 'pengukuran', label: 'Catat Pengukuran', icon: 'bi-clipboard-plus' }
    ],
    bidan: [
      { id: 'dashboard', label: 'Overview & Prioritas', icon: 'bi-columns-gap' },
      { id: 'prioritas', label: 'Balita Prioritas', icon: 'bi-exclamation-triangle' },
      { id: 'rekap', label: 'Rekap Pengukuran', icon: 'bi-journal-text' }
    ],
    admin: [
      { id: 'dashboard', label: 'Ringkasan Dasbor', icon: 'bi-speedometer2' },
      { id: 'balita', label: 'Data Balita', icon: 'bi-people' },
      { id: 'petugas', label: 'Kader & Bidan', icon: 'bi-person-badge' },
      { id: 'jadwal', label: 'Jadwal Posyandu', icon: 'bi-calendar-event' },
      { id: 'laporan', label: 'Laporan (PDF/CSV)', icon: 'bi-file-earmark-text' }
    ]
  }[role];

  const panelSubtitle = {
    kader: 'Panel Kader Desa',
    bidan: 'Panel Bidan Desa',
    admin: 'Panel Admin Desa'
  }[role];

  const userInitials = {
    kader: 'SR',
    bidan: 'AL',
    admin: 'AD'
  }[role] || 'P';

  const userRoleText = {
    kader: user?.dusun_binaan ? `Kader · ${user.dusun_binaan}` : 'Kader Posyandu',
    bidan: 'Bidan Desa',
    admin: 'Administrator'
  }[role];

  return (
    <aside className="app-sidebar w-64 bg-white border-r border-line flex flex-col justify-between h-screen sticky top-0 shrink-0 select-none z-30">
      <div>
        <div className="p-5 border-b border-line flex items-center gap-3">
          <div className="w-9 h-9 rounded-xl bg-[#2196F3] flex items-center justify-center text-white text-lg shadow-xs shrink-0">
            <i className="bi bi-heart-fill"></i>
          </div>
          <div>
            <h1 className="font-bold text-[#0D47A1] text-base leading-tight">Posyandu Pintar</h1>
            <span className="text-xs text-[#607D99] font-medium">
              {panelSubtitle}
            </span>
          </div>
        </div>

        <div className="p-4 space-y-6">
          <div>
            <div className="px-3 mb-2 text-[11px] font-bold text-[#607D99] tracking-wider uppercase">
              Menu Utama
            </div>
            <nav className="space-y-1">
              {menuItems.map((item) => {
                const isActive = currentPage === item.id || (currentPage === 'detail_balita' && item.id === 'balita');
                return (
                  <button
                    key={item.id}
                    onClick={() => onNavigate(item.id)}
                    className={`w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-base text-left ${
                      isActive
                        ? 'bg-[#E3F2FD] text-[#0D47A1]'
                        : 'text-[#3D5F7D] hover:bg-[#F0F7FF] hover:text-[#0D47A1]'
                    }`}
                  >
                    <i className={`bi ${item.icon} text-base shrink-0 ${isActive ? 'text-[#0D47A1]' : 'text-[#607D99]'}`}></i>
                    <span>{item.label}</span>
                  </button>
                );
              })}
            </nav>
          </div>

          <div>
            <div className="px-3 mb-2 text-[11px] font-bold text-[#607D99] tracking-wider uppercase">
              Akun &amp; Panduan
            </div>
            <nav className="space-y-1">
              <button
                onClick={() => onNavigate('profil')}
                className={`w-full flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs font-semibold transition-base text-left ${
                  currentPage === 'profil'
                    ? 'bg-[#E3F2FD] text-[#0D47A1]'
                    : 'text-[#3D5F7D] hover:bg-[#F0F7FF] hover:text-[#0D47A1]'
                }`}
              >
                <i className="bi bi-person text-base text-[#607D99] shrink-0"></i>
                <span>Profil Petugas</span>
              </button>
              <button
                onClick={() => onNavigate('landing')}
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
            {userInitials}
          </div>
          <div className="overflow-hidden">
            <div className="font-bold text-xs text-[#0D47A1] truncate">{user?.nama}</div>
            <div className="text-[11px] text-[#607D99] truncate">
              {userRoleText}
            </div>
          </div>
        </div>

        <button
          onClick={() => {
            logout();
            onNavigate('login');
          }}
          title="Keluar"
          className="w-8 h-8 rounded-lg flex items-center justify-center text-rose-500 hover:bg-rose-50 transition-base shrink-0"
        >
          <i className="bi bi-box-arrow-right text-lg"></i>
        </button>
      </div>
    </aside>
  );
};
