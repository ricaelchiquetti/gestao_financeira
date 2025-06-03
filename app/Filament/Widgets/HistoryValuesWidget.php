<?php

namespace App\Filament\Widgets;

use App\Models\FinanceTransaction;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class HistoryValuesWidget extends ChartWidget
{
    protected static ?string $heading = 'Histórico de Receitas e Despesas (Últimos 12 Meses)';

    protected int | string | array $columnSpan = 12;

    protected function getData(): array
    {
        $endDate = Carbon::now()->endOfMonth();
        $startDate = Carbon::now()->subMonths(11)->startOfMonth();

        // Buscar receitas agrupadas por mês
        $revenuesByMonth = FinanceTransaction::whereHas('accountPlan.accountPlanType', function ($query) {
                $query->where('type', 'revenue');
            })
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->selectRaw("SUM(value) as total_value")
            ->selectRaw("DATE_FORMAT(transaction_date, '%Y-%m') as yearmonth")
            ->groupByRaw("DATE_FORMAT(transaction_date, '%Y-%m')")
            ->orderByRaw("DATE_FORMAT(transaction_date, '%Y-%m') ASC")
            ->pluck('total_value', 'yearmonth');

        // Buscar despesas agrupadas por mês
        $expensesByMonth = FinanceTransaction::whereHas('accountPlan.accountPlanType', function ($query) {
                $query->where('type', 'expense');
            })
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->selectRaw("SUM(value) as total_value")
            ->selectRaw("DATE_FORMAT(transaction_date, '%Y-%m') as yearmonth")
            ->groupByRaw("DATE_FORMAT(transaction_date, '%Y-%m')")
            ->orderByRaw("DATE_FORMAT(transaction_date, '%Y-%m') ASC")
            ->pluck('total_value', 'yearmonth');

        $labels = [];
        $revenueData = [];
        $expenseData = [];

        $currentMonth = $startDate->copy();
        while ($currentMonth <= $endDate) {
            $monthKey = $currentMonth->format('Y-m');
            $labels[] = $currentMonth->translatedFormat('M/y'); // Ex: Jan/23

            // Assumindo que os valores são armazenados em centavos
            $revenueData[] = ($revenuesByMonth[$monthKey] ?? 0) / 100;
            $expenseData[] = ($expensesByMonth[$monthKey] ?? 0) / 100;

            $currentMonth->addMonthNoOverflow();
        }

        return [
            'datasets' => [
                ['label' => 'Receitas', 'data' => $revenueData, 'borderColor' => 'rgb(75, 192, 192)', 'tension' => 0.1],
                ['label' => 'Despesas', 'data' => $expenseData, 'borderColor' => 'rgb(255, 99, 132)', 'tension' => 0.1],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
