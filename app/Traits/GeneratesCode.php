<?php

namespace App\Traits;

trait GeneratesCode
{
    public static function generateUniqueCode($prefix = '', $length = 8)
    {
        do {
            $code = $prefix . strtoupper(uniqid());
            if (strlen($code) > $length + strlen($prefix)) {
                $code = $prefix . substr($code, strlen($prefix), $length);
            }
        } while (static::where(static::getCodeColumn(), $code)->exists());

        return $code;
    }

    protected static function getCodeColumn()
    {
        return 'code'; // Override in model if different
    }
}