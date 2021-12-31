@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="col-lg-12 mb-4">
            <h3 class="h4">New Savings Category</h3>
        </div>
        <div class="col-lg-6 col-md-6 col-12 animated fadeIn slow">
             @include('includes.messages')
            <div class="wrapper shadow-sm">
                <form action="{{route('account.category')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="name">Category name:</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Category name" required>
                    </div>
                    <div class="form-group">
                        <label for="type">Savings Type:</label>
                        <select name="type" id="type" class="form-control">
                            <option value="Savings">Savings</option>
                            <option value="default">Default Savings</option>
                            <option value="Safe lock">Safe lock</option>
                        </select>
                        {{-- <input type="text" name="type" id="type" class="form-control" placeholder="Savings Type" required> --}}
                    </div>
                    <div class="form-group">
                        <label for="categorytype">Category type:</label>
                        <select name="categorytype" id="categorytype" class="form-control">
                            <option value="parent">Parent Category</option>
                            <option value="sub-category">Sub Category</option>
                        </select>
                    </div>
                    <div class="form-group hidden" id="parentcat"> 
                        <label for="parentcat">Parent category:</label>
                        <select name="parentcat"  class="form-control">
                            <option value="">---Parent category---</option>
                            @foreach ($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="interest">Interest rate ( <em>In figure e.g 10 for 10%</em> )</label>
                        <input type="text" name="interest" placeholder="Interest rate" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="interest">Interest interval (<em>Interval before crediting Savings</em>)</label>
                        <input type="text" name="interest_interval" placeholder="Interest interval " class="form-control">
                    </div>
                    <div class="">
                        <button class="btn btn-lg text-white bg-gradient-primary">
                            Submit <i class="fas fa-arrow-circle-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection