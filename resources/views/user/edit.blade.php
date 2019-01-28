@extends_backend('layouts.layout')
@section('content-header')
  <h1>
    {{ __('user::labels.edit_user') }}
  </h1>
@endsection
@section('breadcrumb')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.root') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('aksara-user') }}">@lang('user::labels.all_user')</a></li>
    <li class="breadcrumb-item active">@lang('user::labels.edit_user')</li>
  </ol>
@endsection


@section('content')
  <div class="row">
    <div class="col-lg-8">
      <div class="box box-solid">

        <div class="box-header with-border">
          <h3 class="box-title"> {{ __('user::labels.edit_profile') }}</h3>
        </div>

        {!! Form::model($user, ['route' => ['aksara-user-update', $user->id], 'class' => 'form-horizontal', 'files' => true ])!!}
        {{ method_field('PUT') }}
        @include('user::user._form')
        {!! Form::close() !!}
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-8">
      @if(has_capability('add-user-role'))
        <div class="box box-solid">
          <div class="box-header with-border">
            <h3 class="box-title"> {{ __('user::labels.add_user_role') }}</h3>
          </div>
          <div class="box-body">
            {!! Form::open([
              'route' => [ 'aksara-user-add-role', $user->id ],
              'role' => 'form',
              'class' => 'form-horizontal'
            ]) !!}

            <div class="form-group form-group--table  {!! $errors->has('role_id') ? 'has-error' : '' !!}">
              <label class="col-sm-3 col-xs-4 col-xxs-12 control-label">@lang('user::labels.role_name')</label>
              <div class="col-sm-9 col-xs-8 col-xxs-12">
                {!! Form::select('role_id', $select_role, null, ['class'=>'form-control']) !!}
                {!! $errors->first('role_id', '<p class="help-block">:message</p>') !!}
              </div>
            </div>
            <div class="box-footer">
              {!! Form::submit(__('user::labels.add_user_role'), ['class'=>'btn btn-md btn-brand alignright']) !!}
            </div>

            {!! Form::close() !!}

            <div class="content-table content-table--inside">
              <div class="content-header">
                <h2 class="page-title">{{ __('user::labels.role_list') }}</h2>
              </div>
              <div class="table-box">
                {!! $table->render() !!}
              </div>
            </div>
          </div>
        </div>
      @endif

    </div>
  </div>
@endsection
