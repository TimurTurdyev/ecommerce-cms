<?php

if (!function_exists('getDefaultIcon')) {
    function getDefaultIconId(int $userId): int
    {
        $result = ($userId % 9);
        return $result === 0 ? 9 : $result;
    }
}
