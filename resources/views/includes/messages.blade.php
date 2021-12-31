@if($errors->any())
    <div class="alert alert-danger animated fadeIn slow" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        @foreach($errors->all() as $error)
            {{ $error }}<br/>
        @endforeach
    </div>
@endif

@if(session()->has("message"))
    <div class="alert alert-success animated fadeIn slow" role="alert">
        <button class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        {{session()->get("message")}}
    </div>
@endif