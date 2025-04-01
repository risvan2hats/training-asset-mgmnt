<?php

namespace App\Http\Controllers;

use App\Services\AssetService;
use App\Services\LocationService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $assetService;
    protected $locationService;
    protected $userService;

    public function __construct(AssetService $assetService,LocationService $locationService,UserService $userService) {
        $this->userService      = $userService;
        $this->assetService     = $assetService;
        $this->locationService  = $locationService;
    }

    public function index()
    {
        $user = Auth::user();
        
        return view('dashboard', [
            'users'             => $this->userService->getAllUsers($user, ['limit' => 5]),
            'assets'            => $this->assetService->getAllAssets($user, ['limit' => 5]),
            'locations'         => $this->locationService->getAllLocations($user, ['limit' => 5]),
            'recentActivities'  => $this->assetService->getRecentActivities($user, 5)
        ]);
    }
}