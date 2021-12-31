@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        
        <div class="mb-3 col-lg-12">
            <h3 class="h4">Investors</h3>
            <p>{{$investment->description}}</p>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-primary">{{$investment->name}} Investors</h6>
            </div>
            <div class="card-body">
                <div class="d-sm-flex align-items-center justify-content-between mb-2">
                    <div>
                        {{$investment->description}}
                    </div>
                    <div>
                        <a href="#" class="btn btn-sm bg-gradient-primary text-white">
                            Create <i class="fas fa-plus"></i>
                        </a>
                    </div> 
                </div>
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>SN</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Amount (NGN)</th>
                            <th>Slots</th>
                            <th>Interest rate</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>SN</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Amount ()</th>
                            <th>Slots</th>
                            <th>Interest rate</th>
                        </tr>
                    </tfoot>
                    <tbody>                        
                        <?php $i = 1; $j = 1; ?>
                            @foreach ($investment->investors as $investor)
                                <tr>
                                @if(isset($investor->user))
                                    <td>{{$i++}}</td>
                                    <td>
                                        @if(isset($investor->user->first_name))
                                        {{$investor->user->first_name}} @endif
                                        @if(isset($investor->user->last_name))   
                                        {{$investor->user->last_name}}
                                        @endif
                                    </td>
                                    <td>{{$investor->user->email}}</td>
                                    <td>{{number_format($investor->amount, 2)}}</td>
                                    <td>{{$investor->slots}}</td>
                                    <td>{{$investor->interest}}</td>
                                @endif
                                </tr>
                            @endforeach
                            @if (count($investment->investors) < 1)
                                <tr>
                                    <td class="text-center" colspan="10">No investor found</td>
                                </tr>
                            @endif
                    </tbody>
                </table>
              </div>
            </div>
        </div>
    </div>
@endsection