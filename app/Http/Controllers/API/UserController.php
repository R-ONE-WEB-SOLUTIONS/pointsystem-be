<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public $userService;

    public function __construct(UserService $userService) 
    {
        $this->userService = $userService;
    }
    
    public function index()
    {
        return $this->userService->viewAllUsers();
    }

    public function searchUsers(Request $request) 
    {
        return $this->userService->searchUsers($request);

    }

    
    public function store(Request $request)
    {
        return $this->userService->createUser($request);
    }

    
    public function show($id)
    {
        return $this->userService->viewUser($id);
    }

    
    public function update(Request $request, $id)
    {
        return $this->userService->editUser($request, $id);

    }

    
    public function destroy($id)
    {
        return $this->userService->deleteUser($id);
    }
}
