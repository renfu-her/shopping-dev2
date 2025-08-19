<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Category Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('parent.name')
                    ->label('Parent Category')
                    ->sortable()
                    ->placeholder('Root Category'),
                
                TextColumn::make('products_count')
                    ->label('Products')
                    ->counts('products')
                    ->sortable(),
                
                TextColumn::make('sort_order')
                    ->label('Sort Order')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->sortable()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('parent_id')
                    ->label('Parent Category')
                    ->relationship('parent', 'name')
                    ->placeholder('All Categories'),
                
                TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All Categories')
                    ->trueLabel('Active Categories')
                    ->falseLabel('Inactive Categories'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    \Filament\Actions\BulkAction::make('activate')
                        ->label('Activate Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each->update(['is_active' => true]);
                        })
                        ->requiresConfirmation(),
                    
                    \Filament\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($records) {
                            $records->each->update(['is_active' => false]);
                        })
                        ->requiresConfirmation(),
                    
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc')
            ->defaultSort('name', 'asc');
    }
}
