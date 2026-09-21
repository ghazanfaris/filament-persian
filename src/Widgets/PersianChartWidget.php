<?php

namespace Sghazanfari\FilamentPersian\Widgets;

use Filament\Widgets\ChartWidget;
use Sghazanfari\FilamentPersian\Support\JalaliConverter;

abstract class PersianChartWidget extends ChartWidget
{
    // توجه: در v5، properties والد (مثل $heading, $columnSpan, $pollingInterval)
    // غیر-static هستند. پس اینجا اعلان نمی‌کنیم — فرزندها خودشان اعلان می‌کنند.

    /**
     * رنگ‌ها برای dataset ها.
     */
    protected array $palette = [
        '#0ea5e9', '#8b5cf6', '#10b981', '#f43f5e',
        '#f59e0b', '#3b82f6', '#ec4899', '#14b8a6',
    ];

    protected string $jalaliLabelPattern = 'm/d';

    protected function getJalaliLabels(array $dates): array
    {
        $conv = app(JalaliConverter::class);

        return array_map(
            fn ($date) => $conv->format($date, $this->jalaliLabelPattern),
            $dates
        );
    }

    protected function makeDataset(string $label, array $dates, array $values, int $colorIndex = 0): array
    {
        $color = $this->palette[$colorIndex % count($this->palette)];

        return [
            'label' => $label,
            'data' => $values,
            'borderColor' => $color,
            'backgroundColor' => $color . '33',
            'tension' => 0.4,
            'fill' => false,
            'pointBackgroundColor' => $color,
            'pointBorderColor' => '#fff',
            'pointRadius' => 3,
            'pointHoverRadius' => 5,
        ];
    }

    protected function makeBarDataset(string $label, array $values, int $colorIndex = 0): array
    {
        $color = $this->palette[$colorIndex % count($this->palette)];

        return [
            'label' => $label,
            'data' => $values,
            'backgroundColor' => $color,
            'borderColor' => $color,
            'borderWidth' => 1,
            'borderRadius' => 6,
        ];
    }

    /**
     * @return array{0: array<string>, 1: array<\Carbon\Carbon>}
     */
    protected function lastNDays(int $days = 30): array
    {
        $dates = [];
        $now = now();

        for ($i = $days - 1; $i >= 0; $i--) {
            $dates[] = $now->copy()->subDays($i);
        }

        return [$this->getJalaliLabels($dates), $dates];
    }

    /**
     * @return array{0: array<string>, 1: array<\Carbon\Carbon>}
     */
    protected function lastNMonths(int $months = 12): array
    {
        $dates = [];
        $now = now();

        for ($i = $months - 1; $i >= 0; $i--) {
            $dates[] = $now->copy()->subMonths($i)->startOfMonth();
        }

        return [$this->getJalaliLabels($dates), $dates];
    }

    /**
     * @return array{0: array<string>, 1: array<\Carbon\Carbon>}
     */
    protected function lastWeek(): array
    {
        $this->jalaliLabelPattern = 'D j M';
        $dates = [];
        $now = now();

        for ($i = 6; $i >= 0; $i--) {
            $dates[] = $now->copy()->subDays($i);
        }

        return [$this->getJalaliLabels($dates), $dates];
    }

    protected function getOptions(): array
    {
        $font = config('filament-persian.font.family', 'Vazirmatn');

        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                    'rtl' => true,
                    'textDirection' => 'rtl',
                    'labels' => [
                        'font' => [
                            'family' => $font,
                            'size' => 12,
                        ],
                        'usePointStyle' => true,
                        'padding' => 16,
                    ],
                ],
                'tooltip' => [
                    'rtl' => true,
                    'textDirection' => 'rtl',
                    'titleFont' => ['family' => $font, 'size' => 13],
                    'bodyFont' => ['family' => $font, 'size' => 12],
                    'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
                    'padding' => 12,
                    'cornerRadius' => 8,
                ],
            ],
            'scales' => [
                'x' => [
                    'ticks' => [
                        'font' => ['family' => $font, 'size' => 11],
                        'color' => '#6b7280',
                        'maxRotation' => 45,
                        'minRotation' => 0,
                    ],
                    'grid' => ['display' => false],
                ],
                'y' => [
                    'beginAtZero' => true,
                    'ticks' => [
                        'font' => ['family' => $font, 'size' => 11],
                        'color' => '#6b7280',
                    ],
                    'grid' => ['color' => 'rgba(0, 0, 0, 0.05)'],
                ],
            ],
        ];
    }
}