<?php

namespace App\Http\Controllers;

use App\Http\Requests\LocationRequest;
use App\Services\LocationService;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    protected $locationService;

    public function __construct(LocationService $locationService){
        $this->locationService = $locationService;
    }

    public function index(Request $request)
    {
        $locations = $this->locationService->getAllLocations($request->user(), $request->all());
        return view('locations.index', compact('locations'));
    }

    public function create()
    {
        return view('locations.create');
    }

    public function store(LocationRequest $request)
    {
        $this->locationService->createLocation($request->validated(), $request->user());
        return redirect()->route('locations.index')->with('success', 'Location created successfully.');
    }

    public function show($id)
    {
        $location = $this->locationService->getLocation($id, request()->user());
        return view('locations.show', compact('location'));
    }

    public function edit($id)
    {
        $location = $this->locationService->getLocation($id, request()->user());
        return view('locations.edit', compact('location'));
    }

    public function update(LocationRequest $request, $id)
    {
        $this->locationService->updateLocation($id, $request->validated(), $request->user());
        return redirect()->route('locations.index')->with('success', 'Location updated successfully.');
    }

    public function destroy($id)
    {
        $this->locationService->deleteLocation($id, request()->user());
        return redirect()->route('locations.index')->with('success', 'Location deleted successfully.');
    }
}