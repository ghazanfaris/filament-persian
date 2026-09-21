<?php

namespace Sghazanfari\FilamentPersian\Pages;

use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class EditProfile extends Page
{
    protected static ?string $navigationLabel = 'پروفایل من';
    protected static ?string $title = 'پروفایل من';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';
    protected static ?int $navigationSort = 90;
    protected static bool $shouldRegisterNavigation = false; // در منوی کاربر نمایش داده می‌شود

    protected string $view = 'filament-persian::pages.edit-profile';

    public ?array $data = [];

    public function mount(): void
    {
        $user = Filament::auth()->user();

        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar ?? null,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('اطلاعات شخصی')
                    ->description('نام و ایمیل خود را ویرایش کنید.')
                    ->schema([
                        FileUpload::make('avatar')
                            ->label('تصویر پروفایل')
                            ->avatar()
                            ->image()
                            ->imageEditor()
                            ->circleCropper()
                            ->disk('public')
                            ->directory('avatars')
                            ->maxSize(2048)
                            ->columnSpanFull(),

                        TextInput::make('name')
                            ->label('نام')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('ایمیل')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('تغییر رمز عبور')
                    ->description('اگر نمی‌خواهید رمز را عوض کنید، این بخش را خالی بگذارید.')
                    ->schema([
                        TextInput::make('current_password')
                            ->label('رمز عبور فعلی')
                            ->password()
                            ->revealable()
                            ->currentPassword()
                            ->dehydrated(false),

                        TextInput::make('password')
                            ->label('رمز عبور جدید')
                            ->password()
                            ->revealable()
                            ->rule(Password::default())
                            ->confirmed()
                            ->dehydrated(fn ($state) => filled($state)),

                        TextInput::make('password_confirmation')
                            ->label('تکرار رمز عبور جدید')
                            ->password()
                            ->revealable()
                            ->dehydrated(false),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $user = Filament::auth()->user();

        // اگر رمز جدید وارد شده، چک کن رمز فعلی درست باشد
        if (! empty($data['password'])) {
            if (! Hash::check($data['current_password'] ?? '', $user->password)) {
                Notification::make()
                    ->title('رمز عبور فعلی اشتباه است')
                    ->danger()
                    ->send();
                return;
            }

            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        unset($data['current_password'], $data['password_confirmation']);

        $user->update($data);

        Notification::make()
            ->title('پروفایل با موفقیت به‌روزرسانی شد')
            ->success()
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('ذخیره تغییرات')
                ->submit('save'),
        ];
    }
}