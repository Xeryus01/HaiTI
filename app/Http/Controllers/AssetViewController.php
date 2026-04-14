<?php

namespace App\Http\Controllers;

use App\Imports\AssetsImport;
use App\Imports\AssetsTemplateExport;
use App\Imports\AssetsExport;
use App\Models\Asset;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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

    public function downloadTemplate()
    {
        abort_unless(auth()->user()->can('manage assets'), 403);

        $filename = 'template-aset.xlsx';

        // Delivery langsung, tanpa ketergantungan file system lokal
        return Excel::download(new AssetsTemplateExport(), $filename, \Maatwebsite\Excel\Excel::XLSX);
    }

    public function import(Request $request)
    {
        abort_unless(auth()->user()->can('manage assets'), 403);

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('file');

        try {
            Excel::import(new AssetsImport(), $file);
        } catch (\Exception $ex) {
            return redirect()->route('assets.index')->with('error', 'Import gagal: ' . $ex->getMessage());
        }

        return redirect()->route('assets.index')->with('success', 'Data aset berhasil diimpor dari Excel.');
    }

    public function export(Request $request)
    {
        abort_unless(auth()->user()->can('manage assets'), 403);

        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'period' => 'nullable|string',
        ]);

        $startDate = null;
        $endDate = null;
        $year = now()->year;

        // Jika ada periode preset yang dipilih
        if ($request->period) {
            switch ($request->period) {
                case 'q1':
                    $startDate = "{$year}-01-01";
                    $endDate = "{$year}-03-31";
                    break;
                case 'q2':
                    $startDate = "{$year}-04-01";
                    $endDate = "{$year}-06-30";
                    break;
                case 'q3':
                    $startDate = "{$year}-07-01";
                    $endDate = "{$year}-09-30";
                    break;
                case 'q4':
                    $startDate = "{$year}-10-01";
                    $endDate = "{$year}-12-31";
                    break;
                case 'year':
                    $startDate = "{$year}-01-01";
                    $endDate = "{$year}-12-31";
                    break;
            }
        } else {
            // Gunakan tanggal yang dipilih user
            $startDate = $request->start_date;
            $endDate = $request->end_date;
        }

        $filename = 'aset-' . ($startDate ? \Carbon\Carbon::parse($startDate)->format('Y-m-d') : 'all') .
                   '-to-' . ($endDate ? \Carbon\Carbon::parse($endDate)->format('Y-m-d') : 'all') . '.xlsx';

        return Excel::download(new AssetsExport($startDate, $endDate), $filename, \Maatwebsite\Excel\Excel::XLSX);
    }
}
