import React from 'react';
import { Link, useLocation } from 'react-router-dom';
import { useAuth } from '../../context/AuthContext';
import type { UserRole } from '../../types';

const ROLE_LABELS: Record<UserRole, string> = {
  kader: 'Kader Posyandu',
  bidan: 'Bidan Desa',
  admin: 'Admin Posyandu'
};

const CRUMBS: Record<string, string> = {
  '/balita': 'Data Balita',
  '/pengukuran': 'Catat Pengukuran',
  '/prioritas': 'Balita Prioritas',
  '/rekap': 'Rekap Pengukuran',
  '/petugas': 'Petugas',
  '/jadwal': 'Jadwal',
  '/laporan': 'Laporan',
  '/profil': 'Profil Petugas'
};

export const Topbar: React.FC = () => {
  const { user } = useAuth();
  const { pathname } = useLocation();

  const role = user?.role || 'kader';
  const currentLabel = pathname.startsWith('/balita/')
    ? 'Detail Balita'
    : pathname === '/dashboard'
      ? null
      : CRUMBS[pathname] ?? null;

  return (
    <header className="app-topbar bg-white border-b border-line px-8 py-3.5 flex items-center justify-between sticky top-0 z-20 shadow-xs">
      <div className="flex items-center gap-2 text-xs font-medium text-[#607D99]">
        <Link to="/dashboard">Dasbor</Link>
        {currentLabel && (
          <>
            <i className="bi bi-chevron-right" />
            <span>{currentLabel}</span>
          </>
        )}
      </div>

      <div className="flex items-center gap-3">
        <div className="topbar-role-label flex items-center gap-2 text-[#1976D2] text-xs font-semibold">
          <i className="bi bi-shield-check text-sm"></i>
          <span>{ROLE_LABELS[role]}</span>
        </div>

        <Link
          to="/profil"
          className="w-8 h-8 rounded-full bg-[#0D47A1] text-white font-bold text-xs flex items-center justify-center shadow-xs"
        >
          {user?.nama.charAt(0)}
        </Link>
      </div>
    </header>
  );
};
