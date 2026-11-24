<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Helper upload file.
 * Disk diatur lewat env UPLOAD_DISK:
 *  - lokal  : "public" (storage/app/public, jalankan `php artisan storage:link`)
 *  - lainnya: "s3" bila memakai object storage (isi AWS_* di env)
 */
class Files
{
    public static function disk(): string
    {
        return config('filesystems.uploads', 'public');
    }

    /** Simpan file ke folder tertentu, kembalikan path relatif. */
    public static function store(?UploadedFile $file, string $folder): ?string
    {
        if (! $file) {
            return null;
        }

        return $file->store($folder, ['disk' => self::disk(), 'visibility' => 'public']);
    }

    /** URL publik dari path. Path yang diawali "mivent_assets/" dianggap aset statis di /public. */
    public static function url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }
        if (str_starts_with($path, 'mivent_assets/') || str_starts_with($path, 'http')) {
            return str_starts_with($path, 'http') ? $path : asset($path);
        }

        return Storage::disk(self::disk())->url($path);
    }

    public static function delete(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'mivent_assets/')) {
            Storage::disk(self::disk())->delete($path);
        }
    }
}
