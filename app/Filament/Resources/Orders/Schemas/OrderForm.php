<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Order Information')
                    ->schema([
                        TextInput::make('order_number')
                            ->label('Order Number')
                            ->required()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true)
                            ->helperText('Unique order identifier'),
                        
                        Select::make('member_id')
                            ->label('Customer')
                            ->relationship('member', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        Select::make('status')
                            ->label('Order Status')
                            ->options([
                                'pending' => 'Pending',
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default('pending'),
                        
                        Select::make('payment_status')
                            ->label('Payment Status')
                            ->options([
                                'pending' => 'Pending',
                                'paid' => 'Paid',
                                'failed' => 'Failed',
                                'refunded' => 'Refunded',
                            ])
                            ->required()
                            ->default('pending'),
                        
                        Select::make('payment_method')
                            ->label('Payment Method')
                            ->options([
                                'credit_card' => 'Credit Card',
                                'bank_transfer' => 'Bank Transfer',
                                'paypal' => 'PayPal',
                                'cash_on_delivery' => 'Cash on Delivery',
                            ]),
                    ])->columns(2),

                Section::make('Financial Information')
                    ->schema([
                        TextInput::make('subtotal')
                            ->label('Subtotal')
                            ->numeric()
                            ->prefix('$')
                            ->required()
                            ->minValue(0),
                        
                        TextInput::make('tax_amount')
                            ->label('Tax Amount')
                            ->numeric()
                            ->prefix('$')
                            ->default(0)
                            ->minValue(0),
                        
                        TextInput::make('shipping_amount')
                            ->label('Shipping Amount')
                            ->numeric()
                            ->prefix('$')
                            ->default(0)
                            ->minValue(0),
                        
                        TextInput::make('total_amount')
                            ->label('Total Amount')
                            ->numeric()
                            ->prefix('$')
                            ->required()
                            ->minValue(0),
                    ])->columns(2),

                Section::make('Addresses')
                    ->schema([
                        Textarea::make('shipping_address')
                            ->label('Shipping Address')
                            ->required()
                            ->rows(3),
                        
                        Textarea::make('billing_address')
                            ->label('Billing Address')
                            ->required()
                            ->rows(3),
                    ])->columns(1),

                Section::make('Additional Information')
                    ->schema([
                        Textarea::make('notes')
                            ->label('Order Notes')
                            ->rows(3)
                            ->helperText('Internal notes about this order'),
                        
                        TextInput::make('ecpay_merchant_trade_no')
                            ->label('ECPay Merchant Trade No')
                            ->maxLength(50)
                            ->helperText('Payment gateway reference number'),
                    ])->columns(2),
            ]);
    }
}
