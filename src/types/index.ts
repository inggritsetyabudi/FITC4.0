export type UserRole = 'kader' | 'bidan' | 'admin';

export interface User {
  id: number;
  nama: string;
  username: string;
  role: UserRole;
  dusun_binaan?: string | null;
  telepon?: string | null;
  created_at?: string;
}

export type StatusGizi = 'normal' | 'pemantauan' | 'prioritas' | 'berisiko';

export interface Balita {
  id: number;
  nama: string;
  nik?: string | null;
  tanggal_lahir: string;
  jenis_kelamin: 'L' | 'P';
  nama_ortu: string;
  dusun: string;
  status?: 'aktif' | 'nonaktif';
  status_terakhir?: StatusGizi | null;
  berat_terakhir?: number | null;
  tinggi_terakhir?: number | null;
  tanggal_ukur_terakhir?: string | null;
}

export interface Pengukuran {
  id: number;
  anak_id: number;
  nama_anak?: string;
  dusun?: string;
  jenis_kelamin?: 'L' | 'P';
  nama_ortu?: string;
  tanggal_ukur: string;
  usia_bulan: number;
  berat_kg: number;
  tinggi_cm: number;
  kategori_status: StatusGizi;
  catatan?: string | null;
  dicatat_oleh_id?: number | null;
}

export interface Berita {
  id: number;
  judul: string;
  kategori: string;
  tipe?: 'blog' | 'berita';
  ringkasan: string;
  isi: string;
  gambar?: string | null;
  status?: 'draft' | 'published';
  published_at?: string | null;
  penulis?: string;
  waktu_baca?: string;
}

export interface Jadwal {
  id: number;
  nama_kegiatan: string;
  dusun: string;
  lokasi: string;
  tanggal: string;
  waktu_mulai: string;
  waktu_selesai: string;
  status: 'rencana' | 'selesai';
  petugas?: string;
  keterangan?: string;
  catatan?: string;
}

export interface Umur {
  tahun: number;
  bulan: number;
  hari: number;
  total_bulan: number;
  teks: string;
}
