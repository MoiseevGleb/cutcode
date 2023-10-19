<?php

namespace App\Faker;

use Faker\Provider\Base;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FakerImageProvider extends Base
{
    public function fakeImage(string $from, string $to): string
    {
        if (! Storage::directoryExists($to)) {
            Storage::makeDirectory($to);
        }

        $fromImages = glob($from . '\*.jpg');
        $randomFile = $fromImages[rand(0, count($fromImages) - 1)];

        $newName = storage_path("app/public/{$to}/" . Str::random(10) . '.jpg');

        copy($randomFile, $newName);

        return basename($newName);
    }
}
