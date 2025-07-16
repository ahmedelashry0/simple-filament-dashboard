<?php

namespace App\Filament\Resources\PostResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Posts', \App\Models\Post::count())
                ->color('primary')
                ->icon('heroicon-o-document-text'),
            Stat::make('Total Categories', \App\Models\Category::count())
                ->color('success')
                ->icon('heroicon-o-folder'),
            Stat::make('Total Tags', \App\Models\Tag::count())
                ->color('warning')
                ->icon('heroicon-o-tag'),
        ];
    }
}
