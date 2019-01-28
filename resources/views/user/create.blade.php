@extends_backend('layouts.layout')

@section('content-header')
  <h1>
    {{ __('user::labels.add_user') }}
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.root') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('aksara-user') }}">@lang('user::labels.all_user')</a></li>
    <li class="breadcrumb-item active">@lang('user::labels.add_user')</li>
  </ol>
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-6">
      <div class="box box-solid">
        {!! Form::open(['route' => 'aksara-user-store', 'role' => 'form', 'class' => 'form-horizontal', 'files' => true ])!!}
        @include('user::user._form')
        {!! Form::close() !!}
      </div>
    </div>
  </div>

@endsection
