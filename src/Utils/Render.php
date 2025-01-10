<?php

namespace HealthTracker\Utils;

class Render {
    public static function render($view, $data = []) {
        extract($data);
        require __DIR__ . "/../../Views/{$view}.php";
    }

    public static function breadcrumb($currentPage) {
        return '
        <nav class="flex mb-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="/albi/dashboard" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                        <i class="fas fa-home mr-2"></i>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="text-sm font-medium text-gray-500">'.htmlspecialchars($currentPage).'</span>
                    </div>
                </li>
            </ol>
        </nav>';
    }
} 