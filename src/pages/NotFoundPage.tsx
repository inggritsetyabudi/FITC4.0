import React from 'react';

export const NotFoundPage: React.FC<{ onNavigate: (page: string) => void }> = ({ onNavigate }) => {
  return (
    <div className="min-h-[60vh] flex flex-col items-center justify-center text-center p-6 space-y-4">
      <div className="w-16 h-16 rounded-2xl bg-danger-soft text-danger flex items-center justify-center text-3xl font-bold border border-danger-border">
        404
      </div>
      <h1 className="text-xl font-bold text-forest-900">Halaman Tidak Ditemukan</h1>
      <p className="text-xs text-ink-500 max-w-sm">
        Halaman yang Anda tuju mungkin sudah dipindahkan atau URL yang dimasukkan tidak valid.
      </p>
      <button
        onClick={() => onNavigate('dashboard')}
        className="px-5 py-2.5 bg-primary text-white text-xs font-semibold rounded-xl hover:bg-primary-hover shadow-xs transition-base"
      >
        Kembali ke Dasbor
      </button>
    </div>
  );
};
