@extends('layouts.app')

@section('content')
    <div class="container-fluid animated fadeIn slower">
        <div class="col-lg-12 mb-4">
            <h3 class="h4">Update Savings Category</h3>
        </div>
        <div class="col-lg-6 col-md-6 col-12">
             @include('includes.messages')
            <div class="wrapper shadow-sm">
                <form action="{{url("/")."/admin/account/category/$account->id"}}" method="POST" enctype="application/x-www-form-urlencoded">
                    @csrf
                    @method("PUT")
                    <div class="form-group">
                        <label for="name">Category name:</label>
                        <input type="text" value="{{$account->name}}" name="name" id="name" class="form-control" placeholder="Category name" required>
                    </div>
                    <div class="form-group">
                        <label for="type">Savings Type:</label>
                        <select name="type" id="type" class="form-control">
                            <option value="{{$account->type}}">{{$account->type}}</option>
                            <option value="Savings">Savings</option>
                            <option value="default">Default Savings</option>
                            <option value="Safe lock">Safe lock</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="categorytype">Category type:</label>
                        <select name="categorytype" id="categorytype" class="form-control">
                            @if (empty($account->parent))
                                <option value="parent">Parent Category</option>
                                <option value="sub-category">Sub Category</option>
                            @else
                                <option value="sub-category">Sub Category</option>
                                <option value="parent">Parent Category</option>
                            @endif
                        </select>
                    </div>
                    {{-- @if (!empty($account->parent)) --}}
                        <div class="form-group hidden" id="parentcat">
                            <label for="parentcat">Parent category:</label>
                            <select name="parentcat"  class="form-control">
                                <option value="">---Parent category---</option>
                                @foreach ($categories as $category)
                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                    @foreach ($category->subCategories as $subcat)
                                        <option value="{{$subcat->id}}">{{$subcat->name}}</option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    {{-- @endif --}}
                    <div class="form-group">
                        <label for="interest">Interest rate ( <em>In figure e.g 10 for 10%</em> )</label>
                        <input type="text" value="{{$account->interest_rate}}" name="interest" placeholder="Interest rate" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="interest">Interest interval (<em>Interval before crediting account</em>)</label>
                        <input type="text" value="{{$account->interest_interval}}" name="interest_interval" placeholder="Interest interval " class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control">
                            @if ($account->status == 1)
                                <option value="1">Active</option>
                                <option value="0">Deactivate</option>
                            @else
                                <option value="0">Inactive</option>
                                <option value="1">Activate</option>
                            @endif
                        </select>
                    </div>
                    <div class="">
                        <button class="btn text-white bg-gradient-primary">
                            Submit <i class="fas fa-arrow-circle-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection