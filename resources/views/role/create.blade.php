@extends_backend('layouts.layout')

@section('breadcrumb')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.root') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('aksara-role') }}">@lang('user::labels.all_roles')</a></li>
    <li class="breadcrumb-item active">@lang('user::labels.add_role')</li>
</ol>
@endsection

@section('content-header')
    <h1>
      {{ __('user::labels.add_role') }}
    </h1>
@endsection

@section('content')
<!-- /.content__head -->

<div class="row">
    <div class="col-lg-8">
        <div class="box box-solid">
        {!! Form::open(['route' => 'aksara-role-store', 'role' => 'form', 'class' => 'form-horizontal text-left'])!!}
        @include('user::role._form')
        <div class="box-footer text-right">
            <input type="submit" class="btn btn-md btn-brand" value="{{__('user::labels.add_role')}}">
        </div>
        {!! Form::close() !!}
        </div>
    </div>
</div>

@endsection
