<?php

namespace Support\Testing;

use Faker\Provider\Base;
use Illuminate\Support\Facades\Storage;

class FakerImageProvider extends Base
{
    public function fixturesImage(string $from, string $to): string
    {
        $storage = Storage::disk('images');

        if ($storage->directoryMissing($to)) {
            $storage->makeDirectory($to);
        }

        $file = $this->generator->file(
            base_path("tests/Fixtures/images/$from"),
            $storage->path($to)
        );

        return basename($file);
    }
}
