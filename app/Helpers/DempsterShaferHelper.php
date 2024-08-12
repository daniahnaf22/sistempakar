<?php

// app/Helpers/DempsterShaferHelper.php

namespace App\Helpers;

class DempsterShaferHelper
{
    public static function calculateK($m1, $m2)
    {
        return 1 - min($m1, $m2);
    }

    public static function calculateBelief($m, $K)
    {
        return $m / (1 - $K);
    }

    public static function calculatePlausibility($m, $K)
    {
        return (1 - min(1, $m + $K)) / (1 - $K);
    }
}
