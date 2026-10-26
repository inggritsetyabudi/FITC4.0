import React from 'react';
import { useAuth } from '../../context/AuthContext';

export const Topbar: React.FC<{
  currentPage: string;
  title: string;
  subtitle?: string;
  onNavigate: (page: string) => void;
}> = ({ currentPage, onNavigate }) => {
  const { user } = useAuth();
  const role = user?.role || 'kader';

  const roleLabels = {
    kader: 'Kader Posyandu',
    bidan: 'Bidan Desa',
    admin: 'Admin Posyandu'
  };

  const userInitials = {
    kader: 'SR',
    bidan: 'AL',
    admin: 'AD'
  }[role] || 'P';

  return (
    <header className="app-topbar bg-white border-b border-line px-8 py-3.5 flex items-center justify-between sticky top-0 z-20 shadow-xs">
      <div className="flex items-center gap-2 text-xs font-medium text-[#607D99]">
        <button onClick={() => onNavigate('dashboard')}>Dasbor</button>
        {['balita', 'profil', 'detail_balita'].includes(currentPage) && <><i className="bi bi-chevron-right" /><span>{currentPage === 'balita' ? 'Data Balita' : currentPage === 'profil' ? 'Profil Petugas' : 'Detail Balita'}</span></>}
      </div>

      <div className="flex items-center gap-3">

        <div className="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-[#E3F2FD] border border-[#90CAF9] text-[#1976D2] text-xs font-bold">
          <i className="bi bi-shield-check text-xs"></i>
          <span>{roleLabels[role]}</span>
        </div>

        <button
          onClick={() => onNavigate('profil')}
          className="w-8 h-8 rounded-full bg-[#0D47A1] text-white font-bold text-xs flex items-center justify-center shadow-xs"
        >
          {userInitials}
        </button>
      </div>
    </header>
  );
};
