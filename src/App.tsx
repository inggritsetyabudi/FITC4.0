import { useEffect } from 'react';
import { Navigate, Outlet, RouterProvider, createBrowserRouter, useLocation } from 'react-router-dom';
import { AuthProvider, useAuth } from './context/AuthContext';
import type { UserRole } from './types';
import { LandingLayout } from './components/layout/LandingLayout';
import { DashboardLayout } from './components/layout/DashboardLayout';
import { LandingPage } from './pages/LandingPage';
import { LoginPage } from './pages/LoginPage';
import { DashboardPage } from './pages/DashboardPage';
import { BalitaPage } from './pages/BalitaPage';
import { PengukuranPage } from './pages/PengukuranPage';
import { PrioritasPage } from './pages/PrioritasPage';
import { RekapPage } from './pages/RekapPage';
import { PetugasPage } from './pages/PetugasPage';
import { JadwalPage } from './pages/JadwalPage';
import { BlogPage } from './pages/BlogPage';
import { LaporanPage } from './pages/LaporanPage';
import { DetailBalitaPage } from './pages/DetailBalitaPage';
import { DetailBeritaPage } from './pages/DetailBeritaPage';
import { ProfilPage } from './pages/ProfilPage';
import { NotFoundPage } from './pages/NotFoundPage';

function RequireAuth() {
  const { user, loading } = useAuth();
  if (loading) return null;
  return user ? <Outlet /> : <Navigate to="/login" replace />;
}

function RequireRole({ roles }: { roles: UserRole[] }) {
  const { user, loading } = useAuth();
  if (loading) return null;
  if (!user) return <Navigate to="/login" replace />;
  if (!roles.includes(user.role)) return <Navigate to="/dashboard" replace />;
  return <Outlet />;
}

function ScrollToTop() {
  const { pathname } = useLocation();

  useEffect(() => {
    window.scrollTo(0, 0);
  }, [pathname]);

  return null;
}

const router = createBrowserRouter([
  {
    element: (
      <>
        <ScrollToTop />
        <LandingLayout />
      </>
    ),
    children: [
      { path: '/', element: <LandingPage /> },
      { path: '/berita/:id', element: <DetailBeritaPage /> },
      { path: '/panduan', element: <DetailBeritaPage /> }
    ]
  },
  { path: '/login', element: <LoginPage /> },
  {
    element: (
      <>
        <ScrollToTop />
        <RequireAuth />
      </>
    ),
    children: [
      {
        element: <DashboardLayout />,
        children: [
          { path: '/dashboard', element: <DashboardPage /> },
          { path: '/profil', element: <ProfilPage /> },
          {
            element: <RequireRole roles={['admin']} />,
            children: [
              { path: '/petugas', element: <PetugasPage /> },
              { path: '/jadwal', element: <JadwalPage /> },
              { path: '/laporan', element: <LaporanPage /> }
            ]
          },
          {
            element: <RequireRole roles={['bidan', 'admin']} />,
            children: [{ path: '/blog', element: <BlogPage /> }]
          },
          { path: '/balita', element: <BalitaPage /> },
          { path: '/balita/:id', element: <DetailBalitaPage /> },
          { path: '/pengukuran', element: <PengukuranPage /> },
          { path: '/prioritas', element: <PrioritasPage /> },
          { path: '/rekap', element: <RekapPage /> },
          { path: '*', element: <NotFoundPage /> }
        ]
      }
    ]
  }
]);

export default function App() {
  return (
    <AuthProvider>
      <RouterProvider router={router} />
    </AuthProvider>
  );
}
