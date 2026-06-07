<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class StatusBadge extends Component
{
    public string $label;

    public string $classes;

    public function __construct(public string $status)
    {
        $config = [
            'working' => ['Working', 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300'],
            'faulty' => ['Faulty', 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300'],
            'disposed' => ['Disposed', 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'],
            'under_maintenance' => ['Under Maintenance', 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300'],
        ];

        [$this->label, $this->classes] = $config[$status] ?? $config['working'];
    }

    public function render(): View|Closure|string
    {
        return view('components.status-badge');
    }
}
