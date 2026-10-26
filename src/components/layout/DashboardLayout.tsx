import React from 'react';
import { Outlet, useLocation } from 'react-router-dom';
import { Sidebar } from './Sidebar';
import { Topbar } from './Topbar';

export const DashboardLayout: React.FC<{ children?: React.ReactNode }> = ({ children }) => {
  const { pathname } = useLocation();
  const pageKey = pathname.split('/').filter(Boolean)[0] ?? '';

  return (
    <div className="app-layout min-h-screen bg-[#E3F2FD] flex font-sans">
      <Sidebar />
      <div className="flex-1 flex flex-col min-w-0">
        <Topbar />
        <main className="app-content flex-1" data-page={pageKey}>
          {children ?? <Outlet />}
        </main>
      </div>
    </div>
  );
};
