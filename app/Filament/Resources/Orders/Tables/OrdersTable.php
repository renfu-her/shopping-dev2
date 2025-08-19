<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_number')
                    ->label('Order Number')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->copyable(),
                
                TextColumn::make('member.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('member.email')
                    ->label('Customer Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                BadgeColumn::make('status')
                    ->label('Order Status')
                    ->colors([
                        'warning' => 'pending',
                        'info' => 'processing',
                        'primary' => 'shipped',
                        'success' => 'delivered',
                        'danger' => 'cancelled',
                    ]),
                
                BadgeColumn::make('payment_status')
                    ->label('Payment Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'paid',
                        'danger' => 'failed',
                        'gray' => 'refunded',
                    ]),
                
                TextColumn::make('total_amount')
                    ->label('Total Amount')
                    ->money('USD')
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('items_count')
                    ->label('Items')
                    ->counts('items')
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->label('Order Date')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
                
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Order Status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ]),
                
                SelectFilter::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        'pending' => 'Pending',
                        'paid' => 'Paid',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    \Filament\Actions\BulkAction::make('mark_processing')
                        ->label('Mark as Processing')
                        ->icon('heroicon-o-cog')
                        ->color('info')
                        ->action(function ($records) {
                            $records->each->update(['status' => 'processing']);
                        })
                        ->requiresConfirmation(),
                    
                    \Filament\Actions\BulkAction::make('mark_shipped')
                        ->label('Mark as Shipped')
                        ->icon('heroicon-o-truck')
                        ->color('primary')
                        ->action(function ($records) {
                            $records->each->update(['status' => 'shipped']);
                        })
                        ->requiresConfirmation(),
                    
                    \Filament\Actions\BulkAction::make('mark_delivered')
                        ->label('Mark as Delivered')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each->update(['status' => 'delivered']);
                        })
                        ->requiresConfirmation(),
                    
                    \Filament\Actions\BulkAction::make('mark_cancelled')
                        ->label('Mark as Cancelled')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($records) {
                            $records->each->update(['status' => 'cancelled']);
                        })
                        ->requiresConfirmation(),
                    
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
