import React, { useRef, useState } from 'react';
import { beritaService } from '../services/api';
import { useAsyncData } from '../hooks/useAsyncData';
import { formatTanggalId } from '../utils/helpers';
import type { Berita } from '../types';

const KOSONG: Partial<Berita> = {
  judul: '',
  kategori: 'KABAR DESA',
  ringkasan: '',
  isi: '',
  gambar: '',
  tipe: 'berita',
  status: 'draft'
};

export const BlogPage: React.FC = () => {
  const { data: daftar = [], reload } = useAsyncData(() => beritaService.getAllAdmin());
  const [showModal, setShowModal] = useState(false);
  const [editing, setEditing] = useState<Berita | null>(null);
  const [form, setForm] = useState<Partial<Berita>>({ ...KOSONG });
  const [file, setFile] = useState<File | null>(null);
  const [preview, setPreview] = useState('');
  const [saving, setSaving] = useState(false);
  const [error, setError] = useState('');
  const [toast, setToast] = useState('');
  const fileRef = useRef<HTMLInputElement>(null);

  const set = (key: keyof Berita, value: string) => setForm((f) => ({ ...f, [key]: value }));

  const showToast = (msg: string) => {
    setToast(msg);
    setTimeout(() => setToast(''), 3000);
  };

  const openAdd = () => {
    setEditing(null);
    setForm({ ...KOSONG });
    setFile(null);
    setPreview('');
    setError('');
    setShowModal(true);
  };

  const openEdit = (b: Berita) => {
    setEditing(b);
    setForm({ judul: b.judul, kategori: b.kategori, tipe: b.tipe ?? 'berita', ringkasan: b.ringkasan, isi: b.isi, gambar: b.gambar ?? '', status: b.status ?? 'draft' });
    setFile(null);
    setPreview(b.gambar ?? '');
    setError('');
    setShowModal(true);
  };

  const onPickFile = (e: React.ChangeEvent<HTMLInputElement>) => {
    const f = e.target.files?.[0];
    if (!f) return;
    setFile(f);
    setPreview(URL.createObjectURL(f));
  };

  const removeImage = () => {
    setFile(null);
    setPreview('');
    setForm((f) => ({ ...f, gambar: '' }));
    if (fileRef.current) fileRef.current.value = '';
  };

  const handleSave = async () => {
    if (!form.judul || !form.ringkasan || !form.isi) {
      setError('Judul, ringkasan, dan isi wajib diisi.');
      return;
    }
    setSaving(true);
    setError('');
    try {
      let gambar = form.gambar || '';
      if (file) {
        gambar = await beritaService.uploadImage(file);
      }
      const payload = { ...form, gambar };
      if (editing) {
        await beritaService.update(editing.id, payload);
      } else {
        await beritaService.create(payload);
      }
      setShowModal(false);
      reload();
      showToast(editing ? 'Berita berhasil diperbarui.' : 'Berita berhasil ditambahkan.');
    } catch (err) {
      setError(err instanceof Error ? err.message : 'Gagal menyimpan berita.');
    } finally {
      setSaving(false);
    }
  };

  const handleDelete = async (id: number) => {
    if (!window.confirm('Hapus berita ini?')) return;
    try {
      await beritaService.delete(id);
      reload();
      showToast('Berita berhasil dihapus.');
    } catch (err) {
      showToast(err instanceof Error ? err.message : 'Gagal menghapus berita.');
    }
  };

  return (
    <div className="blog-page space-y-6">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl font-extrabold text-[#0D47A1]">Blog &amp; Berita Desa</h1>
          <p className="text-xs text-ink-500 mt-1">
            Tulis, unggah gambar, dan kelola artikel yang tampil di halaman publik Posyandu.
          </p>
        </div>
        <button
          onClick={openAdd}
          className="px-4 py-2.5 bg-[#2196F3] hover:bg-[#1976D2] text-white text-xs font-bold rounded-xl flex items-center gap-2 self-start sm:self-auto shadow-xs transition-base"
        >
          <i className="bi bi-plus-lg"></i>
          <span>Tulis Berita Baru</span>
        </button>
      </div>

      {toast && (
        <div className="p-4 bg-emerald-50 text-emerald-800 text-xs font-semibold rounded-2xl border border-emerald-200 flex items-center gap-2 shadow-xs">
          <i className="bi bi-check-circle-fill text-emerald-600 text-base"></i>
          <span>{toast}</span>
        </div>
      )}

      <div className="bg-white border border-line rounded-2xl overflow-hidden shadow-xs">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs border-collapse">
            <thead>
              <tr className="border-b border-line text-[#3D5F7D] font-bold text-xs">
                <th className="py-4 px-4">Gambar</th>
                <th className="py-4 px-4">Judul &amp; Kategori</th>
                <th className="py-4 px-4">Jenis</th>
                <th className="py-4 px-4">Status</th>
                <th className="py-4 px-4">Terbit</th>
                <th className="py-4 px-4">Penulis</th>
                <th className="py-4 px-4 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-line">
              {daftar.map((b) => (
                <tr key={b.id} className="hover:bg-slate-50/70 transition-base">
                  <td className="py-3 px-4">
                    <div className="w-16 h-11 rounded-lg overflow-hidden bg-[#F0F7FF] border border-line flex items-center justify-center">
                      {b.gambar ? (
                        <img src={b.gambar} alt={b.judul} className="w-full h-full object-cover" />
                      ) : (
                        <i className="bi bi-image text-ink-300"></i>
                      )}
                    </div>
                  </td>
                  <td className="py-3 px-4">
                    <strong className="text-[#0D47A1] block font-bold">{b.judul}</strong>
                    <span className="text-[11px] text-[#2196F3] font-semibold">{b.kategori}</span>
                  </td>
                  <td className="py-3 px-4">
                    <span className={`px-2.5 py-0.5 rounded-full text-[11px] font-bold border ${
                      b.tipe === 'blog'
                        ? 'bg-violet-50 text-violet-700 border-violet-200'
                        : 'bg-amber-50 text-amber-700 border-amber-200'
                    }`}>
                      {b.tipe === 'blog' ? 'Blog' : 'Berita'}
                    </span>
                  </td>
                  <td className="py-3 px-4">
                    <span className={`px-2.5 py-0.5 rounded-full text-[11px] font-bold border ${
                      b.status === 'published'
                        ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
                        : 'bg-slate-50 text-ink-500 border-line'
                    }`}>
                      {b.status === 'published' ? 'Terbit' : 'Draft'}
                    </span>
                  </td>
                  <td className="py-3 px-4 text-ink-600 whitespace-nowrap">
                    {b.published_at ? formatTanggalId(b.published_at) : '-'}
                  </td>
                  <td className="py-3 px-4 text-ink-700">{b.penulis || '-'}</td>
                  <td className="py-3 px-4 text-right whitespace-nowrap">
                    <div className="inline-flex items-center gap-1.5">
                      <button
                        onClick={() => openEdit(b)}
                        className="p-1.5 text-[#2196F3] hover:bg-blue-50 rounded-lg border border-blue-200 transition-base"
                        title="Edit Berita"
                      >
                        <i className="bi bi-pencil"></i>
                      </button>
                      <button
                        onClick={() => handleDelete(b.id)}
                        className="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg border border-rose-200 transition-base"
                        title="Hapus Berita"
                      >
                        <i className="bi bi-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
              {daftar.length === 0 && (
                <tr>
                  <td colSpan={7} className="py-12 text-center text-ink-400">
                    <i className="bi bi-inbox text-3xl mb-2 block"></i>
                    <p className="text-xs font-semibold">Belum ada berita. Klik "Tulis Berita Baru" untuk memulai.</p>
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>
      </div>

      {showModal && (
        <div className="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs overflow-y-auto p-4">
          <div className="min-h-full flex items-center justify-center">
            <div className="bg-white rounded-3xl p-6 sm:p-8 max-w-2xl w-full border border-line shadow-panel space-y-4 my-6">
            <h3 className="text-lg font-extrabold text-[#0D47A1]">
              {editing ? 'Edit Berita' : 'Tulis Berita Baru'}
            </h3>

            {error && (
              <div className="p-3 bg-rose-50 text-rose-700 text-xs font-semibold rounded-xl border border-rose-200">
                {error}
              </div>
            )}

            <div className="space-y-3">
              <div>
                <label className="block text-xs font-bold text-ink-700 uppercase mb-1">Judul <span className="text-[#D32F2F]">*</span></label>
                <input
                  type="text"
                  value={form.judul || ''}
                  onChange={(e) => set('judul', e.target.value)}
                  placeholder="Contoh: Jadwal Penimbangan Serentak September"
                  className="w-full px-3 py-2 text-xs border border-line rounded-xl focus:outline-none focus:border-[#2196F3]"
                />
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                  <label className="block text-xs font-bold text-ink-700 uppercase mb-1">Kategori</label>
                  <input
                    type="text"
                    value={form.kategori || ''}
                    onChange={(e) => set('kategori', e.target.value)}
                    placeholder="KABAR DESA"
                    className="w-full px-3 py-2 text-xs border border-line rounded-xl focus:outline-none focus:border-[#2196F3]"
                  />
                </div>
                <div>
                  <label className="block text-xs font-bold text-ink-700 uppercase mb-1">Jenis</label>
                  <select
                    value={form.tipe || 'berita'}
                    onChange={(e) => set('tipe', e.target.value)}
                    className="w-full px-3 py-2 text-xs border border-line rounded-xl bg-slate-50"
                  >
                    <option value="berita">Berita (kegiatan/pengumuman)</option>
                    <option value="blog">Blog (artikel/panduan)</option>
                  </select>
                </div>
                <div>
                  <label className="block text-xs font-bold text-ink-700 uppercase mb-1">Status</label>
                  <select
                    value={form.status || 'draft'}
                    onChange={(e) => set('status', e.target.value)}
                    className="w-full px-3 py-2 text-xs border border-line rounded-xl bg-slate-50"
                  >
                    <option value="draft">Draft (belum tayang)</option>
                    <option value="published">Terbit (tayang di publik)</option>
                  </select>
                </div>
              </div>

              <div>
                <label className="block text-xs font-bold text-ink-700 uppercase mb-1">Ringkasan <span className="text-[#D32F2F]">*</span></label>
                <textarea
                  value={form.ringkasan || ''}
                  onChange={(e) => set('ringkasan', e.target.value)}
                  rows={2}
                  placeholder="Ringkasan singkat artikel"
                  className="w-full px-3 py-2 text-xs border border-line rounded-xl focus:outline-none focus:border-[#2196F3]"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-ink-700 uppercase mb-1">Isi Artikel <span className="text-[#D32F2F]">*</span></label>
                <textarea
                  value={form.isi || ''}
                  onChange={(e) => set('isi', e.target.value)}
                  rows={6}
                  placeholder="Tulis isi artikel di sini. Pisahkan paragraf dengan baris kosong."
                  className="w-full px-3 py-2 text-xs border border-line rounded-xl focus:outline-none focus:border-[#2196F3]"
                />
              </div>

              <div>
                <label className="block text-xs font-bold text-ink-700 uppercase mb-1">Gambar Sampul</label>
                <div className="flex items-start gap-4">
                  <div className="w-40 h-24 rounded-xl overflow-hidden bg-[#F0F7FF] border border-line flex items-center justify-center shrink-0">
                    {preview ? (
                      <img src={preview} alt="Pratinjau" className="w-full h-full object-cover" />
                    ) : (
                      <i className="bi bi-image text-ink-300 text-2xl"></i>
                    )}
                  </div>
                  <div className="space-y-2">
                    <input
                      ref={fileRef}
                      type="file"
                      accept="image/jpeg,image/png,image/webp,image/gif"
                      onChange={onPickFile}
                      className="text-xs text-ink-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#E3F2FD] file:text-[#0D47A1]"
                    />
                    <p className="text-[11px] text-ink-400">Format JPG/PNG/WebP/GIF, maksimal 2 MB.</p>
                    {(preview || form.gambar) && (
                      <button
                        type="button"
                        onClick={removeImage}
                        className="text-[11px] font-semibold text-rose-500 hover:text-rose-700"
                      >
                        <i className="bi bi-x-circle me-1"></i>Hapus Gambar
                      </button>
                    )}
                  </div>
                </div>
              </div>
            </div>

            <div className="pt-3 flex justify-end gap-2 border-t border-line">
              <button
                type="button"
                onClick={() => setShowModal(false)}
                className="px-4 py-2 text-xs font-semibold text-ink-700 border border-line rounded-xl hover:bg-slate-50"
              >
                Batal
              </button>
              <button
                type="button"
                onClick={handleSave}
                disabled={saving}
                className="px-4 py-2 text-xs font-bold bg-[#2196F3] text-white rounded-xl hover:bg-[#1976D2] disabled:opacity-60"
              >
                {saving ? 'Menyimpan...' : editing ? 'Simpan Perubahan' : 'Publikasikan'}
              </button>
            </div>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};
