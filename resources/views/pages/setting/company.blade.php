@extends('layouts.master')

@section('title')
    {{__('Company Settings') }}
@endsection

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-header">
                        <h4>{{__('Company Settings')}}</h4>
                    </div>
                    <div class="order-form-section">
                        <form action="{{ route('settings.update', $company->id) }}" method="post" enctype="multipart/form-data" class="ajaxform_instant_reload">
                            @csrf
                            @method('put')
                            <div class="add-suplier-modal-wrapper d-block">
                                <div class="row">
                                    <div class="col-lg-12 mt-2">
                                        <label>{{__('Company Name')}}</label>
                                        <input type="text" name="name" value="{{ $company->value['name'] ?? '' }}"  required class="form-control" placeholder="Company Name">
                                    </div>
                                    <div class="col-lg-6 mt-2">
                                        <label>{{__('Company Email')}}</label>
                                        <input type="text" name="email" value="{{ $company->value['email'] ?? '' }}"  required class="form-control" placeholder="Company Name">
                                    </div>
                                    <div class="col-lg-6 mt-2">
                                        <label>{{__('Remarks')}}</label>
                                        <input type="text" name="remarks" value="{{ $company->value['remarks'] ?? '' }}"  required class="form-control" placeholder="Remarks">
                                    </div>
                                    <div class="col-lg-6 mt-2">
                                        <label>{{__('Address')}}</label>
                                        <input type="text" name="address" value="{{ $company->value['address'] ?? '' }}"  required class="form-control" placeholder="Address">
                                    </div>
                                    <div class="col-lg-6 mt-2">
                                        <label>{{__('Website')}}</label>
                                        <input type="text" name="website" value="{{ $company->value['website'] ?? '' }}" required  class="form-control" placeholder="Website Link">
                                    </div>

                                    <div class="col-lg-6 settings-image-upload">
                                        <label class="title">{{__('Logo')}}</label>
                                        <div class="upload-img-v2">
                                            <label class="upload-v4 settings-upload-v4">
                                                <div class="img-wrp">
                                                    <img src="{{ asset($company->value['logo'] ?? '') }}" alt="logo" id="logo" class="bg-secondary">
                                                </div>
                                                <input type="file" name="logo" class="d-none file-input-change" data-id="logo" accept="image/*">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 settings-image-upload">
                                        <label class="title">{{__('Favicon')}}</label>
                                        <div class="upload-img-v2">
                                            <label class="upload-v4 settings-upload-v4">
                                                <div class="img-wrp">
                                                    <img src="{{ asset($company->value['favicon'] ?? '') }}" alt="user" id="favicon">
                                                </div>
                                                <input type="file" name="favicon" class="d-none file-input-change" data-id="favicon" accept="image/*">
                                            </label>
                                        </div>
                                    </div>

                                    @can('settings-update')
                                    <div class="col-lg-12">
                                        <div class="text-center mt-5">
                                            <button type="submit" class="theme-btn m-2 submit-btn">{{__('Update')}}</button>
                                        </div>
                                    </div>
                                    @endcan
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


