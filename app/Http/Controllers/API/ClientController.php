<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\ClientService;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public $clientService;

    public function __construct(ClientService $clientService){
        $this->clientService = $clientService;
    }

    public function index()
    {
        return $this->clientService->viewClients();
    }


    public function store(Request $request)
    {
        return $this->clientService->createClient($request);
    }

    public function searchClients(Request $request)
    {
        return $this->clientService->searchClients($request);
    }


    public function show($id)
    {
        return $this->clientService->viewClient($id);
    }

    public function viewAllClients(Request $request)
    {
        return $this->clientService->viewAllClients($request);
    }


    public function update(Request $request, $id)
    {
        return $this->clientService->editClient($request, $id);
    }


    public function activateClient($id)
    {
        return $this->clientService->activateClient($id);

    }

    public function renewClient($id)
    {
        return $this->clientService->renewClient($id);

    }

    public function destroy($id)
    {
        return $this->clientService->deactivateClient($id);

    }
}
