import React, { useState } from 'react';
import type { Balita } from '../../types';
import { DUSUN_LIST } from '../../utils/constants';

interface ChildModalProps {
  isOpen: boolean;
  initialData?: Balita | null;
  onClose: () => void;
  onSave: (data: Omit<Balita, 'id'>, id?: number) => Promise<void>;
}

const ChildForm: React.FC<Omit<ChildModalProps, 'isOpen'>> = ({ initialData, onClose, onSave }) => {
  const [nama, setNama] = useState(initialData?.nama ?? '');
  const [nik, setNik] = useState(initialData?.nik ?? '');
  const [tanggalLahir, setTanggalLahir] = useState(initialData?.tanggal_lahir ?? '');
  const [jenisKelamin, setJenisKelamin] = useState<'L' | 'P'>(initialData?.jenis_kelamin ?? 'L');
  const [namaOrtu, setNamaOrtu] = useState(initialData?.nama_ortu ?? '');
  const [dusun, setDusun] = useState(initialData?.dusun ?? 'Gamping Wetan');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');

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
                {DUSUN_LIST.map((d) => (
                  <option key={d} value={d}>Dusun {d}</option>
                ))}
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

export const ChildModal: React.FC<ChildModalProps> = ({ isOpen, initialData, onClose, onSave }) => {
  if (!isOpen) return null;

  return (
    <ChildForm
      key={initialData?.id ?? 'baru'}
      initialData={initialData}
      onClose={onClose}
      onSave={onSave}
    />
  );
};
