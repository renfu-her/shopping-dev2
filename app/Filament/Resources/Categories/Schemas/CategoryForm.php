<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->schema([
                        TextInput::make('name')
                            ->label('Category Name')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, $set) => $set('slug', Str::slug($state))),
                        
                        TextInput::make('slug')
                            ->label('URL Slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Auto-generated from name, but can be customized'),
                        
                        Select::make('parent_id')
                            ->label('Parent Category')
                            ->relationship('parent', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Select parent category (optional)')
                            ->helperText('Leave empty for root categories'),
                        
                        Textarea::make('description')
                            ->label('Description')
                            ->maxLength(1000)
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        TextInput::make('sort_order')
                            ->label('Sort Order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first'),
                    ])->columns(2),

                Section::make('SEO Settings')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta Title')
                            ->maxLength(255)
                            ->helperText('Title for search engines'),
                        
                        Textarea::make('meta_description')
                            ->label('Meta Description')
                            ->maxLength(500)
                            ->rows(3)
                            ->helperText('Description for search engines'),
                    ])->collapsible(),

                Section::make('Status')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Active Status')
                            ->default(true)
                            ->helperText('Inactive categories will not be visible to customers'),
                    ])->columns(1),
            ]);
    }
}
