<?php

namespace App\Asana;

use App\Asana\APIs\ProjectApi;
use App\Asana\APIs\WorkspaceApi;
use Illuminate\Support\Facades\Http;

class Client
{
    use ProjectApi;
    use WorkspaceApi;

    private $baseUrl = 'https://app.asana.com/api/1.0/';

    private $http;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->http = Http::withHeader('Authorization', 'Bearer ' . config('asana.token'));
    }
}
