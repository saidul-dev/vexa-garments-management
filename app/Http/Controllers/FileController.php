<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Helpers\HasUploader;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    use HasUploader;

    public function index()
    {
        return back();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|file|max:2048',
        ]);

        $existingFile = File::where('name', $this->uploadWithFileName($request, 'name'))
            ->where('folder_id', $request->folder_id)
            ->first();

        if ($existingFile) {
            return response()->json(__('A file with the same name already exists in this directory.'), 400);
        }

        File::create([
            'party_id' => request('party_id'),
            'folder_id' => request('folder_id'),
            'name' => $this->uploadWithFileName($request, 'name'),
        ]);

        return response()->json([
            'message'   => __('File saved successfully'),
            'redirect'  => redirect()->back()->getTargetUrl(),
        ]);
    }

    public function show($id)
    {
        $file = File::findOrFail($id)->name;
        if (Storage::disk(config('filesystems.default'))->exists($file)){
            return Storage::disk(config('filesystems.default'))->download($file);
        }else{
            abort(404, __("File Not Found"));
        }
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $file = File::findOrFail($id);
        if (file_exists($file->image)) {
            Storage::delete($file->image);
        }
        $file->delete();
        return response()->json([
            'message'   => __('File deleted successfully'),
            'redirect'  => redirect()->back()->getTargetUrl(),
        ]);
    }
}
