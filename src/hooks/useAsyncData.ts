import { useCallback, useEffect, useState } from 'react';
import type { DependencyList } from 'react';

interface AsyncState<T> {
  data: T | undefined;
  loading: boolean;
  error: string;
}

export function useAsyncData<T>(fetcher: () => Promise<T>, deps: DependencyList = []) {
  const [tick, setTick] = useState(0);
  const [result, setResult] = useState<AsyncState<T>>({ data: undefined, loading: true, error: '' });

  useEffect(() => {
    let active = true;
    fetcher()
      .then((res) => {
        if (active) setResult({ data: res, loading: false, error: '' });
      })
      .catch((err: unknown) => {
        if (active) {
          setResult({
            data: undefined,
            loading: false,
            error: err instanceof Error ? err.message : 'Gagal memuat data dari server.'
          });
        }
      });
    return () => {
      active = false;
    };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [...deps, tick]);

  const reload = useCallback(() => {
    setResult((prev) => ({ ...prev, loading: true }));
    setTick((t) => t + 1);
  }, []);

  return { ...result, reload };
}
