import React from 'react';
import type { StatusGizi } from '../../types';
import { STATUS_CONFIG } from '../../utils/helpers';

export const StatusBadge: React.FC<{ status?: StatusGizi | null; className?: string }> = ({ status, className = '' }) => {
  if (!status) {
    return (
      <span className={`inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-surface-soft text-ink-500 border border-line ${className}`}>
        <i className="bi bi-dash-circle"></i> Belum Diukur
      </span>
    );
  }

  const config = STATUS_CONFIG[status] || STATUS_CONFIG.normal;
  return (
    <span className={`inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold ${config.className} ${className}`}>
      <i className={`bi ${config.icon}`}></i> {config.label}
    </span>
  );
};
