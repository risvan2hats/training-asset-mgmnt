<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        // $this->middleware('auth');
    }

    /**
     * Display a listing of users.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $users = $this->userService->getAllUsers($request->user(), $request->all());
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $request)
    {
        $user = $this->userService->createUser($request->validated(), $request->user());
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $user = $this->userService->getUser($id, request()->user());
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $user = $this->userService->getUser($id, request()->user());
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \App\Http\Requests\UserRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $request, $id)
    {
        $user = $this->userService->updateUser($id, $request->validated(), $request->user());
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->userService->deleteUser($id, request()->user());
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Show user's assigned assets.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function assets($id)
    {
        $user   = $this->userService->getUser($id, request()->user());
        $assets = $this->userService->getUserAssets($id, request()->user());
        return view('users.assets', compact('user', 'assets'));
    }

    /**
     * Show user's activity history.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function history($id)
    {
        $user       = $this->userService->getUser($id, request()->user());
        $histories  = $this->userService->getUserHistories($id, request()->user());
        return view('users.history', compact('user', 'histories'));
    }
}