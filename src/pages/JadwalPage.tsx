import React, { useState } from 'react';
import { jadwalService } from '../services/api';
import { useAsyncData } from '../hooks/useAsyncData';
import { DUSUN_LIST } from '../utils/constants';
import type { Jadwal } from '../types';
import { formatTanggalId } from '../utils/helpers';

export const JadwalPage: React.FC = () => {
  const { data: jadwalList = [], loading, error, reload } = useAsyncData(() => jadwalService.getAll());
  const [filterStatus, setFilterStatus] = useState<string>('semua');
  const [showModal, setShowModal] = useState<boolean>(false);
  const [editing, setEditing] = useState<Jadwal | null>(null);
  const [saving, setSaving] = useState(false);
  const [formError, setFormError] = useState('');
  const [formData, setFormData] = useState<Partial<Jadwal>>({
    nama_kegiatan: 'Penimbangan Balita Bulanan',
    dusun: 'Gamping Wetan',
    lokasi: 'Balai Dusun Gamping Wetan',
    tanggal: new Date().toLocaleDateString('sv-SE'),
    waktu_mulai: '08:00',
    waktu_selesai: '11:30',
    petugas: 'Bu Sari',
    status: 'rencana'
  });

  const handleSave = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!formData.nama_kegiatan || !formData.dusun || !formData.tanggal) {
      setFormError('Nama kegiatan, dusun, dan tanggal wajib diisi.');
      return;
    }

    setSaving(true);
    setFormError('');
    try {
      if (editing) {
        await jadwalService.update(editing.id, formData);
      } else {
        await jadwalService.create(formData as Omit<Jadwal, 'id'>);
      }
      setShowModal(false);
      setEditing(null);
      reload();
    } catch (err) {
      setFormError(err instanceof Error ? err.message : 'Jadwal gagal disimpan.');
    } finally {
      setSaving(false);
    }
  };

  const openAdd = () => {
    setEditing(null);
    setFormError('');
    setFormData({
      nama_kegiatan: 'Penimbangan Balita Bulanan', dusun: DUSUN_LIST[0],
      lokasi: `Balai Dusun ${DUSUN_LIST[0]}`, tanggal: new Date().toLocaleDateString('sv-SE'),
      waktu_mulai: '08:00', waktu_selesai: '11:30', petugas: '', status: 'rencana'
    });
    setShowModal(true);
  };

  const openEdit = (item: Jadwal) => {
    setEditing(item);
    setFormError('');
    setFormData({ ...item });
    setShowModal(true);
  };

  const handleDelete = async (item: Jadwal) => {
    if (!window.confirm(`Hapus agenda “${item.nama_kegiatan}”?`)) return;
    try {
      await jadwalService.delete(item.id);
      reload();
    } catch (err) {
      window.alert(err instanceof Error ? err.message : 'Jadwal gagal dihapus.');
    }
  };

  const filtered = jadwalList.filter((j) => {
    if (filterStatus === 'rencana' && j.status !== 'rencana') return false;
    if (filterStatus === 'selesai' && j.status !== 'selesai') return false;
    return true;
  });

  return (
    <div className="space-y-6">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl font-extrabold text-[#0D47A1]">Jadwal &amp; Agenda Layanan Posyandu</h1>
          <p className="text-xs text-ink-500 mt-1">
            Penyusunan jadwal pelayanan penimbangan, imunisasi, dan skrining kesehatan tiap dusun
          </p>
        </div>

        <button
          onClick={openAdd}
          className="px-4 py-2.5 bg-[#2196F3] hover:bg-[#1976D2] text-white text-xs font-bold rounded-xl flex items-center gap-2 self-start sm:self-auto shadow-xs transition-base"
        >
          <i className="bi bi-calendar-event"></i>
          <span>Tambah Agenda Jadwal</span>
        </button>
      </div>

      <div className="bg-white border border-line rounded-2xl p-4 shadow-xs flex flex-col sm:flex-row items-center gap-3">
        <select
          value={filterStatus}
          onChange={(e) => setFilterStatus(e.target.value)}
          className="flex-1 w-full px-4 py-2.5 text-xs font-semibold bg-slate-50 border border-line rounded-xl focus:outline-none focus:border-[#2196F3] focus:bg-white"
        >
          <option value="semua">Semua Status Agenda</option>
          <option value="rencana">Mendatang / Rencana</option>
          <option value="selesai">Selesai Terlaksana</option>
        </select>

        <button
          onClick={reload}
          className="w-full sm:w-auto px-6 py-2.5 bg-[#2196F3] hover:bg-[#1976D2] text-white text-xs font-bold rounded-xl transition-base shrink-0"
        >
          Filter Status
        </button>
      </div>

      <div className="table-panel bg-white border border-line rounded-2xl overflow-hidden shadow-xs">
        {loading ? (
          <div className="p-12 text-center text-ink-400">
            <i className="bi bi-arrow-repeat animate-spin text-2xl text-[#2196F3]"></i>
            <p className="mt-2 text-xs font-semibold">Memuat jadwal layanan...</p>
          </div>
        ) : filtered.length === 0 ? (
          <div className="p-12 text-center text-ink-400">
            <i className={`bi ${error ? 'bi-exclamation-circle text-amber-500' : 'bi-calendar-x'} text-3xl mb-2 block`}></i>
            <p className="text-xs font-semibold">{error || 'Tidak ada agenda jadwal posyandu untuk status ini.'}</p>
          </div>
        ) : (
          <div className="overflow-x-auto">
            <table className="w-full text-left text-xs border-collapse">
              <thead>
                <tr className="border-b border-line text-[#3D5F7D] font-bold text-xs">
                  <th className="py-4 px-4">Tanggal Pelaksanaan</th>
                  <th className="py-4 px-3">Waktu</th>
                  <th className="py-4 px-4">Judul Agenda &amp; Jenis Layanan</th>
                  <th className="py-4 px-4">Dusun &amp; Lokasi</th>
                  <th className="py-4 px-4">Penanggung Jawab</th>
                  <th className="py-4 px-4">Keterangan</th>
                  <th className="py-4 px-4 text-center">Status</th>
                  <th className="py-4 px-4 text-right">Aksi</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-line">
                {filtered.map((item) => (
                  <tr key={item.id} className="hover:bg-slate-50/70 transition-base">
                    <td className="py-3.5 px-4 font-bold text-[#0D47A1] whitespace-nowrap">
                      <div className="flex items-center gap-2">
                        <i className="bi bi-calendar text-[#2196F3]"></i>
                        <span>{formatTanggalId(item.tanggal, true)}</span>
                      </div>
                    </td>
                    <td className="py-3.5 px-3 text-ink-700 whitespace-nowrap">
                      {item.waktu_mulai} - {item.waktu_selesai} WIB
                    </td>
                    <td className="py-3.5 px-4">
                      <strong className="block text-[#0D47A1] font-bold">{item.nama_kegiatan}</strong>
                      <span className="text-[11px] text-[#2196F3] font-semibold">Penimbangan Balita</span>
                    </td>
                    <td className="py-3.5 px-4 text-ink-700">
                      <div className="font-semibold text-ink-900">Dusun {item.dusun}</div>
                      <div className="text-[11px] text-ink-400">{item.lokasi}</div>
                    </td>
                    <td className="py-3.5 px-4 text-ink-700 whitespace-nowrap">
                      <div className="flex items-center gap-1.5">
                        <i className="bi bi-person-fill text-ink-400"></i>
                        <span>{item.petugas}</span>
                      </div>
                    </td>
                    <td className="py-3.5 px-4 text-ink-600 max-w-xs text-[11px]">
                      {item.catatan || 'Penimbangan dan pemantauan tumbuh kembang balita.'}
                    </td>
                    <td className="py-3.5 px-4 text-center whitespace-nowrap">
                      <span className={`font-semibold text-xs ${item.status === 'selesai' ? 'text-emerald-600' : 'text-blue-700'}`}>
                        {item.status === 'selesai' ? 'Selesai' : 'Akan datang'}
                      </span>
                    </td>
                    <td className="py-3.5 px-4 text-right whitespace-nowrap">
                      <div className="flex justify-end gap-2">
                        <button type="button" onClick={() => openEdit(item)} className="px-3 py-2 rounded-lg border border-line text-[#1976D2] hover:bg-blue-50" aria-label={`Ubah ${item.nama_kegiatan}`}>
                          <i className="bi bi-pencil"></i>
                        </button>
                        <button type="button" onClick={() => handleDelete(item)} className="px-3 py-2 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50" aria-label={`Hapus ${item.nama_kegiatan}`}>
                          <i className="bi bi-trash"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        )}
      </div>

      {showModal && (
        <div className="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs overflow-y-auto p-4">
          <div className="min-h-full flex items-center justify-center">
            <div className="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full border border-line shadow-panel space-y-4">
            <h3 className="text-lg font-extrabold text-[#0D47A1]">{editing ? 'Ubah Agenda Jadwal' : 'Tambah Agenda Jadwal Baru'}</h3>
            {formError && <p role="alert" className="rounded-xl bg-rose-50 border border-rose-200 px-3 py-2 text-xs text-rose-700">{formError}</p>}
            <form onSubmit={handleSave} className="space-y-3">
              <div>
                <label className="block text-xs font-bold text-ink-700 uppercase mb-1">Nama Agenda Kegiatan</label>
                <input
                  type="text"
                  required
                  value={formData.nama_kegiatan}
                  onChange={(e) => setFormData({ ...formData, nama_kegiatan: e.target.value })}
                  placeholder="Contoh: Penimbangan Rutin Dusun"
                  className="w-full px-3 py-2 text-xs border border-line rounded-xl focus:outline-none focus:border-[#2196F3]"
                />
              </div>
              <div className="grid grid-cols-2 gap-2">
                <div>
                  <label className="block text-xs font-bold text-ink-700 uppercase mb-1">Wilayah Dusun</label>
                  <select
                    value={formData.dusun}
                    onChange={(e) => setFormData({ ...formData, dusun: e.target.value })}
                    className="w-full px-3 py-2 text-xs border border-line rounded-xl bg-slate-50"
                  >
                    {DUSUN_LIST.map((d) => (
                      <option key={d} value={d}>{d}</option>
                    ))}
                  </select>
                </div>
                <div>
                  <label className="block text-xs font-bold text-ink-700 uppercase mb-1">Tanggal</label>
                  <input
                    type="date"
                    required
                    value={formData.tanggal}
                    onChange={(e) => setFormData({ ...formData, tanggal: e.target.value })}
                    className="w-full px-3 py-2 text-xs border border-line rounded-xl"
                  />
                </div>
              </div>
              <div className="grid grid-cols-2 gap-2">
                <div>
                  <label className="block text-xs font-bold text-ink-700 uppercase mb-1">Waktu Mulai</label>
                  <input
                    type="time"
                    value={formData.waktu_mulai}
                    onChange={(e) => setFormData({ ...formData, waktu_mulai: e.target.value })}
                    className="w-full px-3 py-2 text-xs border border-line rounded-xl"
                  />
                </div>
                <div>
                  <label className="block text-xs font-bold text-ink-700 uppercase mb-1">Waktu Selesai</label>
                  <input
                    type="time"
                    value={formData.waktu_selesai}
                    onChange={(e) => setFormData({ ...formData, waktu_selesai: e.target.value })}
                    className="w-full px-3 py-2 text-xs border border-line rounded-xl"
                  />
                </div>
              </div>
              <div>
                <label className="block text-xs font-bold text-ink-700 uppercase mb-1">Lokasi Kegiatan</label>
                <input
                  type="text"
                  value={formData.lokasi}
                  onChange={(e) => setFormData({ ...formData, lokasi: e.target.value })}
                  placeholder="Contoh: Balai Dusun"
                  className="w-full px-3 py-2 text-xs border border-line rounded-xl"
                />
              </div>
              <div>
                <label className="block text-xs font-bold text-ink-700 uppercase mb-1">Penanggung Jawab</label>
                <input
                  type="text"
                  value={formData.petugas}
                  onChange={(e) => setFormData({ ...formData, petugas: e.target.value })}
                  placeholder="Nama kader / bidan"
                  className="w-full px-3 py-2 text-xs border border-line rounded-xl"
                />
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
                  type="submit"
                  className="px-4 py-2 text-xs font-bold bg-[#2196F3] text-white rounded-xl hover:bg-[#1976D2]"
                >
                  {saving ? 'Menyimpan…' : editing ? 'Simpan Perubahan' : 'Simpan Agenda'}
                </button>
              </div>
            </form>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};
