@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="mb-4 col-lg-12">
            <h3 class="h4">Create Investment Plan</h3>
        </div>

        <div class="col-lg-6 col-md-6 col-12">
            @include('includes.messages')
            <div class="wrapper shadow-sm">
                <form action="{{route('investment.add')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-2">
                        <div class="col">
                            <label for="name">Investment name:</label>
                            <input type="text"
                                   name="name"
                                   id="name"
                                   class="form-control"
                                   placeholder="Name of Investmen Plan"
                                   required
                                   value="{{ old('name') }}"
                            >
                        </div>
                        <div class="col">
                            <label for="type">Type:</label>
                            <select name="type" id="type" class="form-control">
                                <option value="short term">Short Term</option>
                                <option value="long term">Long Term</option>
                            </select>
{{--                            <input type="text" name="type" id="type" class="form-control" placeholder="Investment Type" required>--}}
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <label for="amount">Amount:</label>
                            <input type="text"
                                   name="amount"
                                   placeholder="Inestment amount"
                                   class="form-control" required
                                   value="{{ old('amount') }}"
                            >
                        </div>
                        <div class="col">
                            <label for="amount_per_slot">Amount per slot:</label>
                            <input type="text"
                                   name="amount_per_slot"
                                   placeholder="Inestment amount per investor"
                                   class="form-control" required
                                   value="{{ old('amount_per_slot') }}"
                            >
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <label for="duration">Duration:</label>
                            <input type="text"
                                   name="duration"
                                   placeholder="Investment duration"
                                   class="form-control" required
                                   value="{{ old('duration') }}"
                            >
                        </div>
                        <div class="col">
                            <label for="interest_rate">Interest rate:</label>
                            <input type="text"
                                   name="interest_rate"
                                   placeholder="Investment interest rate"
                                   class="form-control" required
                                   value="{{ old('interest_rate') }}"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea name="description" rows="5" class="form-control" placeholder="Enter description here">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="btn btn-lg bg-gray-300">
                            Browse
                            <input type="file" name="file" id="file" hidden> 
                        </label>
                    </div>
                    <div class="">
                        <button class="btn animated pulse slower btn-lg bg-gradient-primary text-white">
                            Submit <i class="fas fa-arrow-circle-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection
