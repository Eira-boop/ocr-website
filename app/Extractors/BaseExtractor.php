<?php

namespace App\Extractors;

abstract class BaseExtractor
{
    abstract public function extract(string $text): array;

    protected function clean(string $text): string
    {
        $text = trim($text);
        $text = preg_replace('/\s+/', ' ', $text);

        return $text;
    }

    protected function find(string $pattern, string $text): ?string
    {
        if (preg_match($pattern, $text, $match)) {
            return trim($match[1]);
        }

        return null;
    }

    protected function formatDate(?string $date): ?string
    {
        if (!$date) {
            return null;
        }

        $date = str_replace(['.', '-'], '/', $date);

        $time = strtotime($date);

        if (!$time) {
            return null;
        }

        return date('Y-m-d', $time);
    }
}