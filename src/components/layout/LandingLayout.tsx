import React from 'react';
import { Navbar } from './Navbar';
import { Footer } from './Footer';

export const LandingLayout: React.FC<{
  children: React.ReactNode;
  currentPage: string;
  onNavigate: (page: string) => void;
}> = ({ children, currentPage, onNavigate }) => {
  return (
    <div className="public-layout min-h-screen bg-canvas flex flex-col font-sans">
      <Navbar currentPage={currentPage} onNavigate={onNavigate} />
      <main className="flex-1">{children}</main>
      {currentPage === 'landing' && <Footer onNavigate={onNavigate} />}
    </div>
  );
};
