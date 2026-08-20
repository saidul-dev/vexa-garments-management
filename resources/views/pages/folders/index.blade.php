@extends('layouts.master')

@section('title')
    {{__('Folders & Files')}}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="table-header">
                <h4>{{__('Folders & Files')}} ({{ $party->name }})</h4>
                <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                    <a href="#add-new-folder" class="btn add-order-btn rounded-2 me-1" data-bs-toggle="modal">{{__('+ Make Folder')}}</a>
                    <a href="#upload-file" class="btn add-order-btn rounded-2 me-1" data-bs-toggle="modal">{{__('Upload File')}}</a>
                </div>
            </div>

            @if ($folders->count() > 0 || $files->count() > 0)
                @if ($folders->count() > 0)
                    <div class="responsive-table party-list-folder-table">
                        <h5 class="mb-3">Folders({{ $folders->count() }})</h5>
                        <table class="table table-bordered folders-files table-hover" id="erp-table">
                            <tbody id="party-ledger-data">
                            @foreach($folders as $folder)
                                <tr>
                                    <td class="text-start">
                                        <a href="{{ route('folders.show', [$folder->id, 'parties' => request('parties'), 'parent_id' => $folder->id]) }}" class="d-block">
                                            <i class="far fa-folder custom-warning-color pe-2"></i>
                                            {{ $folder->name }}
                                        </a>
                                    </td>
                                    <td class="width-50">
                                        <a href="{{ route('folders.destroy', $folder->id) }}" class="confirm-action" data-method="DELETE">
                                            <i class="fal fa-trash-alt text-danger"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
                @if ($files->count() > 0)
                    <div class="responsive-table party-list-folder-table">
                            <h5 class="mb-3">Files({{ $files->count() }})</h5>
                            <table class="table table-bordered folders-files table-hover" id="erp-table">
                                <tbody id="party-ledger-data">
                                @foreach($files as $file)
                                    <tr>
                                        <td class="text-start">
                                            <a href="{{ route('files-uploads.show', $file->id) }}" class="d-block">
                                                <i class="{{ getIconForFile( pathinfo($file->name, PATHINFO_EXTENSION)) }} text-primary pe-2"></i>
                                                {{ $file->name }}
                                            </a>
                                        </td>
                                        <td class="width-50">
                                            <a href="{{ route('files-uploads.destroy', $file->id) }}" class="confirm-action" data-method="DELETE">
                                                <i class="fal fa-trash-alt text-danger"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                @endif
            @else
                <h6 class="text-center text-secondary mt-lg-4">{{ __('Empty folder & file') }}</h6>
            @endif
        </div>
    </div>
    @include('pages.folders.create')
@endsection
