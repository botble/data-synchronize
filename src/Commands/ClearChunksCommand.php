<?php

namespace Botble\DataSynchronize\Commands;

use Botble\Base\Facades\BaseHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Attribute\AsCommand;

use function Laravel\Prompts\info;

#[AsCommand('cms:data-synchronize:clear-chunks', 'Remove all expired chunks')]
class ClearChunksCommand extends Command
{
    public function handle(): int
    {
        $storageDisk = Storage::disk(config('packages.data-synchronize.data-synchronize.storage.disk'));

        $storagePath = config('packages.data-synchronize.data-synchronize.storage.path');

        $expiredFileCount = count($storageDisk->allFiles($storagePath));

        if ($expiredFileCount) {
            $expiredFileSize = BaseHelper::humanFilesize(
                $this->calculateChunkSize($storageDisk->path($storagePath))
            );

            $storageDisk->deleteDirectory($storagePath);

            info("✔ $expiredFileCount expired chunk files removed");
            info("✔ $expiredFileSize disk cleared");
        } else {
            info('✔ No expired chunk files found!');
        }

        return self::SUCCESS;
    }

    protected function calculateChunkSize(string $directory): int
    {
        $size = 0;

        foreach (File::glob(rtrim($directory, '/') . '/*', GLOB_NOSORT) as $each) {
            $size += File::isFile($each) ? File::size($each) : self::calculateAppSize($each);
        }

        return $size;
    }
}
