<?php

namespace App\Filament\Resources\Members\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255),
                        
                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        
                        TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->maxLength(20),
                        
                        Textarea::make('address')
                            ->label('Address')
                            ->maxLength(1000)
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Account Settings')
                    ->schema([
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->minLength(8)
                            ->confirmed()
                            ->helperText('Leave blank to keep current password')
                            ->dehydrated(fn ($state) => filled($state)),
                        
                        TextInput::make('password_confirmation')
                            ->label('Confirm Password')
                            ->password()
                            ->minLength(8)
                            ->dehydrated(false),
                        
                        Toggle::make('email_verified_at')
                            ->label('Email Verified')
                            ->helperText('Mark if email has been verified')
                            ->dehydrated(false),
                    ])->columns(2),
            ]);
    }
}
