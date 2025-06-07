<?php

namespace App\Filament\Support\Pages;

use Filament\Pages\Page;

class LiveSupportChat extends Page
{
    protected static ?string $navigationIcon = 'entypo-chat';
    protected static ?string $navigationGroup = 'Supports';
    protected static string $view = 'filament.pages.live-support-chat';
}
