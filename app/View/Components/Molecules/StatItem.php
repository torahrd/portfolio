<?php
// app/View/Components/Molecules/StatItem.php

namespace App\View\Components\Molecules;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatItem extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $title,
        public int|string $value,
        public ?string $icon = null,
        public ?string $href = null,
        public string $variant = 'default'
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.molecules.stat-item');
    }
}
