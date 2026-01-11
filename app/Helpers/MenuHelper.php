<?php

if (!function_exists('isMenuActive')) {
    function isMenuActive($route, $exact = false) {
        if ($exact) {
            return request()->is($route) ? 'active' : '';
        }
        return request()->is($route . '*') ? 'active' : '';
    }
}

if (!function_exists('isMenuOpen')) {
    function isMenuOpen($route) {
        return request()->is($route . '*') ? 'open' : '';
    }
}