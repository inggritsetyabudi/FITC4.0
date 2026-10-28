import { Bar, Line } from 'react-chartjs-2';
import type { ChartData, ChartOptions } from 'chart.js';
import '../../utils/chartSetup';

interface ChartViewProps<T extends 'bar' | 'line'> {
  data: ChartData<T>;
  options: ChartOptions<T>;
  ariaLabel: string;
}

export function BarChartView({ data, options, ariaLabel }: ChartViewProps<'bar'>) {
  return <div role="img" aria-label={ariaLabel} className="h-full w-full"><Bar data={data} options={options} /></div>;
}

export function LineChartView({ data, options, ariaLabel }: ChartViewProps<'line'>) {
  return <div role="img" aria-label={ariaLabel} className="h-full w-full"><Line data={data} options={options} /></div>;
}
