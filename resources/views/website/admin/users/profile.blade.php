@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="mb-3">
            <h1 class="h4">User Profile</h1>
        </div>
        @include('includes.messages')
        <div class="row">
            <!-- Profile -->
            <div class="col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <div class="d-sm-flex align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary p-0">Profile</h6>
                            <a href="/admin/users/{{$user->id}}/edit" >
                                <i class="fas fa-edit"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-sm-flex align-items-center justify-content-between mb-2">
                            <div class="">
                                Name: <span>{{$user->last_name." ".$user->first_name}}</span>
                            </div>
                            <div class="">
                                Gender: <span>{{$user->gender}}</span>
                            </div>
                        </div>
                        <div class="d-sm-flex align-items-center justify-content-between mb-2">
                            <div class="">
                                Email: <span>{{$user->email}}</span>
                            </div>
                            <div class="">
                                Mobile: <span>{{$user->phone_number}}</span>
                            </div>
                        </div>
                        <div class="d-sm-flex align-items-center justify-content-between mb-2">
                            <div class="">
                                Verified:
                                @if ($user->isVerified)
                                    <span class="text-success">Yes</span>
                                @else
                                    <span class="text-danger">No</span>
                                @endif
                            </div>
                            <div class="">
                                Status:
                                @if ($user->is_active)
                                    <span class="text-success">Active</span>
                                @else
                                    <span class="text-danger">Inactive</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Banks -->
                <div class="">
                    <div class="card shadow mb-4">
                        <a href="#collapseBank" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseBank">
                            <h6 class="m-0 font-weight-bold text-primary">Banks</h6>
                        </a>
                        <div class="collapse show" id="collapseBank">
                            <div class="card-body table-responsive">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>Bank</th>
                                        <th>Acc no:</th>
                                        <th>Bank code</th>
                                        <th>Date added</th>
{{--                                        <th></th>--}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if (count($user->banks) > 0)
                                        @foreach ($user->banks as $bank)
                                            <tr>
                                                <td>{{$bank["bank_name"]}}</td>
                                                <td>{{$bank["account_number"]}}</td>
                                                <td>{{$bank["bank_code"]}}</td>
                                                <td>{{$bank["created_at"]}}</td>
{{--                                                <td>--}}
{{--                                                    <div class="row">--}}
{{--                                                        <form action="/admin/transactions/bank{{$bank->id}}" method="POST">--}}
{{--                                                            @csrf--}}
{{--                                                            @method("DELETE")--}}
{{--                                                            <button class="button-sm text-danger">--}}
{{--                                                                <i class="fas fa-trash"></i>--}}
{{--                                                            </button>--}}
{{--                                                        </form>--}}
{{--                                                    </div>--}}
{{--                                                </td>--}}
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                No data found
{{--                                                <a href="/admin/transactions/bank/add/{{$user->id}}" class="ml-2">--}}
{{--                                                    Add <i class="fas fa-plus"></i>--}}
{{--                                                </a>--}}
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cards -->
                <div class="card shadow mb-4">
                    <a href="#collapseCard" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseCard">
                        <h6 class="m-0 font-weight-bold text-primary">Cards</h6>
                    </a>
                    <div class="collapse show" id="collapseCard">
                        <div class="card-body table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Card Type</th>
                                    <th>Card number</th>
                                    <th>Expiry</th>
                                    <th>Dated added</th>
{{--                                    <th></th>--}}
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($user->cards) > 0)
                                    @foreach ($user->cards as $card)
                                        <tr>
                                            <td>{{$card["card_type"]}}</td>
                                            <td>{{$card["card_number"]}}</td>
                                            <td>{{$card["expiry"]}}</td>
                                            <td>{{$card["created_at"]}}</td>
{{--                                            <td>--}}
{{--                                                <div class="row">--}}
{{--                                                    <form action="/admin/account/card/{{$card->id}}" method="POST">--}}
{{--                                                        @csrf--}}
{{--                                                        @method("DELETE")--}}
{{--                                                        <button class="button-sm text-danger">--}}
{{--                                                            <i class="fas fa-trash"></i>--}}
{{--                                                        </button>--}}
{{--                                                    </form>--}}
{{--                                                </div>--}}
{{--                                            </td>--}}
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            No data found
{{--                                            <a href="/admin/transactions/card/add/{{$user->id}}" class="ml-2">--}}
{{--                                                Add <i class="fas fa-plus"></i>--}}
{{--                                            </a>--}}
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Next of KIN -->
                <div class="card shadow mb-4">
                    <a href="#collapseKin" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseKin">
                        <h6 class="m-0 font-weight-bold text-primary">
                            Next of Kin
                            @if($user->agentApproval)
                                @if($user->agentApproval->status == 1)
                                    <span class="text-success ml-3">   Agent Approved</span>
                                @else
                                    <span class="text-danger ml-3">   Agent Declined</span>
                                @endif
                            @else
                                <span class="text-danger ml-3">   Pending Agent Approval</span>
                            @endif
                        </h6>
                    </a>
                    <div class="collapse show" id="collapseKin">
                        <div class="card-body table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Phone Number</th>
                                    <th>Email</th>
                                    <th>Relationship</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if ($user->nextOfKin !== null )
                                    <tr>
                                        <td>{{$user->nextOfKin->name}}</td>
                                        <td>{{$user->nextOfKin->phoneNumber}}</td>
                                        <td>{{$user->nextOfKin->email}}</td>
                                        <td>{{$user->nextOfKin->relationship}}</td>
                                        <td>
                                            @if($user->nextOfKin->status == 1)
                                                <span class="text-success">Approved</span>
                                            @else
                                                <span class="text-danger">Declined</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <form action="/admin/transactions/next-of-kin/{{$user->nextOfKin->id}}" method="POST">
                                                    @csrf
                                                    @method("DELETE")
                                                    <button class="button-sm text-danger btn-sm btn">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            No data found
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer text-right">
                            @if($user->nextOfKin && $user->nextOfKin !== null)
                                <div class="btn-group">
                                    @if($user->nextOfKin->status == 1)
                                        <button type="button"  class="btn btn-success btn-sm">Approved</button>
                                        <button type="button"
                                                class="btn btn-danger btn-sm mgtbtn"
                                                data-id="{{$user->id}}" data-type="decline"
                                                data-toggle="modal"
                                                data-target="#approvalModal"
                                                data-url="{{route('kin.update', [$user->nextOfKin->id, 'action' => 'decline'])}}"
                                        >Decline</button>
                                    @else
                                    <button type="button"
                                            class="btn btn-primary btn-sm mgtbtn"
                                            data-id="{{$user->id}}" data-type="approve"
                                            data-toggle="modal"
                                            data-target="#approvalModal"
                                            data-url="{{route('kin.update', [$user->nextOfKin->id, 'action' => 'approve'])}}"
                                    >Approve</button>
                                    <button type="button"
                                            class="btn btn-danger btn-sm mgtbtn"
                                            data-id="{{$user->id}}" data-type="decline"
                                            data-toggle="modal"
                                            data-target="#approvalModal"
                                            data-url="{{route('kin.update', [$user->nextOfKin->id, 'action' => 'decline'])}}"
                                    >Decline</button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Employment details -->
                <div class="card shadow mb-4">
                    <a href="#collapseEmp" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseEmp">
                        <h6 class="m-0 font-weight-bold text-primary">
                            Employment Details
                            @if($user->agentApproval)
                                @if($user->agentApproval->status == 1)
                                    <span class="text-success ml-3"> Agent  Approved</span>
                                @else
                                    <span class="text-danger ml-3">   Agent Declined</span>
                                @endif
                            @else
                                <span class="text-danger ml-3">  Pending Agent Approval </span>
                            @endif

                        </h6>
                    </a>
                    <div class="collapse show" id="collapseEmp">
                        <div class="card-body table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Employment Status</th>
                                    <th>Type</th>
                                    <th>Employer</th>
                                    <th>Salary</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if ($user->employment !== null )
                                    <tr>
                                        <td>{{$user->employment->employment_status}}</td>
                                        <td>{{$user->employment->employment_type}}</td>
                                        <td>{{$user->employment->employer}}</td>
                                        <td>{{number_format($user->employment->salary, 4)}}</td>
                                        <td>{{$user->employment->last_location}}</td>
                                        <td @if($user->employment->approval_status == 1) class="text-success" @else class="text-danger" @endif>
                                            @if($user->employment->status == 1)
                                                Approved
                                            @else
                                                Pending
                                            @endif
                                        </td>
                                    </tr>
                                    <tr >
                                        <td colspan="5">
                                            @if($user->employment->proof_of_employment !== null && (strpos($user->employment->proof_of_employment, 'jpg') !== false || strpos($user->employment->proof_of_employment, 'png') !== false || strpos($user->employment->proof_of_employment, 'svg') !== false || strpos($user->employment->proof_of_employment, 'jpeg') !== false))
                                                <img src="{{asset('storage/'.$user->employment->proof_of_employment)}}" class="img-fluid" />
                                            @else
                                                <img src="https://via.placeholder.com/500x250" class="img-fluid" />
                                            @endif
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            No data found
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer text-right">
                            @if($user->employment)
                                @if($user->employment->status == 1)
                                    <div class="btn-group">
                                        <a href="javascript:;" class="btn btn-sm btn-success">Approved</a>
                                        <a href="{{route('employment.update', [$user->employment->id, 'action' => 'decline'])}}" class="btn btn-sm btn-danger">Decline</a>
                                    </div>
                                @else
                                    <div class="btn-group">
                                        <button type="button"
                                                class="btn btn-primary btn-sm mgtbtn"
                                                data-id="{{$user->employment->id}}" data-type="approve"
                                                data-toggle="modal"
                                                data-target="#approvalModal"
                                                data-url="{{route('employment.update', [$user->employment->id, 'action' => 'approve'])}}"
                                        >Approve</button>
                                        <button type="button"
                                                class="btn btn-danger btn-sm mgtbtn"
                                                data-id="{{$user->employment->id}}" data-type="decline"
                                                data-toggle="modal"
                                                data-target="#approvalModal"
                                                data-url="{{route('employment.update', [$user->employment->id, 'action' => 'decline'])}}"
                                        >Decline</button>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Social networks -->
                <div class="card shadow mb-4">
                    <a href="#collapseSocial"
                       class="d-block card-header py-3"
                       data-toggle="collapse"
                       role="button" aria-expanded="true"
                       aria-controls="collapseSocial"
                    >
                        <h6 class="m-0 font-weight-bold text-primary">
                            Social Networks

                            @if($user->agentApproval)
                                @if($user->agentApproval->status == 1)
                                    <span class="text-success ml-3">   Agent Approved</span>
                                @else
                                    <span class="text-danger ml-3">   Agent Declined</span>
                                @endif
                            @else
                                <span class="text-danger ml-3">   Pending Agent Approval</span>
                            @endif
                        </h6>
                    </a>
                    <div class="collapse show" id="collapseSocial">
                        <div class="card-body table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Handle</th>
                                    <th>Date</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if ($user->social_links !== null )
                                    @foreach($user->social_links as $socials)
                                        <tr>
                                            <td>{{$socials->name}}</td>
                                            <td>
                                                <a href="{{$socials->handle}}" target="_blank">{{$socials->handle}}</a>
                                            </td>
                                            <td>{{$socials->created_at->toFormattedDateString()}}</td>
                                            <td>{{$socials->last_location}}</td>
                                            <td @if($socials->status == 1) class="text-success" @else class="text-danger" @endif>
                                                @if($socials->status == 1)
                                                    Approved
                                                @else
                                                    Pending
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            No data found
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="card-footer text-right">
                            @if($user->social_links && $user->social_links->count() > 0)
                                @if($user->agentApproval  && $user->agentApproval->status == 1 && $user->social_links->first()->status == 1)
                                    <div class="btn-group">
                                        <button type="button"
                                                class="btn btn-success btn-sm"
                                        >Approved</button>
                                        <button type="button"
                                                class="btn btn-danger btn-sm mgtbtn"
                                                data-id="{{$user->id}}" data-type="decline"
                                                data-toggle="modal"
                                                data-target="#approvalModal"
                                                data-url="{{route('social.update', [$user->id, 'action' => 'decline'])}}"
                                        >Decline</button>
                                    </div>
                                @else
                                    <div class="btn-group">
                                        <button type="button"
                                                class="btn btn-primary btn-sm mgtbtn"
                                                data-id="{{$user->id}}" data-type="approve"
                                                data-toggle="modal"
                                                data-target="#approvalModal"
                                                data-url="{{route('social.update', [$user->id, 'action' => 'approve'])}}"
                                        >Approve</button>
                                        <button type="button"
                                                class="btn btn-danger btn-sm mgtbtn"
                                                data-id="{{$user->id}}" data-type="decline"
                                                data-toggle="modal"
                                                data-target="#approvalModal"
                                                data-url="{{route('social.update', [$user->id, 'action' => 'decline'])}}"
                                        >Decline</button>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>

            </div>


            <div class="col-lg-6">
                <!-- Accounts -->
                <div class="card shadow mb-4">
                    <!-- Card Header - Accordion -->
                    <a href="#collapseAccounts" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseAccounts">
                        <h6 class="m-0 font-weight-bold text-primary">Accounts</h6>
                    </a>
                    <!-- Card Content - Collapse -->
                    <div class="collapse show" id="collapseAccounts">
                        <div class="card-body table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th>Curren balance</th>
                                    <th>Previous balance</th>
                                    <th>Last trans</th>
                                    <th>Acc no:</th>
                                    <th>Bank</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if (count($user->accounts) > 0)
                                    @foreach ($user->accounts as $account)
                                        <tr>
                                            <td>{{$account["currency"].number_format($account["current_balance"], 2)}}</td>
                                            <td>{{$account["currency"].number_format($account["prev_balance"], 2)}}</td>
                                            <td>{{$account["currency"].number_format($account["amount"],2)}}</td>
                                            <td>{{$account["account_number"]}}</td>
                                            <td>{{$account["bank_name"]}}</td>
                                            @can("isAdmin")
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="javascript:;" class="text-primary editBtn " data-account="{{$account["id"]}}" data-toggle="modal" data-target="#AccountModal">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        {{--  <a href="javascript:;"  class="action_btn btn btn-danger" data-action='{{$account["id"]}}' data-toggle="modal" data-target="#exampleModal"><i class="fa fa-trash"></i></a>--}}
                                                    </div>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            No data found <a href="/admin/transactions/account/add" class="ml-2">Add +</a>
                                        </td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- SAS -->
                <div class="card shadow mb-4">
                    <a href="#collapseKyc" class="d-block card-header py-3" data-toggle="collapse" role="button" aria-expanded="true" aria-controls="collapseKyc">
                        <h6 class="m-0 font-weight-bold text-primary">SAS
                            @if($user->agentApproval)
                                @if($user->agentApproval->status == 1)
                                    <span class="text-success ml-3"> Approved</span>
                                @else
                                    <span class="text-danger ml-3">  Declined</span>
                                @endif
                            @else
                                <span class="text-danger ml-3">   Not yet Approved</span>
                            @endif
                        </h6>
                    </a>
                    <div class="collapse show" id="collapseKyc">
                        <div class="card-body table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>
                                                <strong>Points:</strong>
                                                {{
                                                    $user->kycs->sum('points') + ($user->agentApproval ? $user->agentApproval->points : 0) + ($user->employment ? $user->employment->points : 0) + ($user->social_links()  && count($user->social_links) > 0 ? $user->social_links()->first()->points : 0)
                                                }}
                                            </td>
                                        </tr>
                                        @foreach($user->kycs as $kyc)
                                            @if ($kyc !== null)
                                                <tr>
                                                    <td>
                                                        <strong>{{ucfirst($kyc->type)}}: </strong>
                                                        @if($kyc->approval_status == 1) <span class="text-success">Approved</span> @endif <br>
                                                        @if((strpos($kyc->value, 'jpg') !== false || strpos($kyc->value, 'png') !== false || strpos($kyc->value, 'svg') !== false || strpos($kyc->value, 'jpeg') !== false))
                                                            <br>
                                                            @if($kyc->type === 'profile_photo')
                                                                <a href="{{asset('storage/'.$kyc->value)}}" data-lightbox="image-1" data-title="{{$kyc->type}}">
                                                                    <img src="{{asset('storage/'.$kyc->value)}}" class="img-fluid"  width="100"/>
                                                                </a>
                                                                 <br>
                                                            @endif
                                                            @if($kyc->type === 'means_of_identity' ||  $kyc->type === 'residential_document')
                                                                <a href="{{asset('storage/'.$kyc->value)}}" data-lightbox="image-1" data-title="{{$kyc->type}}">
                                                                    <img src="{{asset('storage/'.$kyc->value)}}" class="img-fluid" width="100"/>
                                                                </a>
                                                                <br>
                                                            @endif
                                                        @else
                                                            {{ucfirst($kyc->value)}} <br> <br>
                                                        @endif
                                                        <strong>Reason for disapproval:</strong> <br>
                                                        <p>{{$kyc->reason_for_disapproval}}</p>
                                                        <div>
{{--                                                            <div class="form-group" id="{{$kyc->type.'form'}}" style="display: none">--}}
{{--                                                                <textarea class="form-control reason" placeholder="Reason"></textarea>--}}
{{--                                                            </div>--}}
                                                            <div class="row align-items-center justify-content-between">
                                                                <small class="text-primary">Last Location: {{ucfirst($kyc->last_location)}}</small>

                                                                <div id="{{$kyc->type.'buttons'}}" >
                                                                    @if($kyc->approval_status == 1)
                                                                        <div class="btn-group">
                                                                            <a href="javascript:;" class="btn btn-sm btn-success">Approved</a>
                                                                            <button type="button"
                                                                                    class="btn btn-danger btn-sm mgtbtn"
                                                                                    data-id="{{$kyc->id}}" data-type="decline"
                                                                                    data-toggle="modal"
                                                                                    data-target="#approvalModal"
                                                                                    data-url="{{route('kyc.update', [$kyc->id, 'action' => 'decline'])}}"
                                                                            >Decline</button>
                                                                        </div>
                                                                    @else
                                                                        <div class="text-right">
                                                                            <div class="btn-group">
                                                                                <button type="button"
                                                                                        class="btn btn-primary btn-sm mgtbtn"
                                                                                        data-id="{{$kyc->id}}" data-type="approve"
                                                                                        data-toggle="modal"
                                                                                        data-target="#approvalModal"
                                                                                        data-url="{{route('kyc.update', [$kyc->id, 'action' => 'approve'])}}"
                                                                                >Approve</button>
                                                                                <button type="button"
                                                                                        class="btn btn-danger btn-sm mgtbtn"
                                                                                        data-id="{{$kyc->id}}" data-type="decline"
                                                                                        data-toggle="modal"
                                                                                        data-target="#approvalModal"
                                                                                        data-url="{{route('kyc.update', [$kyc->id, 'action' => 'decline'])}}"
                                                                                >Decline</button>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="5" class="text-center">
                                                        No data found
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>

                        </div>
{{--                        <div class="card-footer text-right">--}}
{{--                            @if($user->kyc)--}}
{{--                                @if($user->kyc->status == 1)--}}
{{--                                    <div class="btn-group">--}}
{{--                                        <a href="javascript:;" class="btn btn-sm btn-success">Approved</a>--}}
{{--                                        <a href="{{route('kyc.update', [$user->kyc->id, 'action' => 'decline'])}}" class="btn btn-sm btn-danger">Decline</a>--}}
{{--                                    </div>--}}
{{--                                @else--}}
{{--                                    <div class="btn-group">--}}
{{--                                        <a href="{{route('kyc.update', [$user->kyc->id, 'action' => 'approve'])}}" class="btn btn-sm btn-primary">Approve</a>--}}
{{--                                        <a href="{{route('kyc.update', [$user->kyc->id, 'action' => 'decline'])}}" class="btn btn-sm btn-danger">Decline</a>--}}
{{--                                    </div>--}}
{{--                                @endif--}}
{{--                            @endif--}}
{{--                        </div>--}}
                    </div>
                </div>
            </div>
        </div>

    </div>
    {{--    Account modal--}}
    <div class="modal fade" id="AccountModal" tabindex="-1" role="dialog" aria-labelledby="AccountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4>Manage Wallet</h4>
                    <button type="button" class="close text-danger" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{route('user.account.manage')}}" method="POST">
                    @csrf
                    <div class="modal-body ">
                        <div>
                            <div class="row">
                                <input type="hidden" name="account_id" id="accountId">
                                <div class="col">
                                    <label for="transaction_type">Transaction Type</label>
                                    <select name="transaction_type" id="transaction_type" class="form-control" required>
                                        <option value="debit">Debit</option>
                                        <option value="credit">Credit</option>
                                    </select>
                                </div>
                                <div class="col">
                                    <label for="amount">Amount</label>
                                    <input type="number" class="form-control" required placeholder="Amount" name="amount">
                                </div>
                            </div>
                            <div class="form-group mb-2 mt-2">
                                <label for="narration">Narration</label>
                                <textarea name="narration" id="narration" class="form-control" placeholder="Narration"></textarea>
                            </div>
                            <div class="text-right">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button class="btn btn-danger" >Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
