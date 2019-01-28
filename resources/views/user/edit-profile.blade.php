@extends_backend('layouts.layout')

@section('content-header')
  <h1>
    {{ __('user::labels.edit_profile') }}
  </h1>
@endsection

@section('breadcrumb')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.root') }}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
    <li class="breadcrumb-item active">@lang('user::labels.edit_profile')</li>
  </ol>
@endsection

@section('content')
  <div class="row">
    <div class="col-lg-8">
      <div class="box box-solid">
        {!! Form::model($user, ['route' => ['aksara.user.update-profile'], 'class' => 'form-horizontal', 'files' => true ])!!}
        {{ method_field('PUT') }}
        @include('user::user._form')
        {!! Form::close() !!}
      </div>
    </div>
  </div>

@endsection
