@extends_backend('layouts.layout')

@section('content-header')
    <h1>
      {{ __('user::labels.user_list') }}
      @if(has_capability('add-user'))
        <a href="{{ route('aksara-user-create') }}" class="btn btn-brand">@lang('user::labels.add_user')</a></h2>
      @endif
    </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li class=""><a href="{{ route('admin.root') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="active">@lang('user::labels.all_user')</li>
  </ol>
@endsection

@section('content')
<div class='row'>
  <div class="col-md-12">

     {!! $presenter->complete() !!}


  </div>
</div>
@endsection

