<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssetRequest;
use App\Http\Requests\MoveAssetRequest;
use App\Services\AssetService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class AssetController extends Controller
{
    protected $assetService;
    protected $userService;

    public function __construct(AssetService $assetService,UserService $userService){
        $this->assetService = $assetService;
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $assets = $this->assetService->getAllAssets($request->user(), $request->all(),$request->boolean('skip_pagination'));
        
        return view('assets.index', [
            'assets' => $assets,
            'paginated' => $assets instanceof LengthAwarePaginator
        ]);
    }

    public function create()
    {
        return view('assets.create', [
            'asset' => $this->assetService->getAllHistories(Auth::user()),
            'locations' => $this->assetService->getLocationsForUser(Auth::user()),
            'users' => $this->userService->getAllUsers(Auth::user())
        ]);
    }

    public function store(AssetRequest $request)
    {
        $asset = $this->assetService->createAsset($request->validated(), $request->user());
        return redirect()->route('assets.show', $asset->id)->with('success', 'Asset created successfully.');
    }

    public function show($id)
    {
        $asset = $this->assetService->getAssetWithHistories($id, request()->user());
        return view('assets.show', compact('asset'));
    }

    public function edit($id)
    {
        return view('assets.edit', [
            'asset'     => $this->assetService->getAssetWithHistories($id, Auth::user()),
            'locations' => $this->assetService->getLocationsForUser(Auth::user()),
            'users'     => $this->userService->getAllUsers(Auth::user())
        ]);
    }

    public function update(AssetRequest $request, $id)
    {
        $asset = $this->assetService->updateAsset($id, $request->validated(), $request->user());
        return redirect()->route('assets.show', $asset->id)->with('success', 'Asset updated successfully.');
    }

    public function destroy($id)
    {
        $this->assetService->deleteAsset($id, request()->user());
        return redirect()->route('assets.index')->with('success', 'Asset deleted successfully.');
    }

    public function moveForm($id)
    {
        return view('assets.move', [
            'asset'     => $this->assetService->getAssetWithHistories($id, Auth::user()),
            'locations' => $this->assetService->getLocationsForUser(Auth::user())
        ]);
    }

    public function move(MoveAssetRequest $request, $id)
    {
        $this->assetService->moveAsset($id, $request, $request->user());
        return redirect()->route('assets.show', $id)->with('success', 'Asset moved successfully.');
    }
}