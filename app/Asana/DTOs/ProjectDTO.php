<?php

namespace App\Asana\DTOs;

readonly class ProjectDTO
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
