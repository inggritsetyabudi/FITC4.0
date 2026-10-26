import React, { useState, useEffect } from 'react';
import type { Balita } from '../../types';

interface ChildModalProps {
  isOpen: boolean;
  initialData?: Balita | null;
  onClose: () => void;
  onSave: (data: Omit<Balita, 'id'>, id?: number) => Promise<void>;
}

export const ChildModal: React.FC<ChildModalProps> = ({
  isOpen,
  initialData,
  onClose,
  onSave
}) => {
  const [nama, setNama] = useState('');
  const [nik, setNik] = useState('');
  const [tanggalLahir, setTanggalLahir] = useState('');
  const [jenisKelamin, setJenisKelamin] = useState<'L' | 'P'>('L');
  const [namaOrtu, setNamaOrtu] = useState('');
  const [dusun, setDusun] = useState('Gamping Wetan');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

  useEffect(() => {
    if (initialData) {
      setNama(initialData.nama);
      setNik(initialData.nik || '');
      setTanggalLahir(initialData.tanggal_lahir);
      setJenisKelamin(initialData.jenis_kelamin);
      setNamaOrtu(initialData.nama_ortu);
      setDusun(initialData.dusun);
    } else {
      setNama('');
      setNik('');
      setTanggalLahir('');
      setJenisKelamin('L');
      setNamaOrtu('');
      setDusun('Gamping Wetan');
    }
    setError('');
  }, [initialData, isOpen]);

  if (!isOpen) return null;

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!nama.trim() || !tanggalLahir || !namaOrtu.trim()) {
      setError('Nama balita, tanggal lahir, dan nama orang tua wajib diisi.');
      return;
    }

    setLoading(true);
    try {
      await onSave(
        {
          nama: nama.trim(),
          nik: nik.trim() || null,
          tanggal_lahir: tanggalLahir,
          jenis_kelamin: jenisKelamin,
          nama_ortu: namaOrtu.trim(),
          dusun,
          status: 'aktif'
        },
        initialData?.id
      );
      onClose();
    } catch {
      setError('Gagal menyimpan data balita.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
      <div className="bg-surface rounded-2xl border border-line w-full max-w-lg shadow-panel overflow-hidden">
        <div className="px-6 py-4 border-b border-line flex items-center justify-between">
          <h3 className="text-base font-bold text-forest-900">
            {initialData ? 'Perbarui Data Balita' : 'Tambah Balita Binaan Baru'}
          </h3>
          <button
            onClick={onClose}
            className="text-ink-400 hover:text-ink-700 p-1"
            type="button"
          >
            <i className="bi bi-x-lg"></i>
          </button>
        </div>

        <form onSubmit={handleSubmit} className="p-6">
          {error && (
            <div className="p-3 mb-4 rounded-lg bg-danger-soft text-danger text-xs border border-danger-border flex items-center gap-2">
              <i className="bi bi-exclamation-circle-fill"></i>
              <span>{error}</span>
            </div>
          )}

          <div className="mb-4">
            <label className="block text-xs font-semibold text-ink-700 uppercase tracking-wider mb-1.5">
              Nama Lengkap Balita <span className="text-danger">*</span>
            </label>
            <input
              className="w-full px-3.5 py-2 text-sm bg-white border border-line rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-base"
              value={nama}
              onChange={(e) => setNama(e.target.value)}
              placeholder="Contoh: Dinda Aulia"
              required
            />
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
              <label className="block text-xs font-semibold text-ink-700 uppercase tracking-wider mb-1.5">
                NIK (Nomor Induk)
              </label>
              <input
                className="w-full px-3.5 py-2 text-sm bg-white border border-line rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-base"
                value={nik}
                onChange={(e) => setNik(e.target.value)}
                placeholder="16 digit NIK"
                maxLength={16}
              />
            </div>

            <div>
              <label className="block text-xs font-semibold text-ink-700 uppercase tracking-wider mb-1.5">
                Tanggal Lahir <span className="text-danger">*</span>
              </label>
              <input
                type="date"
                className="w-full px-3.5 py-2 text-sm bg-white border border-line rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-base"
                value={tanggalLahir}
                onChange={(e) => setTanggalLahir(e.target.value)}
                required
              />
            </div>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            <div>
              <label className="block text-xs font-semibold text-ink-700 uppercase tracking-wider mb-1.5">
                Jenis Kelamin <span className="text-danger">*</span>
              </label>
              <select
                className="w-full px-3.5 py-2 text-sm bg-white border border-line rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-base"
                value={jenisKelamin}
                onChange={(e) => setJenisKelamin(e.target.value as 'L' | 'P')}
              >
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
              </select>
            </div>

            <div>
              <label className="block text-xs font-semibold text-ink-700 uppercase tracking-wider mb-1.5">
                Dusun <span className="text-danger">*</span>
              </label>
              <select
                className="w-full px-3.5 py-2 text-sm bg-white border border-line rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-base"
                value={dusun}
                onChange={(e) => setDusun(e.target.value)}
              >
                <option value="Gamping Wetan">Dusun Gamping Wetan</option>
                <option value="Boharan">Dusun Boharan</option>
                <option value="Kasengan">Dusun Kasengan</option>
                <option value="Bendomungal">Dusun Bendomungal</option>
              </select>
            </div>
          </div>

          <div className="mb-4">
            <label className="block text-xs font-semibold text-ink-700 uppercase tracking-wider mb-1.5">
              Nama Orang Tua <span className="text-danger">*</span>
            </label>
            <input
              className="w-full px-3.5 py-2 text-sm bg-white border border-line rounded-xl focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-base"
              value={namaOrtu}
              onChange={(e) => setNamaOrtu(e.target.value)}
              placeholder="Siti Aminah / Herman Susanto"
              required
            />
          </div>

          <div className="flex items-center justify-end gap-3 pt-3 border-t border-line mt-6">
            <button
              type="button"
              onClick={onClose}
              className="px-4 py-2 text-xs font-semibold text-ink-700 hover:bg-surface-soft border border-line rounded-xl transition-base"
              disabled={loading}
            >
              Batal
            </button>
            <button
              type="submit"
              className="px-4 py-2 bg-primary hover:bg-primary-hover text-white text-xs font-semibold rounded-xl shadow-xs transition-base"
              disabled={loading}
            >
              {loading ? 'Menyimpan...' : initialData ? 'Simpan Perubahan' : 'Tambahkan Balita'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};
