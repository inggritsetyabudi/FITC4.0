import { useState } from 'react';
import { AuthProvider } from './context/AuthContext';
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
import { LaporanPage } from './pages/LaporanPage';
import { DetailBalitaPage } from './pages/DetailBalitaPage';
import { DetailBeritaPage } from './pages/DetailBeritaPage';
import { ProfilPage } from './pages/ProfilPage';
import { NotFoundPage } from './pages/NotFoundPage';

export function AppContent() {
  const [currentPage, setCurrentPage] = useState<string>('landing');
  const [pageParams, setPageParams] = useState<any>({});

  const handleNavigate = (page: string, params: any = {}) => {
    setCurrentPage(page);
    setPageParams(params);
    window.scrollTo({ top: 0, behavior: 'smooth' });
  };

  switch (currentPage) {
    case 'landing':
      return (
        <LandingLayout currentPage={currentPage} onNavigate={handleNavigate}>
          <LandingPage onNavigate={handleNavigate} />
        </LandingLayout>
      );

    case 'login':
      return <LoginPage onNavigate={handleNavigate} />;

    case 'dashboard':
    case 'kader_dashboard':
    case 'bidan_dashboard':
    case 'admin_dashboard':
      return (
        <DashboardLayout
          currentPage="dashboard"
          title="Dasbor Kerja Posyandu"
          subtitle="Tinjauan penimbangan bulanan dan kesehatan balita desa"
          onNavigate={handleNavigate}
        >
          <DashboardPage onNavigate={handleNavigate} />
        </DashboardLayout>
      );

    case 'balita':
      return (
        <DashboardLayout
          currentPage={currentPage}
          title="Pengelolaan Data Balita"
          subtitle="Pencarian, pemfilteran, dan pencatatan sasaran anak posyandu"
          onNavigate={handleNavigate}
        >
          <BalitaPage onNavigate={handleNavigate} />
        </DashboardLayout>
      );

    case 'pengukuran':
      return (
        <DashboardLayout
          currentPage={currentPage}
          title="Catat Pengukuran"
          subtitle="Formulir penimbangan berat dan tinggi badan dengan validasi anomali"
          onNavigate={handleNavigate}
        >
          <PengukuranPage
            preselectedId={pageParams?.preselectedId}
            onNavigate={handleNavigate}
          />
        </DashboardLayout>
      );

    case 'prioritas':
      return (
        <DashboardLayout
          currentPage={currentPage}
          title="Daftar Balita Prioritas"
          subtitle="Balita yang memerlukan intervensi gizi, konseling, atau rujukan klinis"
          onNavigate={handleNavigate}
        >
          <PrioritasPage onNavigate={handleNavigate} />
        </DashboardLayout>
      );

    case 'rekap':
      return (
        <DashboardLayout
          currentPage={currentPage}
          title="Rekapitulasi Pengukuran"
          subtitle="Arsip data penimbangan bulanan dan unduh spreadsheet CSV"
          onNavigate={handleNavigate}
        >
          <RekapPage onNavigate={handleNavigate} />
        </DashboardLayout>
      );

    case 'petugas':
    case 'admin_kader':
      return (
        <DashboardLayout
          currentPage="petugas"
          title="Kelola Petugas Posyandu"
          subtitle="Manajemen akun dan penugasan dusun Kader serta Bidan Desa"
          onNavigate={handleNavigate}
        >
          <PetugasPage onNavigate={handleNavigate} />
        </DashboardLayout>
      );

    case 'jadwal':
    case 'admin_jadwal':
      return (
        <DashboardLayout
          currentPage="jadwal"
          title="Jadwal Pelayanan Posyandu"
          subtitle="Manajemen agenda penimbangan serentak dan imunisasi desa"
          onNavigate={handleNavigate}
        >
          <JadwalPage onNavigate={handleNavigate} />
        </DashboardLayout>
      );

    case 'laporan':
    case 'admin_laporan':
      return (
        <DashboardLayout
          currentPage="laporan"
          title="Laporan Rekapitulasi Balita"
          subtitle="Cetak laporan resmi desa atau ekspor dataset CSV"
          onNavigate={handleNavigate}
        >
          <LaporanPage onNavigate={handleNavigate} />
        </DashboardLayout>
      );

    case 'detail_balita':
      return (
        <DashboardLayout
          currentPage={currentPage}
          title="Buku KMS Digital Balita"
          subtitle="Rekam riwayat tumbuh kembang dan kurva pertumbuhan anak"
          onNavigate={handleNavigate}
        >
          <DetailBalitaPage id={pageParams?.id || 1} onNavigate={handleNavigate} />
        </DashboardLayout>
      );

    case 'detail_panduan':
    case 'detail_berita':
      return (
        <LandingLayout currentPage={currentPage} onNavigate={handleNavigate}>
          <DetailBeritaPage id={currentPage === 'detail_panduan' ? 4 : pageParams?.id || 1} onNavigate={handleNavigate} />
        </LandingLayout>
      );

    case 'profil':
      return (
        <DashboardLayout
          currentPage={currentPage}
          title="Profil Petugas"
          subtitle="Kelola identitas, nomor kontak, dan preferensi sesi"
          onNavigate={handleNavigate}
        >
          <ProfilPage onNavigate={handleNavigate} />
        </DashboardLayout>
      );

    default:
      return (
        <DashboardLayout
          currentPage={currentPage}
          title="Halaman Tidak Ditemukan"
          onNavigate={handleNavigate}
        >
          <NotFoundPage onNavigate={handleNavigate} />
        </DashboardLayout>
      );
  }
}

export default function App() {
  return (
    <AuthProvider>
      <AppContent />
    </AuthProvider>
  );
}
