<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAssetRequest;
use App\Http\Requests\UpdateAssetRequest;
use App\Models\Asset;
use App\Models\Log;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $q = Asset::query();

        // everyone can view assets, but maybe restrict fields or count
        return $q->paginate(20);
    }

    public function show(Asset $asset)
    {
        return $asset;
    }

    public function store(StoreAssetRequest $request)
    {
        $asset = Asset::create($request->validated());

        Log::create([
            'actor_id' => $request->user()->id,
            'entity_type' => 'Asset',
            'entity_id' => $asset->id,
            'action' => 'CREATED',
            'meta' => $asset->toArray(),
        ]);

        return response()->json($asset, 201);
    }

    public function update(UpdateAssetRequest $request, Asset $asset)
    {
        $asset->update($request->validated());

        Log::create([
            'actor_id' => $request->user()->id,
            'entity_type' => 'Asset',
            'entity_id' => $asset->id,
            'action' => 'UPDATED',
            'meta' => $asset->getChanges(),
        ]);

        return response()->json($asset);
    }

    public function destroy(Request $request, Asset $asset)
    {
        $asset->delete();
        Log::create([
            'actor_id' => $request->user()->id,
            'entity_type' => 'Asset',
            'entity_id' => $asset->id,
            'action' => 'DELETED',
            'meta' => [],
        ]);
        return response()->noContent();
    }
}
