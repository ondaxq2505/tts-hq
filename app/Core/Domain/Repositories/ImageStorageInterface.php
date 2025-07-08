<?php
namespace App\Core\Domain\Repositories;

use Illuminate\Http\UploadedFile;

interface ImageStorageInterface
{
    public function store(UploadedFile $file, string $path): string;
}
