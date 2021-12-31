@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="mb-3">
            <h3 class="h4">Savings Categories</h3>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">Savings Categories</h6>
            </div>
            <div class="card-body">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <div>
                        <a href="{{url("/")."/admin/account/new-category"}}" class="btn text-white btn-sm bg-gradient-primary">
                            <span class="text">Create</span>
                            <span class="icon text-white-50">
                                <i class="fas fa-plus"></i>
                            </span>
                        </a>
                    </div>
                </div>
              <div class="table-responsive">
                <table id="dataTable" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Dated created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>SN</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Dated created</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php $i = 1; $j = 1; ?>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{$i++}}</td>
                                <td colspan="3">
                                    {{$category->name}}
                                </td>
                                <td>
                                    <div class="row">
                                        <a href="{{url("/")."/admin/account/category/$category->id"}}/" class="mr-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{url("/")."/admin/account/category/$category->id"}}" method="POST">
                                            @csrf
                                            @method("DELETE")
                                            <button class="button-sm text-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @foreach ($category->subCategories as $subcategory)
                                <tr>
                                    <td></td>
                                    <td>{{$subcategory->name}}</td>
                                    <td>{{$subcategory->type}}</td>
                                    <td>{{$subcategory->created_at}}</td>
                                    <td>
                                        <div class="row">
                                            <a href="{{url("/")."/admin/account/category/$subcategory->id"}}" class="mr-1">
                                                <ion-icon name="eye"></ion-icon>
                                            </a>
                                            <a href="{{url("/")."/admin/account/category/$subcategory->id"}}">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                          <button type="button" class="action_btn btn btn-sm btn-danger" data-action='{{$subcategory->id}}' data-toggle="modal" data-target="#exampleModal"><i class="fa fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
              </div>
            </div>
        </div>
    </div>
@endsection
