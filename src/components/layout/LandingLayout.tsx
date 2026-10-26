import React from 'react';
import { Outlet, useLocation } from 'react-router-dom';
import { Navbar } from './Navbar';
import { Footer } from './Footer';

export const LandingLayout: React.FC = () => {
  const { pathname } = useLocation();

  return (
    <div className="public-layout min-h-screen bg-canvas flex flex-col font-sans">
      <Navbar />
      <main className="flex-1">
        <Outlet />
      </main>
      {pathname === '/' && <Footer />}
    </div>
  );
};
