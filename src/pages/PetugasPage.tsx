import React, { useState, useEffect } from 'react';
import { dashboardService } from '../services/api';
import type { User } from '../types';

export const PetugasPage: React.FC<{ onNavigate: (page: string, params?: any) => void }> = () => {
  const [petugasList, setPetugasList] = useState<User[]>([]);
  const [showAddModal, setShowAddModal] = useState<boolean>(false);
  const [resetSuccess, setResetSuccess] = useState<string>('');

  useEffect(() => {
    dashboardService.getPetugas().then(setPetugasList);
  }, []);

  const bidanList = petugasList.filter((p) => p.role === 'bidan');
  const kaderList = petugasList.filter((p) => p.role === 'kader');

  const handleResetPassword = (name: string) => {
    setResetSuccess(`Tautan reset kata sandi untuk ${name} berhasil dikirim ke WhatsApp.`);
    setTimeout(() => setResetSuccess(''), 4000);
  };

  const handleDeleteKader = (id: number) => {
    if (!window.confirm('Hapus akses kader ini?')) return;
    setPetugasList((prev) => prev.filter((p) => p.id !== id));
  };

  return (
    <div className="staff-page space-y-6">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-2xl font-extrabold text-[#0D47A1]">Petugas Posyandu Desa Sukatani</h1>
          <p className="text-xs text-ink-500 mt-1">
            Kelola data dan hak akses akun Kader Posyandu dan Bidan Desa penanggung jawab
          </p>
        </div>

        <button
          onClick={() => setShowAddModal(true)}
          className="px-4 py-2.5 bg-[#2196F3] hover:bg-[#1976D2] text-white text-xs font-bold rounded-xl flex items-center gap-2 self-start sm:self-auto shadow-xs transition-base"
        >
          <i className="bi bi-person-plus-fill"></i>
          <span>Tambah Petugas Baru</span>
        </button>
      </div>

      {resetSuccess && (
        <div className="p-4 bg-emerald-50 text-emerald-800 text-xs font-semibold rounded-2xl border border-emerald-200 flex items-center gap-2 shadow-xs">
          <i className="bi bi-check-circle-fill text-emerald-600 text-base"></i>
          <span>{resetSuccess}</span>
        </div>
      )}

      <div className="bg-white border border-line rounded-2xl overflow-hidden shadow-xs">
        <div className="p-5 border-b border-line flex flex-col sm:flex-row sm:items-center justify-between gap-2">
          <div>
            <h2 className="text-sm font-extrabold text-[#0D47A1]">Bidan Desa Penanggung Jawab Klinis</h2>
            <p className="text-xs text-ink-500 mt-0.5">
              Tenaga kesehatan yang memiliki wewenang evaluasi kurva tumbuh kembang dan rujukan
            </p>
          </div>
          <span className="px-3 py-1 bg-[#2196F3] text-white text-xs font-semibold rounded-full self-start sm:self-auto">
            {bidanList.length} Tenaga Medis
          </span>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs border-collapse">
            <thead>
              <tr className="border-b border-line text-[#3D5F7D] font-bold text-xs">
                <th className="py-4 px-4">Nama Petugas</th>
                <th className="py-4 px-4">Nama Pengguna (Username)</th>
                <th className="py-4 px-4">Wilayah Tanggung Jawab</th>
                <th className="py-4 px-4">Kontak / Telepon</th>
                <th className="py-4 px-4">Tanggal Terdaftar</th>
                <th className="py-4 px-4 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-line">
              {bidanList.map((b) => (
                <tr key={b.id} className="hover:bg-slate-50/70 transition-base">
                  <td className="py-3.5 px-4">
                    <div className="flex items-center gap-3">
                      <div className="w-8 h-8 rounded-full bg-[#0D47A1] text-white font-bold flex items-center justify-center shrink-0 text-xs">
                        {b.nama.charAt(0)}
                      </div>
                      <div>
                        <strong className="text-[#0D47A1] block font-bold">{b.nama}</strong>
                      </div>
                    </div>
                  </td>
                  <td className="py-3.5 px-4">
                    <span className="font-semibold text-[#2196F3]">@{b.username}</span>
                  </td>
                  <td className="py-3.5 px-4 text-ink-700">
                    Seluruh Desa Sukatani
                  </td>
                  <td className="py-3.5 px-4 text-ink-700 whitespace-nowrap">
                    <i className="bi bi-telephone text-ink-400 me-1.5"></i>
                    <span>{b.telepon || '0812-3456-7892'}</span>
                  </td>
                  <td className="py-3.5 px-4 text-ink-600 whitespace-nowrap">
                    15 Des 2025
                  </td>
                  <td className="py-3.5 px-4 text-right whitespace-nowrap">
                    <button
                      onClick={() => handleResetPassword(b.nama)}
                      className="px-3 py-1.5 bg-white hover:bg-slate-50 text-[#2196F3] text-xs font-semibold rounded-lg border border-[#2196F3] flex items-center gap-1.5 ml-auto transition-base"
                    >
                      <i className="bi bi-key"></i>
                      <span>Reset Sandi</span>
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      <div className="bg-white border border-line rounded-2xl overflow-hidden shadow-xs">
        <div className="p-5 border-b border-line flex flex-col sm:flex-row sm:items-center justify-between gap-2">
          <div>
            <h2 className="text-sm font-extrabold text-[#0D47A1]">Kader Posyandu Tiap Dusun</h2>
            <p className="text-xs text-ink-500 mt-0.5">
              Petugas pencatatan antropometri dan pelayanan lapangan di pos masing-masing dusun
            </p>
          </div>
          <span className="px-3 py-1 bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold rounded-full self-start sm:self-auto">
            {kaderList.length} Kader Aktif
          </span>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs border-collapse">
            <thead>
              <tr className="border-b border-line text-[#3D5F7D] font-bold text-xs">
                <th className="py-4 px-4">Nama Kader</th>
                <th className="py-4 px-4">Nama Pengguna (Username)</th>
                <th className="py-4 px-4">Dusun Binaan</th>
                <th className="py-4 px-4">Nomor Telepon</th>
                <th className="py-4 px-4">Terdaftar Sejak</th>
                <th className="py-4 px-4 text-right">Aksi</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-line">
              {kaderList.map((k) => (
                <tr key={k.id} className="hover:bg-slate-50/70 transition-base">
                  <td className="py-3.5 px-4">
                    <div className="flex items-center gap-3">
                      <div className="w-8 h-8 rounded-full bg-[#1976D2] text-white font-bold flex items-center justify-center shrink-0 text-xs">
                        {k.nama.charAt(0)}
                      </div>
                      <div>
                        <strong className="text-[#0D47A1] block font-bold">{k.nama}</strong>
                      </div>
                    </div>
                  </td>
                  <td className="py-3.5 px-4">
                    <span className="font-semibold text-[#2196F3]">@{k.username}</span>
                  </td>
                  <td className="py-3.5 px-4 text-ink-700 whitespace-nowrap">
                    <i className="bi bi-geo-alt-fill text-[#2196F3] me-1.5"></i>
                    <span>Dusun {k.dusun_binaan}</span>
                  </td>
                  <td className="py-3.5 px-4 text-ink-700 whitespace-nowrap">
                    <i className="bi bi-telephone text-ink-400 me-1.5"></i>
                    <span>{k.telepon || '0812-3456-7890'}</span>
                  </td>
                  <td className="py-3.5 px-4 text-ink-600 whitespace-nowrap">
                    10 Jan 2026
                  </td>
                  <td className="py-3.5 px-4 text-right whitespace-nowrap">
                    <div className="inline-flex items-center gap-1.5">
                      <button
                        onClick={() => handleResetPassword(k.nama)}
                        className="px-2.5 py-1.5 bg-white hover:bg-slate-50 text-[#2196F3] text-xs font-semibold rounded-lg border border-[#2196F3] flex items-center gap-1 transition-base"
                      >
                        <i className="bi bi-key"></i>
                        <span>Reset</span>
                      </button>
                      <button
                        onClick={() => handleDeleteKader(k.id)}
                        className="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg border border-rose-200 transition-base"
                        title="Hapus Kader"
                      >
                        <i className="bi bi-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {showAddModal && (
        <div className="fixed inset-0 z-50 bg-slate-900/40 backdrop-blur-xs flex items-center justify-center p-4">
          <div className="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full border border-line shadow-panel space-y-4">
            <h3 className="text-lg font-extrabold text-[#0D47A1]">Tambah Petugas Posyandu Baru</h3>
            <p className="text-xs text-ink-500">Buat akun untuk kader dusun baru atau tenaga medis penanggung jawab.</p>
            <div className="space-y-3">
              <div>
                <label className="block text-xs font-bold text-ink-700 uppercase mb-1">Nama Lengkap</label>
                <input
                  type="text"
                  placeholder="Contoh: Siti Fatimah, Amd.Keb"
                  className="w-full px-3 py-2 text-xs border border-line rounded-xl focus:outline-none focus:border-[#2196F3]"
                />
              </div>
              <div>
                <label className="block text-xs font-bold text-ink-700 uppercase mb-1">Peran (Role)</label>
                <select className="w-full px-3 py-2 text-xs border border-line rounded-xl bg-slate-50">
                  <option value="kader">Kader Posyandu</option>
                  <option value="bidan">Bidan Desa</option>
                </select>
              </div>
              <div>
                <label className="block text-xs font-bold text-ink-700 uppercase mb-1">Wilayah Dusun Binaan</label>
                <select className="w-full px-3 py-2 text-xs border border-line rounded-xl bg-slate-50">
                  <option value="Gamping Wetan">Dusun Gamping Wetan</option>
                  <option value="Gamping Tengah">Dusun Gamping Tengah</option>
                  <option value="Boharan">Dusun Boharan</option>
                  <option value="Kasengan">Dusun Kasengan</option>
                  <option value="Bendomungal">Dusun Bendomungal</option>
                </select>
              </div>
            </div>
            <div className="pt-3 flex justify-end gap-2 border-t border-line">
              <button
                type="button"
                onClick={() => setShowAddModal(false)}
                className="px-4 py-2 text-xs font-semibold text-ink-700 border border-line rounded-xl hover:bg-slate-50"
              >
                Batal
              </button>
              <button
                type="button"
                onClick={() => {
                  setShowAddModal(false);
                  setResetSuccess('Akun kader baru berhasil ditambahkan.');
                  setTimeout(() => setResetSuccess(''), 3000);
                }}
                className="px-4 py-2 text-xs font-bold bg-[#2196F3] text-white rounded-xl hover:bg-[#1976D2]"
              >
                Simpan Akun
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
};
