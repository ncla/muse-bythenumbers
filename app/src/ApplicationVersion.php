<?php

/**
 * Class ApplicationVersion
 * https://stackoverflow.com/a/33986403/757587
 */

namespace App\Src;

class ApplicationVersion
{
    public static function get()
    {
        $commitHash = trim(exec('git log --pretty="%h" -n1 HEAD'));
        $commitDate = new \DateTime(trim(exec('git log --pretty="%ci" -n1 HEAD')));

        return sprintf('%s (%s)', $commitHash, $commitDate->format('Y-m-d H:i:s'));
    }
}