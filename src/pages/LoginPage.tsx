import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import type { UserRole } from '../types';

export const LoginPage: React.FC = () => {
  const { login } = useAuth();
  const navigate = useNavigate();
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [errorMsg, setErrorMsg] = useState('');
  const [loading, setLoading] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setErrorMsg('');
    setLoading(true);

    try {
      await login(username, password);
      navigate('/dashboard');
    } catch (err) {
      setErrorMsg(err instanceof Error ? err.message : 'Gagal terhubung ke layanan server.');
    } finally {
      setLoading(false);
    }
  };

  const handleRoleQuickSelect = async (role: UserRole) => {
    setUsername(role);
    setPassword('123');
    setLoading(true);
    setErrorMsg('');
    try {
      await login(role, '123');
      navigate('/dashboard');
    } catch (err) {
      setErrorMsg(err instanceof Error ? err.message : 'Gagal terhubung ke layanan server.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="login-page min-h-screen flex flex-col lg:flex-row bg-[#F0F7FF] font-sans">
      <div className="login-photo relative hidden lg:flex lg:w-1/2 bg-[#0D47A1] overflow-hidden">
        <img
          src="/images/desain-login-photo.png"
          alt="Pelayanan Posyandu Desa"
          className="absolute inset-0 w-full h-full object-cover"
        />
        <div className="absolute inset-0 login-shade"></div>

        <div className="login-photo-content relative z-10 p-12 lg:p-16 flex flex-col justify-between h-full text-white">
          <button
            onClick={() => navigate('/')}
            className="flex items-center gap-3 text-left focus:outline-none w-fit"
          >
            <div className="w-10 h-10 rounded-xl bg-white text-[#0D47A1] flex items-center justify-center text-xl font-bold shadow-md">
              <i className="bi bi-heart-fill"></i>
            </div>
            <span className="font-extrabold text-white text-xl tracking-tight">Posyandu Pintar</span>
          </button>

          <div className="space-y-4 max-w-lg">
            <span className="inline-block px-3.5 py-1 rounded-full text-[11px] font-bold uppercase tracking-wider bg-white/20 text-white backdrop-blur-xs">
              Posyandu Pintar
            </span>
            <h1 className="text-3xl lg:text-4xl font-extrabold leading-tight text-white">
              Pencatatan lebih rapi, pemantauan lebih mudah.
            </h1>
            <p className="text-sm lg:text-base text-blue-100/90 leading-relaxed">
              Kelola data balita, penimbangan, dan pemantauan tumbuh kembang dalam satu sistem terpadu desa.
            </p>
          </div>


        </div>
      </div>

      <div className="login-form-side flex-1 flex items-center justify-center p-6 sm:p-12">
        <div className="login-form w-full space-y-6">
          <div className="space-y-1.5">
            <span className="text-[11px] font-extrabold uppercase tracking-widest text-[#2196F3] block">
              Selamat Datang
            </span>
            <h2 className="text-2xl font-extrabold text-[#0D47A1]">Masuk ke Posyandu Pintar</h2>
            <p className="text-xs text-ink-500">
              Silakan masukkan akun petugas Posyandu Anda
            </p>
          </div>

          {errorMsg && (
            <div className="p-3 bg-rose-50 text-rose-700 text-xs font-semibold rounded-xl border border-rose-200 flex items-center gap-2">
              <i className="bi bi-exclamation-circle-fill"></i>
              <span>{errorMsg}</span>
            </div>
          )}

          <form onSubmit={handleSubmit} className="space-y-4">
            <div>
              <label className="block text-xs font-bold text-ink-700 uppercase tracking-wider mb-1.5">
                Nama Pengguna / Akun <span className="text-[#D32F2F]">*</span>
              </label>
              <input
                type="text"
                value={username}
                onChange={(e) => setUsername(e.target.value)}
                placeholder="Masukkan nama pengguna"
                className="w-full px-4 py-2.5 text-xs sm:text-sm bg-white border border-blue-200 rounded-xl focus:outline-none focus:border-[#2196F3] transition-base"
                required
              />
            </div>

            <div>
              <label className="block text-xs font-bold text-ink-700 uppercase tracking-wider mb-1.5">
                Kata Sandi <span className="text-[#D32F2F]">*</span>
              </label>
              <input
                type="password"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                placeholder="Masukkan kata sandi"
                className="w-full px-4 py-2.5 text-xs sm:text-sm bg-white border border-blue-200 rounded-xl focus:outline-none focus:border-[#2196F3] transition-base"
                required
              />
            </div>

            <button
              type="submit"
              disabled={loading}
              className="w-full py-3 px-4 bg-[#2196F3] hover:bg-[#1976D2] text-white rounded-xl flex items-center justify-center gap-2 shadow-xs font-bold text-xs sm:text-sm transition-base"
            >
              {loading ? (
                <span>Memproses...</span>
              ) : (
                <>
                  <i className="bi bi-box-arrow-in-right"></i>
                  <span>Masuk</span>
                </>
              )}
            </button>

            <button
              type="button"
              onClick={() => navigate('/')}
              className="w-full py-2.5 px-4 bg-white hover:bg-slate-50 text-ink-700 text-center text-xs font-bold rounded-xl border border-line transition-base"
            >
              Kembali ke Beranda
            </button>
          </form>

          {(
            <div className="login-role-buttons pt-2 flex items-center gap-2">
              <button
                type="button"
                onClick={() => handleRoleQuickSelect('admin')}
                className="px-5 py-2 bg-[#2196F3] hover:bg-[#1976D2] text-white text-xs font-bold rounded-full transition-base shadow-xs"
              >
                Admin
              </button>
              <button
                type="button"
                onClick={() => handleRoleQuickSelect('bidan')}
                className="px-5 py-2 bg-[#2196F3] hover:bg-[#1976D2] text-white text-xs font-bold rounded-full transition-base shadow-xs"
              >
                Bidan
              </button>
              <button
                type="button"
                onClick={() => handleRoleQuickSelect('kader')}
                className="px-5 py-2 bg-[#2196F3] hover:bg-[#1976D2] text-white text-xs font-bold rounded-full transition-base shadow-xs"
              >
                Kader
              </button>
            </div>
          )}
        </div>
      </div>
    </div>
  );
};
