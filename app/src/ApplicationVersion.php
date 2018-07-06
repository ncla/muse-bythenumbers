<?php

/**
 * Class ApplicationVersion
 * https://stackoverflow.com/a/33986403/757587
 */

namespace App\Src;

class ApplicationVersion
{
    const MAJOR = 1;
    const MINOR = 2;
    const PATCH = 3;

    public static function get()
    {
        $commitHash = trim(exec('git log --pretty="%h" -n1 HEAD'));

        $commitDate = new \DateTime(trim(exec('git log -n1 --pretty=%ci HEAD')));
        $commitDate->setTimezone(new \DateTimeZone('UTC'));

        return sprintf('%s (%s)', $commitHash, $commitDate->format('Y-m-d H:m:s'));
    }
}