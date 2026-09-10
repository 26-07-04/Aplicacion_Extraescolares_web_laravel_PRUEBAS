<?php

namespace App\Support;

use Symfony\Component\Process\Process;

class MembretePdfPreview
{
    public static function resolve(string $path): ?string
    {
        if (strtolower(pathinfo($path, PATHINFO_EXTENSION)) !== 'pdf') {
            return $path;
        }

        $previewPath = preg_replace('/\.pdf$/i', '.png', $path);
        if (!is_string($previewPath)) {
            return null;
        }

        if (!file_exists($previewPath)) {
            $process = new Process([
                'pdftoppm',
                '-png',
                '-f',
                '1',
                '-singlefile',
                $path,
                pathinfo($previewPath, PATHINFO_DIRNAME) . DIRECTORY_SEPARATOR . pathinfo($previewPath, PATHINFO_FILENAME),
            ]);
            $process->setTimeout(60);
            try {
                $process->run();
            } catch (\Throwable $e) {
                return null;
            }
        }

        return file_exists($previewPath) ? $previewPath : null;
    }
}
