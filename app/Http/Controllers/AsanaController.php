<?php

namespace App\Http\Controllers;

use App\Asana\Client;
use Illuminate\Http\Request;

class AsanaController extends Controller
{
    public Client $asana;

    public function __construct()
    {
        $this->asana = new Client();
    }

    public function installWebhook()
    {
        $projects = $this->asana->getProjects();
        dd($projects);
        return;
        // dd($projects);
        // $result = $this->asana->webhooks->createWebhook([
        //     'resource' => ,
        //     'target' => env('ASANA_WEBHOOK_URL'),
        // ], array('opt_pretty' => 'true'));

        // return $result;
    }

    public function webhook() {}
}
