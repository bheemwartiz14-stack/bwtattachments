<?php
declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'token' => $this->resource['token'],
            'name' => $this->resource['name'],
            'size' => $this->resource['size'],
            'url' => $this->resource['url'],
            'mime_type' => $this->resource['mime_type'] ?? null,
            'extension' => $this->resource['extension'] ?? null,
        ];
    }
}
