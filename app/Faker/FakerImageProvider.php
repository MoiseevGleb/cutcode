<?php

namespace App\Faker;

use Faker\Provider\Base;
use Illuminate\Support\Facades\Storage;

class FakerImageProvider extends Base
{
    public function fixturesImage(string $from, string $to): string
    {
        if (Storage::directoryMissing($to)) {
            Storage::makeDirectory($to);
        }

        $file = $this->generator->file(base_path("tests/Fixtures/images/$from"), Storage::path($to));

        return basename($file);
    }
}
