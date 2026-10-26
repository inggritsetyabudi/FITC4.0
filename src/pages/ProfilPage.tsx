import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { authService } from '../services/api';

export const ProfilPage: React.FC = () => {
  const { user, logout, updateUser } = useAuth();
  const navigate = useNavigate();
  const [telepon, setTelepon] = useState<string>(user?.telepon || '');
  const [passwordLama, setPasswordLama] = useState<string>('');
  const [passwordBaru, setPasswordBaru] = useState<string>('');
  const [passwordKonfirm, setPasswordKonfirm] = useState<string>('');
  const [successMsg, setSuccessMsg] = useState<string>('');
  const [pwdMsg, setPwdMsg] = useState<string>('');
  const [pwdError, setPwdError] = useState<string>('');

  const handleSaveProfile = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!user) return;
    try {
      const updated = await authService.updateProfile(telepon);
      updateUser(updated);
      setSuccessMsg('Informasi profil berhasil disimpan.');
      setTimeout(() => setSuccessMsg(''), 3000);
    } catch (err) {
      setSuccessMsg('');
      setPwdError(err instanceof Error ? err.message : 'Gagal menyimpan profil.');
    }
  };

  const handleUpdatePassword = async (e: React.FormEvent) => {
    e.preventDefault();
    setPwdError('');
    if (passwordBaru !== passwordKonfirm) {
      setPwdMsg('Kata sandi baru dan konfirmasi tidak cocok.');
      return;
    }
    try {
      await authService.changePassword(passwordLama, passwordBaru);
      setPwdMsg('Kata sandi berhasil diperbarui.');
      setPasswordLama('');
      setPasswordBaru('');
      setPasswordKonfirm('');
      setTimeout(() => setPwdMsg(''), 3000);
    } catch (err) {
      setPwdMsg('');
      setPwdError(err instanceof Error ? err.message : 'Gagal memperbarui kata sandi.');
    }
  };

  const handleLogout = async () => {
    await logout();
    navigate('/login');
  };

  return (
    <div className="profile-page mx-auto space-y-6">
      <div className="space-y-1">
        <h1 className="text-2xl font-extrabold text-[#0D47A1]">Informasi Akun Petugas</h1>
        <p className="text-xs text-ink-500">
          Kelola identitas dan kontak operasional Posyandu.
        </p>
      </div>

      {successMsg && (
        <div className="p-4 bg-emerald-50 text-emerald-800 text-xs font-semibold rounded-2xl border border-emerald-200 flex items-center gap-2 shadow-xs">
          <i className="bi bi-check-circle-fill text-emerald-600"></i>
          <span>{successMsg}</span>
        </div>
      )}

      <div className="bg-white border border-line rounded-3xl p-6 sm:p-8 shadow-xs space-y-6">
        <div className="profile-identity flex items-center gap-4">
          <div className="w-14 h-14 rounded-full bg-[#0D47A1] text-white text-xl font-bold flex items-center justify-center shrink-0">
            {user?.role === 'admin' ? 'AD' : user?.role === 'bidan' ? 'AL' : 'SR'}
          </div>
          <div>
            <h2 className="text-base font-extrabold text-[#0D47A1]">{user?.nama}</h2>
            <div className="flex items-center gap-2 mt-1">
              <span className="px-3 py-0.5 rounded-full text-[11px] font-bold bg-blue-50 text-[#2196F3] border border-blue-200 flex items-center gap-1.5">
                <i className="bi bi-shield-check"></i>
                <span className="capitalize">{user?.role} Posyandu</span>
              </span>
              <span className="text-xs text-ink-500">
                &bull; {user?.dusun_binaan ? `Dusun ${user.dusun_binaan}` : 'Pemerintah Desa'}
              </span>
            </div>
          </div>
        </div>



        <form onSubmit={handleSaveProfile} className="space-y-4">
          <div>
            <label className="block text-xs font-bold text-ink-700 uppercase tracking-wider mb-1.5">
              Nama Pengguna (Username)
            </label>
            <input
              type="text"
              disabled
              value={user?.username || ''}
              className="w-full px-4 py-2.5 text-xs bg-slate-50 border border-line rounded-xl text-ink-500 cursor-not-allowed font-medium"
            />
          </div>

          <div>
            <label className="block text-xs font-bold text-ink-700 uppercase tracking-wider mb-1.5">
              Nama Lengkap
            </label>
            <input
              type="text"
              disabled
              value={user?.nama || ''}
              className="w-full px-4 py-2.5 text-xs bg-slate-50 border border-line rounded-xl text-ink-500 cursor-not-allowed font-medium"
            />
          </div>

          <div>
            <label className="block text-xs font-bold text-ink-700 uppercase tracking-wider mb-1.5">
              Nomor WhatsApp / Telepon
            </label>
            <input
              type="text"
              value={telepon}
              onChange={(e) => setTelepon(e.target.value)}
              className="w-full px-4 py-2.5 text-xs sm:text-sm bg-white border border-line rounded-xl focus:outline-none focus:border-[#2196F3] font-medium"
            />
          </div>

          <button
            type="submit"
            className="w-full py-3 bg-[#2196F3] hover:bg-[#1976D2] text-white text-xs sm:text-sm font-bold rounded-xl flex items-center justify-center gap-2 shadow-xs transition-base"
          >
            <i className="bi bi-check2"></i>
            <span>Simpan Perubahan Profil</span>
          </button>
        </form>
      </div>

      <div className="bg-white border border-line rounded-3xl p-6 sm:p-8 shadow-xs space-y-5">
        <div className="flex items-center gap-2 text-ink-900 font-extrabold text-sm">
          <i className="bi bi-key text-[#2196F3]"></i>
          <span>Ganti Kata Sandi</span>
        </div>

        <p className="profile-password-description text-xs text-ink-500">Perbarui kata sandi akun {user?.role === 'admin' ? 'administrator' : 'petugas'} untuk menjaga keamanan akses.</p>
        {pwdMsg && (
          <div className="p-3 bg-blue-50 text-[#0D47A1] text-xs font-semibold rounded-xl border border-blue-200">
            {pwdMsg}
          </div>
        )}
        {pwdError && (
          <div className="p-3 bg-rose-50 text-rose-700 text-xs font-semibold rounded-xl border border-rose-200">
            {pwdError}
          </div>
        )}

        <form onSubmit={handleUpdatePassword} className="space-y-4">
          <div>
            <label className="block text-xs font-bold text-ink-700 uppercase tracking-wider mb-1.5">
              Kata Sandi Saat Ini <span className="text-[#D32F2F]">*</span>
            </label>
            <input
              type="password"
              value={passwordLama}
              onChange={(e) => setPasswordLama(e.target.value)}
              placeholder="Masukkan kata sandi saat ini"
              className="w-full px-4 py-2.5 text-xs sm:text-sm bg-white border border-line rounded-xl focus:outline-none focus:border-[#2196F3]"
            />
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label className="block text-xs font-bold text-ink-700 uppercase tracking-wider mb-1.5">
                Kata Sandi Baru <span className="text-[#D32F2F]">*</span>
              </label>
              <input
                type="password"
                value={passwordBaru}
                onChange={(e) => setPasswordBaru(e.target.value)}
                placeholder="Minimal 6 karakter"
                className="w-full px-4 py-2.5 text-xs sm:text-sm bg-white border border-line rounded-xl focus:outline-none focus:border-[#2196F3]"
              />
            </div>
            <div>
              <label className="block text-xs font-bold text-ink-700 uppercase tracking-wider mb-1.5">
                Ulangi Kata Sandi Baru <span className="text-[#D32F2F]">*</span>
              </label>
              <input
                type="password"
                value={passwordKonfirm}
                onChange={(e) => setPasswordKonfirm(e.target.value)}
                placeholder="Konfirmasi kata sandi"
                className="w-full px-4 py-2.5 text-xs sm:text-sm bg-white border border-line rounded-xl focus:outline-none focus:border-[#2196F3]"
              />
            </div>
          </div>

          <button
            type="submit"
            className="w-full py-3 bg-white hover:bg-slate-50 text-[#2196F3] text-xs sm:text-sm font-bold rounded-xl border border-[#2196F3] flex items-center justify-center gap-2 transition-base"
          >
            <i className="bi bi-shield-lock"></i>
            <span>Perbarui Kata Sandi</span>
          </button>
        </form>

        <div className="profile-logout pt-2 text-center">
          <button
            type="button"
            onClick={handleLogout}
            className="px-5 py-2 text-xs font-bold text-[#D32F2F] hover:bg-rose-50 border border-rose-200 rounded-xl inline-flex items-center gap-1.5 transition-base"
          >
            <i className="bi bi-box-arrow-right"></i>
            <span>Keluar dari Akun</span>
          </button>
        </div>
      </div>
    </div>
  );
};
