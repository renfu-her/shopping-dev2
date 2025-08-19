<?php

namespace App\Filament\Resources\Members\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class MembersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('email')
                    ->label('Email Address')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                
                TextColumn::make('phone')
                    ->label('Phone Number')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('orders_count')
                    ->label('Orders')
                    ->counts('orders')
                    ->sortable(),
                
                IconColumn::make('email_verified_at')
                    ->label('Email Verified')
                    ->boolean()
                    ->sortable()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                TextColumn::make('created_at')
                    ->label('Registered')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
                
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('email_verified_at')
                    ->label('Email Verification')
                    ->placeholder('All Members')
                    ->trueLabel('Verified Members')
                    ->falseLabel('Unverified Members'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    \Filament\Actions\BulkAction::make('verify_email')
                        ->label('Verify Email')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each->update(['email_verified_at' => now()]);
                        })
                        ->requiresConfirmation(),
                    
                    \Filament\Actions\BulkAction::make('unverify_email')
                        ->label('Unverify Email')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($records) {
                            $records->each->update(['email_verified_at' => null]);
                        })
                        ->requiresConfirmation(),
                    
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
