@extends('layouts.master')

@section('main_content')
    <div class="erp-table-section">
        <div class="container-fluid">
            <div class="cards">
            <div class="tab-content order-summary-tab">
                <div class="tab-pane fade show active" id="add-new-user"><br>
                    <div class="table-header">
                        <h4>{{__('Edit')}} {{ ucfirst($user->role) }}</h4>
                    </div>
                    <div class="order-form-section">
                        {{-- form start --}}
                        <form action="{{ route('users.update',$user->id) }}" method="post" enctype="multipart/form-data" class="ajaxform">
                            @csrf
                            @method('put')
                            <div class="add-suplier-modal-wrapper">
                                <div class="row">
                                    <div class="col-lg-6 mt-2">
                                        <label>{{('Full Name')}}</label>
                                        <input type="text" name="name" value="{{ $user->name }}" required class="form-control" placeholder="Enter Name" >
                                    </div>
                                    <div class="col-lg-6 mt-2">
                                        <label>{{__('Phone')}}</label>
                                        <input type="text" name="phone" value="{{ $user->phone }}" class="form-control" placeholder="Enter Phone Number" >
                                    </div>
                                    <div class="col-lg-6 mt-2">
                                        <label>{{__('Role')}}</label>
                                        <div>
                                            <select name="role" required class="select-2 form-control w-100" >
                                                <option value=""> {{__('Select a role')}}</option>
                                                @foreach ($roles as $role)
                                                <option value="{{ $role->name }}" @selected($user->role == $role->name)> {{ ucfirst($role->name) }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mt-2">
                                        <label>{{__('User Name')}}</label>
                                        <input type="text" name="email" value="{{ $user->email }}" required class="form-control" placeholder="Enter Email Address" >
                                    </div>
                                    <div class="col-lg-6 mt-2">
                                        <label>{{__('New Password')}}</label>
                                        <input type="password" name="password" class="form-control" placeholder="Enter Password">
                                    </div>
                                    <div class="col-lg-6 mt-2">
                                        <label>{{__('Confirm password')}}</label>
                                        <input type="password" name="password_confirmation" class="form-control" placeholder="Enter Confirm password">
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="button-group text-center mt-5">
                                            <a href="{{ route('users.index',['users'=>$user->role]) }}" class="theme-btn border-btn m-2">{{__('Cancel')}}</a>
                                            <button class="theme-btn m-2 submit-btn">{{__('Update')}}</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="add-profile-photo m-5 mt-0">
                                    <label for="add-profile" class="add-profile-photo-wrapper">
                                        <input type="file" name="image" accept="image/*" class="d-none" id="add-profile">
                                        <div class="image-wrapper">
                                            <img class="image-preview" src="{{ asset($user->image ?? 'assets/images/profile/avatar.jpg') }}" alt="profile-img">
                                        </div>
                                        <div class="icons"><i class="fal fa-camera"></i>
                                        </div>
                                    </label>
                                    <p>{{__('Upload Image')}}</p>
                                </div>
                            </div>
                        </form>
                        {{-- form end --}}
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
@endsection
