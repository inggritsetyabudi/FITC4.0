import type { Umur, StatusGizi } from '../types';

export function formatTanggalId(dateStr?: string | null, singkat = false): string {
  if (!dateStr) return '-';
  const date = new Date(dateStr);
  if (isNaN(date.getTime())) return dateStr;

  const bulan = [
    'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
    'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
  ];
  const bulanSingkat = [
    'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
    'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'
  ];

  const tgl = date.getDate();
  const bln = singkat ? bulanSingkat[date.getMonth()] : bulan[date.getMonth()];
  const thn = date.getFullYear();

  return `${tgl} ${bln} ${thn}`;
}

export function hitungUmur(tanggalLahirStr: string, tanggalPatokanStr?: string): Umur {
  const tglLahir = new Date(tanggalLahirStr);
  const tglPatokan = tanggalPatokanStr ? new Date(tanggalPatokanStr) : new Date();

  if (isNaN(tglLahir.getTime())) {
    return { tahun: 0, bulan: 0, hari: 0, total_bulan: 0, teks: '-' };
  }

  let tahun = tglPatokan.getFullYear() - tglLahir.getFullYear();
  let bulan = tglPatokan.getMonth() - tglLahir.getMonth();
  let hari = tglPatokan.getDate() - tglLahir.getDate();

  if (hari < 0) {
    bulan--;
    const prevMonthDays = new Date(tglPatokan.getFullYear(), tglPatokan.getMonth(), 0).getDate();
    hari += prevMonthDays;
  }

  if (bulan < 0) {
    tahun--;
    bulan += 12;
  }

  const totalBulan = tahun * 12 + bulan;

  let teks: string;
  if (tahun > 0) {
    teks = bulan > 0 ? `${tahun} tahun ${bulan} bulan` : `${tahun} tahun`;
  } else if (bulan > 0) {
    teks = `${bulan} bulan`;
  } else {
    teks = `${hari} hari`;
  }

  return { tahun, bulan, hari, total_bulan: totalBulan, teks };
}

export const STATUS_CONFIG: Record<StatusGizi, { label: string; icon: string; className: string }> = {
  normal: {
    label: 'Normal',
    icon: 'bi-check-circle-fill',
    className: 'bg-success-soft text-success border border-success-border'
  },
  pemantauan: {
    label: 'Perlu Pemantauan',
    icon: 'bi-exclamation-circle-fill',
    className: 'bg-warning-soft text-warning border border-warning-border'
  },
  prioritas: {
    label: 'Prioritas Bidan',
    icon: 'bi-exclamation-triangle-fill',
    className: 'bg-danger-soft text-danger border border-danger-border'
  },
  berisiko: {
    label: 'Risiko Tinggi',
    icon: 'bi-exclamation-triangle-fill',
    className: 'bg-danger-soft text-danger border border-danger-border'
  }
};
