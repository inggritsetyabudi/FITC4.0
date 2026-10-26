import type { Balita, Pengukuran, User, Berita, Jadwal } from '../types';

interface ApiResponse<T> {
  success: boolean;
  data?: T;
  user?: User;
  id?: number;
  message?: string;
}

async function fetchApi<T>(endpoint: string, options: RequestInit = {}): Promise<ApiResponse<T>> {
  const res = await fetch(`/api/${endpoint}`, {
    ...options,
    headers: {
      'Content-Type': 'application/json',
      ...(options.headers || {})
    }
  });

  const body: ApiResponse<T> = await res.json().catch(() => ({ success: false }));

  if (!res.ok || !body.success) {
    throw new Error(body.message || `Permintaan gagal (HTTP ${res.status}).`);
  }

  return body;
}

export const authService = {
  async login(username: string, password: string): Promise<User> {
    const res = await fetchApi<User>('auth/login', {
      method: 'POST',
      body: JSON.stringify({ username, password })
    });
    if (!res.user) throw new Error('Respons server tidak valid.');
    return res.user;
  },

  async me(): Promise<User | null> {
    const res = await fetchApi<User>('auth/me');
    return res.user ?? null;
  },

  async logout(): Promise<void> {
    await fetchApi('auth/logout', { method: 'POST' });
  },

  async updateProfile(telepon: string): Promise<User> {
    const res = await fetchApi<User>('auth/profile', {
      method: 'PUT',
      body: JSON.stringify({ telepon })
    });
    if (!res.user) throw new Error('Respons server tidak valid.');
    return res.user;
  },

  async changePassword(passwordLama: string, passwordBaru: string): Promise<void> {
    await fetchApi('auth/password', {
      method: 'PUT',
      body: JSON.stringify({ password_lama: passwordLama, password_baru: passwordBaru })
    });
  }
};

export const balitaService = {
  async getAll(dusun?: string, search?: string): Promise<Balita[]> {
    const query = new URLSearchParams();
    if (dusun && dusun !== 'semua') query.append('dusun', dusun);
    if (search) query.append('q', search);

    const res = await fetchApi<Balita[]>(`balita?${query.toString()}`);
    return res.data ?? [];
  },

  async getById(id: number): Promise<Balita | undefined> {
    const res = await fetchApi<Balita>(`balita/detail?id=${id}`);
    return res.data;
  },

  async create(data: Omit<Balita, 'id'>): Promise<number> {
    const res = await fetchApi<{ id: number }>('balita', {
      method: 'POST',
      body: JSON.stringify(data)
    });
    return res.id ?? 0;
  },

  async update(id: number, data: Partial<Balita>): Promise<void> {
    await fetchApi(`balita?id=${id}`, {
      method: 'PUT',
      body: JSON.stringify(data)
    });
  },

  async delete(id: number): Promise<void> {
    await fetchApi(`balita?id=${id}`, { method: 'DELETE' });
  }
};

export const pengukuranService = {
  async getAll(bulan?: string, dusun?: string, status?: string): Promise<Pengukuran[]> {
    const q = new URLSearchParams();
    if (bulan && bulan !== 'semua') q.append('bulan', bulan);
    if (dusun && dusun !== 'semua') q.append('dusun', dusun);
    if (status && status !== 'semua') q.append('status', status);

    const res = await fetchApi<Pengukuran[]>(`pengukuran?${q.toString()}`);
    return res.data ?? [];
  },

  async getByBalitaId(anakId: number): Promise<Pengukuran[]> {
    const res = await fetchApi<Pengukuran[]>(`pengukuran?anak=${anakId}`);
    return res.data ?? [];
  },

  async create(data: Omit<Pengukuran, 'id'>): Promise<number> {
    const res = await fetchApi<{ id: number }>('pengukuran', {
      method: 'POST',
      body: JSON.stringify(data)
    });
    return res.id ?? 0;
  }
};

export const beritaService = {
  async getAll(tipe?: 'blog' | 'berita'): Promise<Berita[]> {
    const res = await fetchApi<Berita[]>(tipe ? `berita?tipe=${tipe}` : 'berita');
    return res.data ?? [];
  },

  async getAllAdmin(): Promise<Berita[]> {
    const res = await fetchApi<Berita[]>('berita/admin');
    return res.data ?? [];
  },

  async getById(id: number): Promise<Berita | undefined> {
    const res = await fetchApi<Berita>(`berita/detail?id=${id}`);
    return res.data;
  },

  async uploadImage(file: File): Promise<string> {
    const fd = new FormData();
    fd.append('gambar', file);
    const res = await fetch('/api/berita/upload', { method: 'POST', body: fd });
    const body = await res.json().catch(() => ({ success: false }));
    if (!res.ok || !body.success) {
      throw new Error(body.message || 'Upload gambar gagal.');
    }
    return body.url;
  },

  async create(data: Partial<Berita>): Promise<number> {
    const res = await fetchApi<{ id: number }>('berita', {
      method: 'POST',
      body: JSON.stringify(data)
    });
    return res.id ?? 0;
  },

  async update(id: number, data: Partial<Berita>): Promise<void> {
    await fetchApi(`berita?id=${id}`, {
      method: 'PUT',
      body: JSON.stringify(data)
    });
  },

  async delete(id: number): Promise<void> {
    await fetchApi(`berita?id=${id}`, { method: 'DELETE' });
  }
};

export const jadwalService = {
  async getAll(): Promise<Jadwal[]> {
    const res = await fetchApi<Jadwal[]>('jadwal');
    return res.data ?? [];
  },

  async create(data: Omit<Jadwal, 'id'>): Promise<number> {
    const res = await fetchApi<{ id: number }>('jadwal', {
      method: 'POST',
      body: JSON.stringify(data)
    });
    return res.id ?? 0;
  },

  async update(id: number, data: Partial<Jadwal>): Promise<void> {
    await fetchApi(`jadwal?id=${id}`, {
      method: 'PUT',
      body: JSON.stringify(data)
    });
  },

  async delete(id: number): Promise<void> {
    await fetchApi(`jadwal?id=${id}`, { method: 'DELETE' });
  }
};

export const petugasService = {
  async getAll(): Promise<User[]> {
    const res = await fetchApi<User[]>('petugas');
    return res.data ?? [];
  },

  async create(data: Omit<User, 'id'> & { password: string }): Promise<number> {
    const res = await fetchApi<{ id: number }>('petugas', {
      method: 'POST',
      body: JSON.stringify(data)
    });
    return res.id ?? 0;
  },

  async delete(id: number): Promise<void> {
    await fetchApi(`petugas?id=${id}`, { method: 'DELETE' });
  }
};
