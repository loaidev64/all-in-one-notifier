<?php

namespace App\Asana\DTOs;

readonly class WorkspaceDTO
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        public ?string $id,
        public string $name,
    ) {
        //
    }
}
