import React from 'react';
import { Sidebar } from './Sidebar';
import { Topbar } from './Topbar';

export const DashboardLayout: React.FC<{
  children: React.ReactNode;
  currentPage: string;
  title: string;
  subtitle?: string;
  onNavigate: (page: string) => void;
}> = ({ children, currentPage, title, subtitle, onNavigate }) => {
  return (
    <div className="app-layout min-h-screen bg-[#E3F2FD] flex font-sans">
      <Sidebar currentPage={currentPage} onNavigate={onNavigate} />
      <div className="flex-1 flex flex-col min-w-0">
        <Topbar currentPage={currentPage} title={title} subtitle={subtitle} onNavigate={onNavigate} />
        <main className="app-content flex-1" data-page={currentPage}>{children}</main>
      </div>
    </div>
  );
};
