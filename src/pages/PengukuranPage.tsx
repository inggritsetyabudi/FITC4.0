import React, { useState, useEffect } from 'react';
import { balitaService, pengukuranService } from '../services/api';
import type { Balita, StatusGizi } from '../types';
import { hitungUmurLengkap, formatTanggalId } from '../utils/helpers';

export const PengukuranPage: React.FC<{
  preselectedId?: number;
  onNavigate: (page: string, params?: any) => void;
}> = ({ preselectedId, onNavigate }) => {
  const [daftarBalita, setDaftarBalita] = useState<Balita[]>([]);
  const [selectedId, setSelectedId] = useState<number>(preselectedId || 0);
  const [tanggalUkur, setTanggalUkur] = useState<string>(new Date().toISOString().split('T')[0]);
  const [beratKg, setBeratKg] = useState<string>('');
  const [tinggiCm, setTinggiCm] = useState<string>('');
  const [catatan, setCatatan] = useState<string>('');
  const [loading, setLoading] = useState<boolean>(false);
  const [successMsg, setSuccessMsg] = useState<string>('');
  const [errorMsg, setErrorMsg] = useState<string>('');

  useEffect(() => {
    balitaService.getAll().then((list) => {
      setDaftarBalita(list);
      if (!selectedId && list.length > 0) {
        setSelectedId(list[0].id);
      }
    });
  }, []);

  const selectedBalita = daftarBalita.find((b) => b.id === selectedId);
  const umur = selectedBalita ? hitungUmurLengkap(selectedBalita.tanggal_lahir, tanggalUkur) : null;

  const prevBerat = selectedBalita?.berat_terakhir;
  const prevTinggi = selectedBalita?.tinggi_terakhir;

  const currentBeratNum = parseFloat(beratKg) || 0;
  const currentTinggiNum = parseFloat(tinggiCm) || 0;

  const deltaBb = prevBerat && currentBeratNum ? (currentBeratNum - prevBerat).toFixed(2) : null;
  const isBbTurun = deltaBb !== null && parseFloat(deltaBb) < 0;
  const isTbAnomali = Boolean(prevTinggi && currentTinggiNum && currentTinggiNum < prevTinggi);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!selectedId || !currentBeratNum || !currentTinggiNum) {
      setErrorMsg('Mohon lengkapi balita sasaran, berat badan, dan tinggi badan anak.');
      return;
    }

    setLoading(true);
    setErrorMsg('');

    let status: StatusGizi = 'normal';
    if (deltaBb && parseFloat(deltaBb) < -0.3) {
      status = 'prioritas';
    } else if (deltaBb && parseFloat(deltaBb) <= 0) {
      status = 'pemantauan';
    }

    try {
      await pengukuranService.create({
        anak_id: selectedId,
        nama_anak: selectedBalita?.nama,
        dusun: selectedBalita?.dusun,
        tanggal_ukur: tanggalUkur,
        usia_bulan: umur?.total_bulan || 0,
        berat_kg: currentBeratNum,
        tinggi_cm: currentTinggiNum,
        kategori_status: status,
        catatan: catatan.trim() || undefined
      });

      setSuccessMsg(`Hasil penimbangan untuk ${selectedBalita?.nama} berhasil disimpan.`);
      setBeratKg('');
      setTinggiCm('');
      setCatatan('');

      setTimeout(() => {
        setSuccessMsg('');
        onNavigate('detail_balita', { id: selectedId });
      }, 1500);
    } catch {
      setErrorMsg('Gagal menyimpan hasil pengukuran. Silakan coba kembali.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="measurement-page mx-auto space-y-6">
      <div>
        <h1 className="text-2xl font-bold text-[#0D47A1]">Catat Pengukuran Bulanan</h1>
        <p className="text-xs text-[#607D99] mt-1 font-medium">
          Masukkan hasil penimbangan berat dan pengukuran tinggi badan anak
        </p>
      </div>

      {successMsg && (
        <div className="p-4 bg-emerald-50 text-emerald-800 text-xs font-semibold rounded-2xl border border-emerald-200 flex items-center gap-2 shadow-xs">
          <i className="bi bi-check-circle-fill text-emerald-600 text-base"></i>
          <span>{successMsg}</span>
        </div>
      )}

      {errorMsg && (
        <div className="p-4 bg-rose-50 text-rose-700 text-xs font-semibold rounded-2xl border border-rose-200 flex items-center gap-2 shadow-xs">
          <i className="bi bi-exclamation-triangle-fill text-rose-600 text-base"></i>
          <span>{errorMsg}</span>
        </div>
      )}

      <div className="bg-white border border-line rounded-2xl p-6 sm:p-8 shadow-xs">
        <form onSubmit={handleSubmit} className="space-y-5">
          <div>
            <label className="block text-xs font-bold text-[#3D5F7D] mb-1.5">
              Pilih Balita <span className="text-[#D32F2F]">*</span>
            </label>
            <select
              value={selectedId.toString()}
              onChange={(e) => setSelectedId(parseInt(e.target.value, 10))}
              className="w-full px-4 py-2.5 text-xs sm:text-sm font-semibold bg-white border border-line rounded-xl focus:outline-none focus:border-[#2196F3] transition-base"
            >
              {daftarBalita.map((b) => (
                <option key={b.id} value={b.id.toString()}>
                  {b.nama} &bull; Dusun {b.dusun}
                </option>
              ))}
            </select>
          </div>

          {selectedBalita && umur && (
            <div className="p-4 bg-[#F0F7FF] border border-[#D1E3F2] rounded-xl flex items-start justify-between gap-3 text-xs">
              <div className="space-y-1">
                <span className="font-bold text-[#0D47A1] text-sm block">{selectedBalita.nama}</span>
                <div className="text-xs text-[#607D99]">
                  Orang Tua: <strong className="text-[#3D5F7D]">{selectedBalita.nama_ortu}</strong> &bull; Dusun {selectedBalita.dusun}
                </div>
                <div className="text-xs text-[#607D99]">
                  Catatan Terakhir: <strong className="text-[#0D47A1]">{selectedBalita.berat_terakhir ? `${selectedBalita.berat_terakhir} kg · ${selectedBalita.tinggi_terakhir} cm (${formatTanggalId(selectedBalita.tanggal_ukur_terakhir, true)})` : 'Belum pernah diukur'}</strong>
                </div>
              </div>
              <span className="px-3 py-1 rounded-full bg-white text-[#3D5F7D] font-medium border border-[#D1E3F2] text-xs shrink-0">
                {umur.teks}
              </span>
            </div>
          )}

          <div>
            <label className="block text-xs font-bold text-[#3D5F7D] mb-1.5">
              Tanggal Pengukuran <span className="text-[#D32F2F]">*</span>
            </label>
            <input
              type="date"
              value={tanggalUkur}
              onChange={(e) => setTanggalUkur(e.target.value)}
              className="w-full px-4 py-2.5 text-xs sm:text-sm bg-white border border-line rounded-xl focus:outline-none focus:border-[#2196F3] transition-base"
              required
            />
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label className="block text-xs font-bold text-[#3D5F7D] mb-1.5">
                Berat Badan <span className="text-[#D32F2F]">*</span>
              </label>
              <div className="flex items-center">
                <input
                  type="number"
                  step="0.05"
                  min="1"
                  max="40"
                  value={beratKg}
                  onChange={(e) => setBeratKg(e.target.value)}
                  placeholder="Contoh: 10.8"
                  className="w-full px-4 py-2.5 text-xs sm:text-sm font-semibold bg-white border border-line rounded-l-xl focus:outline-none focus:border-[#2196F3] transition-base"
                  required
                />
                <span className="px-3.5 py-2.5 bg-[#F0F7FF] border border-l-0 border-line text-xs font-bold text-[#607D99] rounded-r-xl">
                  kg
                </span>
              </div>
              <div className="flex items-center justify-between mt-1 text-[11px] text-[#607D99]">
                <span>Gunakan titik untuk desimal (contoh: 10.8)</span>
                {deltaBb && (
                  <span className={`font-bold ${parseFloat(deltaBb) < 0 ? 'text-rose-600' : 'text-emerald-600'}`}>
                    Selisih: {parseFloat(deltaBb) >= 0 ? '+' : ''}{deltaBb} kg
                  </span>
                )}
              </div>
            </div>

            <div>
              <label className="block text-xs font-bold text-[#3D5F7D] mb-1.5">
                Tinggi / Panjang Badan <span className="text-[#D32F2F]">*</span>
              </label>
              <div className="flex items-center">
                <input
                  type="number"
                  step="0.1"
                  min="30"
                  max="140"
                  value={tinggiCm}
                  onChange={(e) => setTinggiCm(e.target.value)}
                  placeholder="Contoh: 83.1"
                  className="w-full px-4 py-2.5 text-xs sm:text-sm font-semibold bg-white border border-line rounded-l-xl focus:outline-none focus:border-[#2196F3] transition-base"
                  required
                />
                <span className="px-3.5 py-2.5 bg-[#F0F7FF] border border-l-0 border-line text-xs font-bold text-[#607D99] rounded-r-xl">
                  cm
                </span>
              </div>
              <div className="mt-1 text-[11px] text-[#607D99]">
                Tidur (&lt; 2 thn) / berdiri (&ge; 2 thn)
              </div>
            </div>
          </div>

          {isTbAnomali && (
            <div className="p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-700 text-xs flex items-start gap-2.5">
              <i className="bi bi-exclamation-triangle-fill text-base shrink-0 mt-0.5"></i>
              <div>
                <strong>Peringatan Anomali Tinggi:</strong> Tinggi anak saat ini ({currentTinggiNum} cm) lebih rendah dari bulan lalu ({prevTinggi} cm). Pastikan posisi stadiometer sudah tepat.
              </div>
            </div>
          )}

          {isBbTurun && (
            <div className="p-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 text-xs flex items-start gap-2.5">
              <i className="bi bi-info-circle-fill text-base shrink-0 mt-0.5"></i>
              <div>
                <strong>Perhatian Kenaikan BB:</strong> Berat badan anak mengalami penurunan {deltaBb} kg. Kasus ini akan ditandai otomatis untuk Bidan Desa.
              </div>
            </div>
          )}

          <div>
            <label className="block text-xs font-bold text-ink-700 uppercase tracking-wider mb-1.5">
              Catatan Tambahan (Opsional)
            </label>
            <textarea
              className="w-full px-4 py-2.5 text-xs sm:text-sm bg-white border border-line rounded-xl focus:outline-none focus:border-[#2196F3] transition-base"
              rows={3}
              value={catatan}
              onChange={(e) => setCatatan(e.target.value)}
              placeholder="Kondisi kesehatan, nafsu makan, atau imunisasi terbaru..."
            />
          </div>

          <div className="pt-2">
            <button
              type="submit"
              disabled={loading}
              className="w-full py-3 bg-[#2196F3] hover:bg-[#1976D2] text-white text-sm font-semibold rounded-xl flex items-center justify-center gap-2 shadow-xs transition-base"
            >
              <i className="bi bi-check-circle text-base"></i>
              <span>{loading ? 'Menyimpan...' : 'Simpan Pengukuran'}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};
