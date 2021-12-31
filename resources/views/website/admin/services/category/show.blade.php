@extends('layouts.app')

@section("content")
    <div class="container-fluid">

        <div class="mb-4">
            <h3 class="h3 mb-0 text-gray-800">{{$category->name}} services</h3>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">{{$category->name}} services</h6>
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
                            <th>Discount</th>
                            <th>Active</th>
                            <th>Action</th>
                          </tr>
                      </tfoot>
                      <tbody>
                          <?php $i = 1; ?>
                          @foreach ($category->services as $subcat)
                              <tr>
                                  <td>{{$i++}}</td>
                                  <td>{{$subcat->name}}</td>
                                  <td>{{$subcat->code}}</td>
                                  <td>{{$subcat->discount}}</td>
                                  <td>
                                      @if ($subcat->status)
                                          <span class="text-success">Active</span>
                                      @else
                                          <span class="text-danger">Inactive</span>
                                      @endif
                                  </td>
                                  <td>
                                    <nav>
                                        <ul class="nav">
                                            <li class="dropdown no-arrow">
                                              <a href="javascript:;" data-toggle="dropdown">
                                                  <i class="fas fa-ellipsis-h"></i>
                                              </a>
                                              <div class="dropdown-menu text-center dropdown-menu shadow animated">
                                                  <a href="{{url("/")."/admin/services/$subcat->id/edit"}}">
                                                      Edit
                                                  </a> <br>
                                                  <form action="{{url("/")."/admin/services/$subcat->id"}}" method="POST">
                                                      @csrf
                                                      @method("DELETE")
                                                      <button class="button-sm text-danger">
                                                      Delete
                                                      </button>
                                                  </form>
                                              </div>
                                            </li>
                                        </ul>
                                    </nav>
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