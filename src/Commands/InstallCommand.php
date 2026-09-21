<?php

namespace Sghazanfari\FilamentPersian\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'filament-persian:install
                            {--force : بازنویسی فایل‌های موجود}
                            {--no-assets : بدون انتشار asset ها}';

    protected $description = 'نصب و راه‌اندازی Filament Persian';

    public function handle(): int
    {
        $this->info('🚀 نصب Filament Persian...');
        $this->newLine();

        $this->publishConfig();
        $this->publishLang();

        if (! $this->option('no-assets')) {
            $this->publishAssets();
        }

        $this->createStorageFolder();
        $this->patchEnv();
        $this->showPostInstall();

        $this->newLine();
        $this->info('✅ نصب کامل شد.');

        return self::SUCCESS;
    }

    protected function publishConfig(): void
    {
        $this->line('📝 انتشار config...');

        $this->call('vendor:publish', [
            '--tag' => 'filament-persian-config',
            '--force' => $this->option('force'),
        ]);
    }

    protected function publishLang(): void
    {
        $this->line('🌐 انتشار ترجمه‌ها...');

        $this->call('vendor:publish', [
            '--tag' => 'filament-persian-lang',
            '--force' => $this->option('force'),
        ]);
    }

    protected function publishAssets(): void
    {
        $this->line('📦 انتشار asset ها...');

        $targetDir = public_path('vendor/filament-persian/js');
        if (! File::isDirectory($targetDir)) {
            File::makeDirectory($targetDir, 0755, true);
        }

        $sourceJs = [
            __DIR__ . '/../../resources/js/jalali-calendar.js',
            __DIR__ . '/../../resources/js/persian-numbers.js',
            __DIR__ . '/../../resources/js/command-palette.js',
        ];

        foreach ($sourceJs as $file) {
            if (File::exists($file)) {
                File::copy($file, $targetDir . '/' . basename($file));
            }
        }
    }

    protected function createStorageFolder(): void
    {
        $this->line('📁 ساخت پوشه تنظیمات...');

        $dir = storage_path('app/filament-persian');
        if (! File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
    }

    protected function patchEnv(): void
    {
        $env = base_path('.env');
        if (! File::exists($env)) {
            return;
        }

        $content = File::get($env);
        $additions = [
            'APP_LOCALE=fa',
            'APP_FALLBACK_LOCALE=en',
            'APP_TIMEZONE=Asia/Tehran',
        ];

        $changed = false;
        foreach ($additions as $line) {
            [$key] = explode('=', $line);

            if (preg_match("/^{$key}=.*$/m", $content)) {
                $content = preg_replace("/^{$key}=.*$/m", $line, $content);
                $changed = true;
            } elseif (! str_contains($content, "\n{$key}=")) {
                $content .= "\n{$line}";
                $changed = true;
            }
        }

        if ($changed) {
            File::put($env, $content);
            $this->info('📝 فایل .env به‌روزرسانی شد.');
        }
    }

    protected function showPostInstall(): void
    {
        $this->newLine();
        $this->line('👉 <fg=yellow>مراحل بعدی:</>');
        $this->newLine();
        $this->line('   <fg=cyan>1.</> پلاگین را در <fg=white>AdminPanelProvider</> اضافه کنید:');
        $this->line('      <fg=gray>->plugin(\\Sghazanfari\\FilamentPersian\\FilamentPersianPlugin::make())</>');
        $this->newLine();
        $this->line('   <fg=cyan>2.</> کش‌ها را پاک کنید:');
        $this->line('      <fg=gray>php artisan optimize:clear</>');
        $this->newLine();
        $this->line('   <fg=cyan>3.</> بررسی نصب:');
        $this->line('      <fg=gray>php artisan filament-persian:doctor</>');
    }
}