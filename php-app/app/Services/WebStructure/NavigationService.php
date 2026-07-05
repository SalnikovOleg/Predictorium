<?php

namespace App\Services\WebStructure;

use VanOns\FilamentNavigation\Models\Navigation;

class NavigationService
{
    public function getMainMenu(): ?Navigation
    {
       return Navigation::where('handle', 'main_menu')->first();
    }
}
