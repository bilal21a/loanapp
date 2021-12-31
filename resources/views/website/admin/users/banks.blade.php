@extends('layouts.app')

@section("content")
    <div class="container-fluid">
        <section>
            <div class="row">
                @include('includes.sideNav')
                <div class="col-lg-8 col-md-8 col-12">
                    <h3>User account</h3>
                    <table class="table table-stripe">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Curren balance</th>
                                <th>Last transaction</th>
                                <th>Account number</th>
                                 <th>Bank</th>
                                <th>Code</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @foreach ($accounts->accounts as $account)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$account["currency"].number_format($account["current_balance"], 2)}}</td>
                                    <td>{{$account["currency"].number_format($account["amount"],2)}}</td> 
                                    <td>{{$account["account_number"]}}</td>
                                    <td>{{$account["bank_name"]}}</td>
                                    <td>{{$account["bank_code"]}}</td>
                                </tr> 
                            @endforeach
                        </tbody>
                    </table>
                    <div class="pagination">
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection