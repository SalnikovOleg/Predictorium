<?php

namespace App\Services\Web;

use VanOns\FilamentNavigation\Models\Navigation;

class NavigationService
{
    public function getMainMenu(): ?Navigation
    {
       return Navigation::where('handle', 'main_menu')->first();
    }
}
