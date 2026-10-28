import React from 'react';
import type { StatusGizi } from '../../types';
import { STATUS_CONFIG } from '../../utils/helpers';

export const StatusBadge: React.FC<{ status?: StatusGizi | null; className?: string }> = ({ status, className = '' }) => {
  if (!status) {
    return (
      <span className={`status-mark status-mark-empty ${className}`}>
        <span className="status-mark-dot" aria-hidden="true"></span> Belum diukur
      </span>
    );
  }

  const config = STATUS_CONFIG[status] || STATUS_CONFIG.normal;
  return (
    <span className={`status-mark status-mark-${status} ${className}`}>
      <span className="status-mark-dot" aria-hidden="true"></span> {config.label}
    </span>
  );
};
