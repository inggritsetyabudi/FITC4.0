import React, { useState } from 'react';
import { useNavigate, useParams } from 'react-router-dom';
import { balitaService, pengukuranService } from '../services/api';
import { useAsyncData } from '../hooks/useAsyncData';
import { hitungUmur, formatTanggalId } from '../utils/helpers';
import { StatusBadge } from '../components/ui/StatusBadge';
import type { ChartData, ChartOptions } from 'chart.js';

const LineChartView = React.lazy(() => import('../components/ui/ChartViews').then((module) => ({ default: module.LineChartView })));

export const DetailBalitaPage: React.FC = () => {
  const { id } = useParams();
  const navigate = useNavigate();
  const balitaId = Number(id);
  const [chartMetric, setChartMetric] = useState<'bb' | 'tb'>('bb');

  const { data: balita, loading } = useAsyncData(() => balitaService.getById(balitaId), [balitaId]);
  const { data: riwayat = [] } = useAsyncData(
    () => pengukuranService.getByBalitaId(balitaId),
    [balitaId]
  );

  if (loading) {
    return (
      <div className="py-20 text-center text-ink-400">
        <i className="bi bi-arrow-repeat animate-spin text-3xl text-primary"></i>
        <p className="mt-2 text-xs font-semibold">Memuat data balita...</p>
      </div>
    );
  }

  if (!balita) {
    return (
      <div className="p-12 text-center bg-white rounded-2xl border border-line space-y-4 max-w-md mx-auto shadow-xs">
        <i className="bi bi-exclamation-circle text-4xl text-amber-500"></i>
        <h2 className="text-lg font-bold text-forest-900">Data Balita Tidak Ditemukan</h2>
        <p className="text-xs text-ink-500">Balita dengan ID #{balitaId} tidak ada dalam pangkalan data.</p>
        <button
          onClick={() => navigate('/balita')}
          className="px-5 py-2 bg-[#2196F3] text-white text-xs font-bold rounded-xl"
        >
          Kembali ke Daftar Balita
        </button>
      </div>
    );
  }

  const umur = hitungUmur(balita.tanggal_lahir);
  const sortedRiwayat = [...riwayat].sort(
    (a, b) => new Date(a.tanggal_ukur).getTime() - new Date(b.tanggal_ukur).getTime()
  );

  const prevPengukuran = sortedRiwayat.length > 1 ? sortedRiwayat[sortedRiwayat.length - 2] : null;
  const deltaBb = prevPengukuran && balita.berat_terakhir
    ? (balita.berat_terakhir - prevPengukuran.berat_kg).toFixed(1)
    : null;
  const growthValues = sortedRiwayat.map((item) => chartMetric === 'bb' ? item.berat_kg : item.tinggi_cm);
  const growthUnit = chartMetric === 'bb' ? 'kg' : 'cm';
  const growthMin = growthValues.length ? Math.max(0, Math.floor(Math.min(...growthValues) - 1)) : 0;
  const growthMax = growthValues.length ? Math.ceil(Math.max(...growthValues) + 1) : 1;
  const growthChartData: ChartData<'line'> = {
    labels: sortedRiwayat.map((item) => formatTanggalId(item.tanggal_ukur, true)),
    datasets: [{
      label: chartMetric === 'bb' ? 'Berat badan' : 'Tinggi badan',
      data: growthValues,
      borderColor: '#0D47A1',
      backgroundColor: 'rgba(13, 71, 161, .10)',
      fill: true,
      tension: .32,
      borderWidth: 3,
      pointRadius: 4,
      pointHoverRadius: 6,
      pointBackgroundColor: '#0D47A1',
      pointBorderColor: '#fff',
      pointBorderWidth: 2
    }]
  };
  const growthChartOptions: ChartOptions<'line'> = {
    responsive: true,
    maintainAspectRatio: false,
    interaction: { mode: 'index', intersect: false },
    plugins: {
      legend: { display: false },
      tooltip: {
        enabled: true,
        backgroundColor: '#0D2744',
        padding: 10,
        callbacks: { label: (context) => `${context.parsed.y} ${growthUnit}` }
      }
    },
    scales: {
      x: {
        grid: { display: false },
        border: { display: false },
        ticks: { color: '#607D99', maxRotation: 0, autoSkip: true, maxTicksLimit: 7, font: { size: 10 } }
      },
      y: {
        min: growthMin,
        max: growthMax,
        border: { display: false },
        grid: { color: '#E8EFF5' },
        ticks: { color: '#607D99', maxTicksLimit: 6, font: { size: 10 } }
      }
    }
  };

  return (
    <div className="child-detail-page space-y-6 max-w-6xl mx-auto">
      <div className="bg-white rounded-2xl border border-line p-6 shadow-xs">
        <div className="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-line gap-4">
          <div className="flex items-center gap-4">
            <div className="w-14 h-14 rounded-full bg-[#0D47A1] text-white text-2xl font-black flex items-center justify-center shrink-0 shadow-xs">
              {balita.nama.charAt(0)}
            </div>
            <div>
              <div className="flex items-center gap-2.5 flex-wrap">
                <h1 className="text-2xl font-black text-[#0D47A1]">{balita.nama}</h1>
                <StatusBadge status={balita.status_terakhir} />
              </div>
              <div className="text-xs text-[#607D99] mt-1">
                <span>{balita.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'}</span> &bull; <span>{umur.teks}</span> &bull; <span>Dusun {balita.dusun}</span>
              </div>
              <div className="text-xs text-[#607D99] mt-0.5">
                NIK: <span className="font-mono">{balita.nik || '-'}</span> &bull; Orang Tua: <strong>{balita.nama_ortu}</strong>
              </div>
            </div>
          </div>

          <button
            onClick={() => navigate(`/pengukuran?anak=${balita.id}`)}
            className="px-4 py-2 bg-[#2196F3] hover:bg-[#1976D2] text-white text-xs font-bold rounded-xl flex items-center gap-1.5 shadow-xs transition-base self-start sm:self-auto"
          >
            <i className="bi bi-clipboard-plus"></i>
            <span>Catat Pengukuran Baru</span>
          </button>
        </div>

        <div className="detail-stats grid grid-cols-2 lg:grid-cols-4 gap-4 pt-6">
          <div>
            <span className="text-xs text-[#607D99] block mb-1 font-medium">Berat Terakhir</span>
            <div className="text-2xl font-black text-[#0D47A1]">
              {balita.berat_terakhir ? `${balita.berat_terakhir.toFixed(1)} kg` : '-'}
            </div>
          </div>

          <div>
            <span className="text-xs text-[#607D99] block mb-1 font-medium">Tinggi Terakhir</span>
            <div className="text-2xl font-black text-[#0D47A1]">
              {balita.tinggi_terakhir ? `${balita.tinggi_terakhir.toFixed(1)} cm` : '-'}
            </div>
          </div>

          <div>
            <span className="text-xs text-[#607D99] block mb-1 font-medium">Perubahan BB</span>
            <div className="text-2xl font-black text-[#2196F3]">
              {deltaBb ? `${parseFloat(deltaBb) > 0 ? '+' : ''}${deltaBb} kg` : '-'}
            </div>
          </div>

          <div>
            <span className="text-xs text-[#607D99] block mb-1 font-medium">Tanggal Pengukuran</span>
            <div className="text-lg font-black text-[#0D47A1]">
              {balita.tanggal_ukur_terakhir ? formatTanggalId(balita.tanggal_ukur_terakhir) : '-'}
            </div>
          </div>
        </div>
      </div>

      <div className="bg-white rounded-2xl border border-line p-6 shadow-xs">
        <div className="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-line mb-6 gap-3">
          <div>
            <h2 className="text-base font-bold text-[#0D47A1]">Grafik Tren Pertumbuhan</h2>
            <p className="text-xs text-[#607D99]">
              Perkembangan berat dan tinggi badan balita dari kunjungan ke kunjungan
            </p>
          </div>
          <div className="inline-flex rounded-lg border border-line overflow-hidden shadow-xs">
            <button
              onClick={() => setChartMetric('bb')}
              className={`px-3.5 py-1.5 text-xs font-semibold transition-base ${
                chartMetric === 'bb'
                  ? 'bg-[#E3F2FD] text-[#2196F3] font-bold'
                  : 'bg-white text-ink-500 hover:bg-slate-50'
              }`}
            >
              Berat Badan (kg)
            </button>
            <button
              onClick={() => setChartMetric('tb')}
              className={`px-3.5 py-1.5 text-xs font-semibold border-l border-line transition-base ${
                chartMetric === 'tb'
                  ? 'bg-[#E3F2FD] text-[#2196F3] font-bold'
                  : 'bg-white text-ink-500 hover:bg-slate-50'
              }`}
            >
              Tinggi Badan (cm)
            </button>
          </div>
        </div>

        <div className="w-full h-56">
          {sortedRiwayat.length === 0 ? (
            <p className="text-center text-ink-500 py-20">Belum ada riwayat pengukuran.</p>
          ) : (
            <React.Suspense fallback={<div className="h-full animate-pulse rounded-xl bg-slate-50" />}>
              <LineChartView
                data={growthChartData}
                options={growthChartOptions}
                ariaLabel={`Grafik ${chartMetric === 'bb' ? 'berat' : 'tinggi'} badan ${balita.nama}`}
              />
            </React.Suspense>
          )}
        </div>
      </div>

      <div className="bg-white rounded-2xl border border-line p-6 shadow-xs">
        <div className="pb-4 border-b border-line mb-4">
          <h2 className="text-base font-bold text-[#0D47A1]">Riwayat Pengukuran Lengkap</h2>
          <p className="text-xs text-[#607D99]">
            Catatan penimbangan berat dan pengukuran tinggi badan bulanan
          </p>
        </div>

        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs border-collapse">
            <thead>
              <tr className="border-b border-line text-[#3D5F7D] font-bold text-xs">
                <th className="py-3.5 px-4">Tanggal</th>
                <th className="py-3.5 px-4">Usia</th>
                <th className="py-3.5 px-4">Berat Badan</th>
                <th className="py-3.5 px-4">Tinggi Badan</th>
                <th className="py-3.5 px-4 text-center">Status Skrining</th>
                <th className="py-3.5 px-4">Catatan</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-line">
              {sortedRiwayat.map((r) => (
                <tr key={r.id} className="hover:bg-slate-50/70 transition-base">
                  <td className="py-3 px-4 font-semibold text-[#0D47A1]">{formatTanggalId(r.tanggal_ukur)}</td>
                  <td className="py-3 px-4 text-[#3D5F7D]">{r.usia_bulan} bulan</td>
                  <td className="py-3 px-4 font-bold text-[#0D47A1]">{r.berat_kg.toFixed(1)} kg</td>
                  <td className="py-3 px-4 font-bold text-[#0D47A1]">{r.tinggi_cm.toFixed(1)} cm</td>
                  <td className="py-3 px-4 text-center">
                    <StatusBadge status={r.kategori_status} />
                  </td>
                  <td className="py-3 px-4 text-[#607D99] max-w-xs">{r.catatan || '-'}</td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
};
