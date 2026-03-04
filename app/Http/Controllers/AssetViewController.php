<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Services\NotificationService;
use Illuminate\Http\Request;

use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use Illuminate\Support\Str;

class AssetViewController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $assets = Asset::paginate(20);
        return view('assets.index', compact('assets'));
    }

    public function create()
    {
        return view('assets.create');
    }

    public function store(StoreAssetRequest $request)
    {
        $asset = Asset::create($request->validated());
        
        // Send notification
        $this->notificationService->notifyAssetCreated($request->user(), $asset);

        return redirect()->route('assets.show', $asset)->with('success', 'Asset created');
    }

    public function show(Asset $asset)
    {
        return view('assets.show', compact('asset'));
    }

    public function edit(Asset $asset)
    {
        return view('assets.edit', compact('asset'));
    }

    public function update(UpdateAssetRequest $request, Asset $asset)
    {
        $asset->update($request->validated());
        return redirect()->route('assets.show', $asset)->with('success', 'Asset updated');
    }

    public function destroy(Asset $asset)
    {
        $asset->delete();
        return redirect()->route('assets.index')->with('success', 'Asset deleted');
    }
}
