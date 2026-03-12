<?php

namespace App\Support;

class ContentFormatter
{
    public static function linkify(string $text): string
    {
        $escaped = e($text);

        $escaped = preg_replace_callback('/@([A-Za-z0-9_]+)/', function ($matches) {
            $username = $matches[1];
            $url = url('/u/' . $username);
            return '<a href="' . $url . '" class="text-orange-400 hover:underline">@' . $username . '</a>';
        }, $escaped);

        $escaped = preg_replace_callback('/#([A-Za-z0-9_]+)/', function ($matches) {
            $tag = $matches[1];
            $url = url('/tags/' . $tag);
            return '<a href="' . $url . '" class="text-green-400 hover:underline">#' . $tag . '</a>';
        }, $escaped);

        return $escaped;
    }
}
