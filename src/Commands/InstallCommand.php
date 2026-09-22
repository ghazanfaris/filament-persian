<?php

namespace Sghazanfari\FilamentPersian\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'filament-persian:install
                            {--force : بازنویسی فایل‌های موجود}
                            {--no-assets : بدون انتشار asset ها}
                            {--no-fonts : بدون کپی فونت‌ها}';

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

        if (! $this->option('no-fonts')) {
            $this->publishFonts();
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

        // CSS
        $targetCss = public_path('vendor/filament-persian/css');
        if (! File::isDirectory($targetCss)) {
            File::makeDirectory($targetCss, 0755, true);
        }

        $sourceCss = __DIR__ . '/../../resources/css/filament-persian.css';
        if (File::exists($sourceCss)) {
            File::copy($sourceCss, $targetCss . '/filament-persian.css');
        }
    }

    protected function publishFonts(): void
    {
        $this->line('✍️  انتشار فونت‌های فارسی...');

        $sourceFonts = __DIR__ . '/../../resources/fonts';

        if (! File::isDirectory($sourceFonts)) {
            $this->warn('  پوشه فونت‌های پکیج پیدا نشد.');
            return;
        }

        $targetFonts = public_path('fonts');

        if (! File::isDirectory($targetFonts)) {
            File::makeDirectory($targetFonts, 0755, true);
        }

        $fontDirs = File::directories($sourceFonts);
        $copied = 0;
        $skipped = 0;
        $invalid = 0;

        foreach ($fontDirs as $sourceDir) {
            $fontName = basename($sourceDir);

            // فقط پوشه‌هایی که فایل CSS با همان نام دارند
            $cssFile = $sourceDir . '/' . $fontName . '.css';
            if (! File::exists($cssFile)) {
                $invalid++;
                continue;
            }

            $targetDir = $targetFonts . '/' . $fontName;

            // اگر پوشه وجود دارد و --force نیست، رد کن
            if (File::isDirectory($targetDir) && ! $this->option('force')) {
                $skipped++;
                continue;
            }

            // اگر وجود دارد، پاک کن (تا فایل‌های قدیمی نمانند)
            if (File::isDirectory($targetDir)) {
                File::deleteDirectory($targetDir);
            }

            File::makeDirectory($targetDir, 0755, true);
            File::copyDirectory($sourceDir, $targetDir);
            $copied++;
        }

        if ($copied > 0) {
            $this->info("  ✓ {$copied} فونت کپی شد.");
        }

        if ($skipped > 0) {
            $this->line("  ⊘ {$skipped} فونت از قبل موجود بود (برای بازنویسی: --force)");
        }

        if ($invalid > 0) {
            $this->line("  ⊘ {$invalid} پوشه غیرفونت نادیده گرفته شد.");
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
            $this->info('  ✓ فایل .env به‌روزرسانی شد.');
        }
    }

    protected function showPostInstall(): void
    {
        $this->newLine();
        $this->line('👉 <fg=yellow>مراحل بعدی:</>');
        $this->newLine();
        $this->line('   <fg=cyan>1.</> پلاگین را در <fg=white>AdminPanelProvider</> ثبت کنید:');
        $this->line('      <fg=gray>->plugin(\\Sghazanfari\\FilamentPersian\\FilamentPersianPlugin::make())</>');
        $this->newLine();
        $this->line('   <fg=cyan>2.</> کش‌ها را پاک کنید:');
        $this->line('      <fg=gray>php artisan optimize:clear</>');
        $this->newLine();
        $this->line('   <fg=cyan>3.</> بررسی نصب:');
        $this->line('      <fg=gray>php artisan filament-persian:doctor</>');
    }
}