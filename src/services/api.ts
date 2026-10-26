import type { Balita, Pengukuran, User, Berita, Jadwal } from '../types';
import {
  INITIAL_BALITA,
  INITIAL_PENGUKURAN,
  INITIAL_USERS,
  INITIAL_BERITA,
  INITIAL_JADWAL
} from '../utils/mockData';

const STORAGE_PREFIX = 'posyandu_pintar_v4_';

function getStorage<T>(key: string, defaultVal: T): T {
  try {
    const item = localStorage.getItem(STORAGE_PREFIX + key);
    return item ? JSON.parse(item) : defaultVal;
  } catch {
    return defaultVal;
  }
}

function setStorage<T>(key: string, val: T): void {
  try {
    localStorage.setItem(STORAGE_PREFIX + key, JSON.stringify(val));
  } catch (e) {
    console.error(e);
  }
}

if (!localStorage.getItem(STORAGE_PREFIX + 'balita')) {
  setStorage('balita', INITIAL_BALITA);
}
if (!localStorage.getItem(STORAGE_PREFIX + 'pengukuran')) {
  setStorage('pengukuran', INITIAL_PENGUKURAN);
}
if (!localStorage.getItem(STORAGE_PREFIX + 'users')) {
  setStorage('users', INITIAL_USERS);
}
if (!localStorage.getItem(STORAGE_PREFIX + 'berita')) {
  setStorage('berita', INITIAL_BERITA);
}
if (!localStorage.getItem(STORAGE_PREFIX + 'jadwal')) {
  setStorage('jadwal', INITIAL_JADWAL);
}

async function fetchApi<T>(endpoint: string, options: RequestInit = {}): Promise<{ success: boolean; data?: T; [key: string]: any }> {
  try {
    const res = await fetch(`/api/${endpoint}`, {
      ...options,
      headers: {
        'Content-Type': 'application/json',
        ...(options.headers || {})
      }
    });
    if (res.ok) {
      return await res.json();
    }
    throw new Error(`API error ${res.status}`);
  } catch (err) {
    return { success: false, isOffline: true, error: err };
  }
}

export const authService = {
  async login(username: string, _password?: string): Promise<{ success: boolean; user?: User; message?: string }> {
    const apiRes = await fetchApi<User>('auth/login', {
      method: 'POST',
      body: JSON.stringify({ username, password: _password })
    });

    if (apiRes.success && apiRes.user) {
      setStorage('currentUser', apiRes.user);
      return { success: true, user: apiRes.user };
    }

    const users = getStorage<User[]>('users', INITIAL_USERS);
    const found = users.find(u => u.username.toLowerCase() === username.toLowerCase() || u.role === username);
    if (found) {
      setStorage('currentUser', found);
      return { success: true, user: found };
    }

    const defaultKader = users[0];
    setStorage('currentUser', defaultKader);
    return { success: true, user: defaultKader };
  },

  getCurrentUser(): User | null {
    return getStorage<User | null>('currentUser', INITIAL_USERS[0]);
  },

  logout(): void {
    fetchApi('auth/logout', { method: 'POST' }).catch(() => {});
    localStorage.removeItem(STORAGE_PREFIX + 'currentUser');
  }
};

export const balitaService = {
  async getAll(dusun?: string, search?: string): Promise<Balita[]> {
    const query = new URLSearchParams();
    if (dusun && dusun !== 'semua') query.append('dusun', dusun);
    if (search) query.append('q', search);

    const res = await fetchApi<Balita[]>(`balita?${query.toString()}`);
    if (res.success && Array.isArray(res.data)) {
      return res.data;
    }

    let list = getStorage<Balita[]>('balita', INITIAL_BALITA);
    if (dusun && dusun !== 'semua') {
      list = list.filter(b => b.dusun.toLowerCase() === dusun.toLowerCase());
    }
    if (search && search.trim() !== '') {
      const q = search.toLowerCase();
      list = list.filter(b =>
        b.nama.toLowerCase().includes(q) ||
        (b.nik && b.nik.includes(q)) ||
        b.nama_ortu.toLowerCase().includes(q)
      );
    }
    return list;
  },

  async getById(id: number): Promise<Balita | undefined> {
    const list = await this.getAll();
    return list.find(b => b.id === Number(id));
  },

  async getDetail(id: number): Promise<{ anak: Balita; riwayat: Pengukuran[] } | null> {
    const res = await fetchApi<{ anak: Balita; riwayat: Pengukuran[] }>(`balita/detail?id=${id}`);
    if (res.success && res.anak) {
      return { anak: res.anak, riwayat: res.riwayat || [] };
    }

    const list = getStorage<Balita[]>('balita', INITIAL_BALITA);
    const found = list.find(b => b.id === id);
    if (!found) return null;

    const allPengukuran = getStorage<Pengukuran[]>('pengukuran', INITIAL_PENGUKURAN);
    const riwayat = allPengukuran
      .filter(p => p.anak_id === id)
      .sort((a, b) => new Date(a.tanggal_ukur).getTime() - new Date(b.tanggal_ukur).getTime());

    return { anak: found, riwayat };
  },

  async create(data: Omit<Balita, 'id'>): Promise<Balita> {
    const res = await fetchApi<{ id: number }>('balita', {
      method: 'POST',
      body: JSON.stringify(data)
    });

    const list = getStorage<Balita[]>('balita', INITIAL_BALITA);
    const newId = res.success && res.id ? res.id : (list.length > 0 ? Math.max(...list.map(b => b.id)) + 1 : 1);
    const newBalita: Balita = {
      ...data,
      id: newId,
      status: 'aktif'
    };

    list.unshift(newBalita);
    setStorage('balita', list);
    return newBalita;
  },

  async update(id: number, data: Partial<Balita>): Promise<boolean> {
    fetchApi(`balita?id=${id}`, {
      method: 'PUT',
      body: JSON.stringify(data)
    }).catch(() => {});

    const list = getStorage<Balita[]>('balita', INITIAL_BALITA);
    const idx = list.findIndex(b => b.id === id);
    if (idx !== -1) {
      list[idx] = { ...list[idx], ...data };
      setStorage('balita', list);
      return true;
    }
    return false;
  },

  async delete(id: number): Promise<boolean> {
    fetchApi(`balita?id=${id}`, { method: 'DELETE' }).catch(() => {});

    const list = getStorage<Balita[]>('balita', INITIAL_BALITA);
    const filtered = list.filter(b => b.id !== id);
    setStorage('balita', filtered);
    return true;
  }
};

export const pengukuranService = {
  async getAll(bulan?: string, dusun?: string, status?: string): Promise<Pengukuran[]> {
    const q = new URLSearchParams();
    if (bulan && bulan !== 'semua') q.append('bulan', bulan);
    if (dusun && dusun !== 'semua') q.append('dusun', dusun);
    if (status && status !== 'semua') q.append('status', status);

    const res = await fetchApi<Pengukuran[]>(`pengukuran?${q.toString()}`);
    if (res.success && Array.isArray(res.data)) {
      return res.data;
    }

    let list = getStorage<Pengukuran[]>('pengukuran', INITIAL_PENGUKURAN);
    if (bulan && bulan !== 'semua') {
      list = list.filter(p => p.tanggal_ukur.startsWith(bulan));
    }
    if (dusun && dusun !== 'semua') {
      list = list.filter(p => p.dusun?.toLowerCase() === dusun.toLowerCase());
    }
    if (status && status !== 'semua') {
      list = list.filter(p => p.kategori_status === status);
    }
    return list.sort((a, b) => new Date(b.tanggal_ukur).getTime() - new Date(a.tanggal_ukur).getTime());
  },

  async getByBalitaId(anakId: number): Promise<Pengukuran[]> {
    const list = await this.getAll();
    return list
      .filter(p => p.anak_id === Number(anakId))
      .sort((a, b) => new Date(a.tanggal_ukur).getTime() - new Date(b.tanggal_ukur).getTime());
  },

  async create(data: Omit<Pengukuran, 'id'>): Promise<Pengukuran> {
    const res = await fetchApi<{ id: number }>('pengukuran', {
      method: 'POST',
      body: JSON.stringify(data)
    });

    const list = getStorage<Pengukuran[]>('pengukuran', INITIAL_PENGUKURAN);
    const newId = res.success && res.id ? res.id : (list.length > 0 ? Math.max(...list.map(p => p.id)) + 1 : 1);
    const newPengukuran: Pengukuran = { ...data, id: newId };

    list.unshift(newPengukuran);
    setStorage('pengukuran', list);

    const balitaList = getStorage<Balita[]>('balita', INITIAL_BALITA);
    const bIdx = balitaList.findIndex(b => b.id === data.anak_id);
    if (bIdx !== -1) {
      balitaList[bIdx].status_terakhir = data.kategori_status;
      balitaList[bIdx].berat_terakhir = data.berat_kg;
      balitaList[bIdx].tinggi_terakhir = data.tinggi_cm;
      balitaList[bIdx].tanggal_ukur_terakhir = data.tanggal_ukur;
      setStorage('balita', balitaList);
    }

    return newPengukuran;
  }
};

export const beritaService = {
  async getAll(): Promise<Berita[]> {
    const res = await fetchApi<Berita[]>('berita');
    if (res.success && Array.isArray(res.data)) {
      return res.data;
    }
    return getStorage<Berita[]>('berita', INITIAL_BERITA);
  },

  async getById(id: number): Promise<Berita | undefined> {
    const list = await this.getAll();
    return list.find(b => b.id === Number(id));
  }
};

export const jadwalService = {
  async getAll(): Promise<Jadwal[]> {
    const res = await fetchApi<Jadwal[]>('jadwal');
    if (res.success && Array.isArray(res.data)) {
      return res.data;
    }
    return getStorage<Jadwal[]>('jadwal', INITIAL_JADWAL);
  },

  async create(data: Omit<Jadwal, 'id'>): Promise<Jadwal> {
    const res = await fetchApi<{ id: number }>('jadwal', {
      method: 'POST',
      body: JSON.stringify(data)
    });

    const list = getStorage<Jadwal[]>('jadwal', INITIAL_JADWAL);
    const newId = res.success && res.id ? res.id : (list.length > 0 ? Math.max(...list.map(j => j.id)) + 1 : 1);
    const newJadwal: Jadwal = { ...data, id: newId };

    list.unshift(newJadwal);
    setStorage('jadwal', list);
    return newJadwal;
  },

  async update(id: number, data: Partial<Jadwal>): Promise<boolean> {
    fetchApi(`jadwal?id=${id}`, {
      method: 'PUT',
      body: JSON.stringify(data)
    }).catch(() => {});

    const list = getStorage<Jadwal[]>('jadwal', INITIAL_JADWAL);
    const idx = list.findIndex(j => j.id === id);
    if (idx !== -1) {
      list[idx] = { ...list[idx], ...data };
      setStorage('jadwal', list);
      return true;
    }
    return false;
  },

  async delete(id: number): Promise<boolean> {
    fetchApi(`jadwal?id=${id}`, { method: 'DELETE' }).catch(() => {});

    const list = getStorage<Jadwal[]>('jadwal', INITIAL_JADWAL);
    const filtered = list.filter(j => j.id !== id);
    setStorage('jadwal', filtered);
    return true;
  }
};

export const dashboardService = {
  async getKaderDashboard() {
    const res = await fetchApi<{
      total_anak: number;
      sudah_ditimbang: number;
      belum_ditimbang: number;
      perlu_perhatian: number;
      daftar_belum_ditimbang: Balita[];
      daftar_perlu_perhatian: Balita[];
    }>('dashboard/kader');

    if (res.success && res.data) {
      return res.data;
    }

    const balitaList = getStorage<Balita[]>('balita', INITIAL_BALITA);
    const belum = [
      balitaList.find(b => b.nama.includes('Raka')) || balitaList[3],
      balitaList.find(b => b.nama.includes('Almira')) || balitaList[4]
    ].filter(Boolean) as Balita[];
    const perhatian = [
      balitaList.find(b => b.nama.includes('Dinda')) || balitaList[0],
      balitaList.find(b => b.nama.includes('Farel')) || balitaList[1]
    ].filter(Boolean) as Balita[];

    return {
      total_anak: balitaList.length,
      sudah_ditimbang: 3,
      belum_ditimbang: 2,
      perlu_perhatian: 2,
      daftar_belum_ditimbang: belum,
      daftar_perlu_perhatian: perhatian
    };
  },

  async getBidanPrioritas() {
    const res = await fetchApi<{ prioritas: any[]; stats_dusun: any[] }>('dashboard/bidan');
    if (res.success && res.prioritas) {
      return res.prioritas;
    }

    const balitaList = getStorage<Balita[]>('balita', INITIAL_BALITA);
    return balitaList.filter(b => b.status_terakhir && ['pemantauan', 'prioritas', 'berisiko'].includes(b.status_terakhir));
  },

  async getBerita(): Promise<Berita[]> {
    return beritaService.getAll();
  },

  async getJadwal(): Promise<Jadwal[]> {
    return jadwalService.getAll();
  },

  async getPetugas(): Promise<User[]> {
    const res = await fetchApi<User[]>('petugas');
    if (res.success && Array.isArray(res.data)) {
      return res.data;
    }
    return getStorage<User[]>('users', INITIAL_USERS);
  }
};
