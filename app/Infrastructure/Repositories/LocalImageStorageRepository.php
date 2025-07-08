<?php
namespace App\Infrastructure\Repositories;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Core\Domain\Repositories\ImageStorageInterface;

class LocalImageStorageRepository implements ImageStorageInterface
{
    public function store(UploadedFile $file, string $path): string
    {
        return $file->store($path, 'public');
    }
}
