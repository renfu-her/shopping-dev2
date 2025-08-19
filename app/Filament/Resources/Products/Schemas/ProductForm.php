<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->schema([
                        TextInput::make('name')
                            ->label('Product Name')
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
                        
                        Select::make('category_id')
                            ->label('Category')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        TextInput::make('sku')
                            ->label('SKU')
                            ->maxLength(100)
                            ->unique(ignoreRecord: true)
                            ->helperText('Stock Keeping Unit - unique product identifier'),
                        
                        Textarea::make('short_description')
                            ->label('Short Description')
                            ->maxLength(500)
                            ->rows(3)
                            ->helperText('Brief description for product listings'),
                    ])->columns(2)->columnSpanFull(),

                Section::make('Pricing')
                    ->schema([
                        TextInput::make('price')
                            ->label('Regular Price')
                            ->numeric()
                            ->prefix('$')
                            ->required()
                            ->minValue(0),
                        
                        TextInput::make('sale_price')
                            ->label('Sale Price')
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->helperText('Leave empty if no sale price'),
                        
                        TextInput::make('cost_price')
                            ->label('Cost Price')
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->helperText('Internal cost for profit calculations'),
                    ])->columns(3)->columnSpanFull(),

                Section::make('Inventory')
                    ->schema([
                        TextInput::make('stock_quantity')
                            ->label('Stock Quantity')
                            ->numeric()
                            ->default(0)
                            ->minValue(0),
                        
                        TextInput::make('weight')
                            ->label('Weight (kg)')
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01),
                        
                        TextInput::make('dimensions')
                            ->label('Dimensions')
                            ->maxLength(100)
                            ->helperText('e.g., 10x5x2 cm'),
                    ])->columns(3)->columnSpanFull(),

                Section::make('Content')
                    ->schema([
                        RichEditor::make('description')
                            ->label('Description')
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'link',
                                'bulletList',
                                'orderedList',
                                'h2',
                                'h3',
                                'blockquote',
                                'codeBlock',
                            ]),
                    ])->collapsible()->columnSpanFull(),

                Section::make('SEO')
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
                    ])->collapsible()->columnSpanFull(),

                Section::make('Settings')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Active Status')
                            ->default(true)
                            ->helperText('Inactive products will not be visible to customers'),
                        
                        Toggle::make('is_featured')
                            ->label('Featured Product')
                            ->default(false)
                            ->helperText('Featured products appear on homepage'),
                    ])->columns(2)->columnSpanFull(),
            ]);
    }
}
