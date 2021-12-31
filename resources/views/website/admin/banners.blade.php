@extends('layouts.app')

@section('content')
    <div class="container-fluid animated fadeIn slower">

        <div class="col-lg-6 mb-2">
            @include('includes.messages')
        </div>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">App Banners</h6>
            </div>
            <div class="card-body">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <form action="{{route('banner.create')}}" method="POST" enctype="multipart/form-data" class="mb-3 w-100 animated fadeIn" id="editForm">
                        @csrf
                        <div class="row">
                            <div class="col">
                            	<label>Chose banners</label>
                                <input type="file" name="banner[]" multiple="true" class="form--control">
                            </div>
                            <div class="col">
                            	<label>Publish?</label>
                            	<select name="status" class="form-control">
                            		<option value="yes">Yes</option>
                            		<option value="no">No</option>
                            	</select>
                            </div>
                            <div class="col">
                                <button class="btn bg-gradient-primary text-white">
                                   Save <i class="fas fa-arrow-circle-right"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 col-lg-4">
                	@foreach($banners as $banner) 
                	    <div class="card mb-3">
		                	<div class="card-body">
		                		<img src="{{asset('imgs/banners/'.$banner->banner_url)}}" alt="banner" />
		                	</div>
		                	<div class="card-footer d-flex">
		                		@if($banner->status)
		                			<a href="{{url('/').'/admin/banner/'.$banner->id}}">Unpublish</a>
		                		@else 
		                			<a href="{{url('/').'/admin/banner/'.$banner->id}}">Publish</a>
		                		@endif
		                		<form action="{{url('/').'/admin/banner/'.$banner->id}}" method="POST">
                                  @csrf
                                  @method("DELETE")
                                  <button class="button-sm text-danger">
                                  	Delete
                                  </button>
                                 </form>
		                	</div>
		                </div>
                	@endforeach
                </div>

            </div>
        </div>
    </div>
@endsection 
