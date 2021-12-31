@extends('layouts.app')

@section("content")
    <div class="container-fluid">

        <div class="mb-4">
            <h3 class="h3 mb-0 text-gray-800">{{$service->name}} services</h3>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">{{$service->name}} services</h6>
            </div>
            <div class="card-body">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <div>
                        <a href="{{url("/")."/admin/services/new-service"}}" class="btn btn-sm btn-primary btn-icon-split">
                            <span class="text">Add</span>
                            <span class="icon text-white-50">
                                <i class="fas fa-plus"></i>
                            </span>
                        </a>
                    </div>
                </div>
              <div class="table-responsive">
                  <table class="table table-striped table-hover" id="dataTable">
                      <thead>
                          <tr>
                            <th>SN</th>
                            <th>Service Name</th>
                            <th>Service Code</th>
                            <th>Code Name</th>
                            <th>Amount</th>
                            <th>Discount</th>
                            <th>Active</th>
                            <th>Action</th>
                          </tr>
                      </thead>
                      <tfoot>
                          <tr>
                            <th>SN</th>
                            <th>Service Name</th>
                            <th>Service Code</th>
                            <th>Code Name</th>
                            <th>Amount</th>
                            <th>Discount</th>
                            <th>Active</th>
                            <th>Action</th>
                          </tr>
                      </tfoot>
                      <tbody>
                          <?php $i = 1; ?>
                          @foreach ($service->categroy as $category)
                              <tr>
                                  <td>{{$i++}}</td>
                                  <td>{{$category->name}}</td>
                                  <td>{{$category->code}}</td>
                                  <td>{{$category->codename}}</td>
                                  <td>{{$category->amount}}</td>
                                  <td>{{$category->discount}}</td>
                                  <td>
                                      @if ($category->status)
                                          <span class="text-success">Active</span>
                                      @else
                                          <span class="text-danger">Inactive</span>
                                      @endif
                                  </td>
                                  <td>
                                      <div class="row">
                                          <a href="{{url("/")."/admin/services/$category->id"}}" class="mr-1">
                                            <i class="fas fa-eye"></i>
                                          </a>
                                          <a href="{{url("/")."/admin/services/$category->id/edit"}}">
                                              <i class="fas fa-edit"></i>
                                          </a>
                                          <form action="{{url("/")."/admin/services/$category->id"}}" method="POST">
                                              @csrf
                                              @method("DELETE")
                                              <button class="button-sm text-danger">
                                                <i class="fas fa-trash"></i>
                                              </button>
                                          </form>
                                      </div>
                                  </td>
                              </tr>
                          @endforeach
                      </tbody>
                  </table>
              </div>
            </div>
        </div>
    </div>
@endsection