<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use App\Models\Member;
use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Products', Product::count())
                ->description('Active products in store')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('success'),
            
            Stat::make('Total Categories', Category::count())
                ->description('Product categories')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('info'),
            
            Stat::make('Total Members', Member::count())
                ->description('Registered customers')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),
            
            Stat::make('Total Orders', Order::count())
                ->description('Orders this month')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('warning'),
            
            Stat::make('Active Products', Product::where('is_active', true)->count())
                ->description('Currently available')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            
            Stat::make('Featured Products', Product::where('is_featured', true)->count())
                ->description('Featured on homepage')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
            
            Stat::make('Pending Orders', Order::where('status', 'pending')->count())
                ->description('Awaiting processing')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            
            Stat::make('Total Revenue', '$' . number_format(Order::where('payment_status', 'paid')->sum('total_amount'), 2))
                ->description('From paid orders')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('success'),
        ];
    }
}
