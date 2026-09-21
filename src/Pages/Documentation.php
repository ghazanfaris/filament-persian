<?php

namespace Sghazanfari\FilamentPersian\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Documentation extends Page
{
    protected static ?string $navigationLabel = 'مستندات';
    protected static ?string $title = 'مستندات';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-book-open';
    protected static ?int $navigationSort = 98;

    protected string $view = 'filament-persian::pages.documentation';

    public ?string $activeSlug = 'introduction';
    public string $search = '';

    public function mount(): void
    {
        $docs = $this->getDocsProperty();

        // اگر ?doc=xxx در URL بود، آن را باز کن
        $requestedDoc = request()->query('doc');
        if ($requestedDoc && isset($docs[$requestedDoc])) {
            $this->activeSlug = $requestedDoc;
            return;
        }

        if (! empty($docs)) {
            $this->activeSlug = array_key_first($docs);
        }
    }
    public function selectDoc(string $slug): void
    {
        $this->activeSlug = $slug;

        // URL را آپدیت کن
        $this->dispatch('update-url', doc: $slug);
    }

    public function getDocsProperty(): array
    {
        $dir = __DIR__ . '/../../resources/docs';

        if (! File::isDirectory($dir)) {
            return [];
        }

        $docs = [];
        $files = File::files($dir);

        foreach ($files as $file) {
            if ($file->getExtension() !== 'md') {
                continue;
            }

            $slug = Str::of($file->getFilename())
                ->replaceFirst('.md', '')
                ->replaceMatches('/^\d+-/', '')
                ->toString();

            $content = File::get($file->getPathname());

            $title = $slug;
            if (preg_match('/^#\s+(.+)$/m', $content, $m)) {
                $title = trim($m[1]);
            }

            $docs[$slug] = [
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'filename' => $file->getFilename(),
            ];
        }

        return $docs;
    }

    public function getFilteredDocsProperty(): array
    {
        $docs = $this->getDocsProperty();

        if (trim($this->search) === '') {
            return $docs;
        }

        $needle = $this->normalize($this->search);

        return array_filter($docs, function ($doc) use ($needle) {
            return str_contains($this->normalize($doc['title']), $needle)
                || str_contains($this->normalize($doc['content']), $needle);
        });
    }

    public function getActiveDocProperty(): ?array
    {
        return $this->getDocsProperty()[$this->activeSlug] ?? null;
    }

    public function getActiveHtmlProperty(): string
    {
        $doc = $this->getActiveDocProperty();
        if (! $doc) {
            return '<p style="color:#6b7280;">مستندی انتخاب نشده است.</p>';
        }

        return (string) Str::markdown($doc['content'], [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }

    protected function normalize(string $text): string
    {
        return str_replace(
            ['ي', 'ك', 'ة', 'ۀ', 'ؤ'],
            ['ی', 'ک', 'ه', 'ه', 'و'],
            mb_strtolower(trim($text))
        );
    }
}