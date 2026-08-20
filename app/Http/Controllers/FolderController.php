<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Party;
use App\Models\Folder;
use Illuminate\Http\Request;

class FolderController extends Controller
{
    public function index()
    {
        $party = Party::findOrFail(request('parties'));
        $folders = Folder::whereNull('parent_id')->where('party_id', request('parties'))->latest()->get();
        $files = File::where('party_id', request('parties'))->latest()->get();
        return view('pages.folders.index', compact('folders', 'party','files'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $existingFolder = Folder::where('name', $request->name)->where('parent_id', $request->parent_id)->first();

        if ($existingFolder) {
            return response()->json(__('A folder with the same name already exists in this directory.'), 400);
        }

        Folder::create($request->all());

        return response()->json([
            'message'   => __('Folder saved successfully'),
            'redirect'  => redirect()->back()->getTargetUrl(),
        ]);
    }


    public function show($id)
    {
        $party = Party::findOrFail(request('parties'));
        $folders = Folder::where('parent_id', $id)->latest()->get();
        $files = File::where('folder_id', $id)->latest()->get();
        return view('pages.folders.show', compact('folders', 'party','files'));
    }

    public function destroy($id)
    {
        $folder = Folder::findOrFail($id);
        $folder->delete();
        return response()->json([
            'message'   => __('Folder deleted successfully'),
            'redirect'  => redirect()->back()->getTargetUrl(),
        ]);
    }
}
