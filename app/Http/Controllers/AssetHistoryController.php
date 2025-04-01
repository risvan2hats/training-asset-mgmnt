<?php

namespace App\Http\Controllers;

use App\Services\AssetService;
use Illuminate\Support\Facades\Auth;
use App\Services\AssetHistoryService;

class AssetHistoryController extends Controller
{
    protected $assetHistoryService;
    protected $assetService;

    public function __construct(AssetHistoryService $assetHistoryService,AssetService $assetService) {
        $this->assetHistoryService = $assetHistoryService;
        $this->assetService = $assetService;
    }

    public function assetHistories($assetId)
    {
        $asset = $this->assetService->getAssetWithHistories($assetId, request()->user());
        $histories = $this->assetHistoryService->getAssetHistories($assetId, request()->user(), request()->only(['date_from', 'date_to']));
        
        return view('assets.histories', compact('asset', 'histories'));
    }

    public function allHistories()
    {
        $assets = $this->assetService->getAllAssets(Auth::user(),[], false);
        $histories = $this->assetHistoryService->getAllHistories(request()->user(),request()->only(['asset_ids', 'date_from', 'date_to']));
        
        return view('asset-histories.index', compact('assets','histories'));
    }
}