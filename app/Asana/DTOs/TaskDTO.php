<?php

namespace App\Asana\DTOs;

class TaskDTO
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
