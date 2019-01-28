<div class="box-body">
  <div class="form-group form-group--table {!! $errors->has('name') ? 'has-error' : '' !!}">
    <label class="col-sm-3 col-xs-4 col-xxs-12 control-label">@lang('user::labels.user_name')</label>
    <div class="col-sm-9 col-xs-8 col-xxs-12">
      {!! Form::text('name', $user->name, ['class'=>'form-control']) !!}
      {!! Form::hidden('id', $user->id, ['class'=>'form-control']) !!}
      {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
  </div>

  <div class="form-group form-group--table {!! $errors->has('email') ? 'has-error' : '' !!}">
    <label class="col-sm-3 col-xs-4 col-xxs-12 control-label">@lang('user::labels.email')</label>
    <div class="col-sm-9 col-xs-8 col-xxs-12">
      {!! Form::email('email', $user->email, ['class'=>'form-control']) !!}
      {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
  </div>

  <div class="form-group form-group--table {!! $errors->has('active') ? 'has-error' : '' !!}">
    <label class="col-sm-3 col-xs-4 col-xxs-12 control-label">@lang('user::labels.active')</label>
    <div class="col-sm-9 col-xs-8 col-xxs-12">
      {!! Form::select('active', [1 => 'Active', 0 => 'Non Active'], $user->active, ['class'=>'form-control']) !!}
      {!! $errors->first('active', '<p class="help-block">:message</p>') !!}
    </div>
  </div>

  <div class="form-group form-group--table {!! $errors->has('password') ? 'has-error' : '' !!}">
    <label class="col-sm-3 col-xs-4 col-xxs-12 control-label">@lang('user::labels.password')</label>
    <div class="col-sm-9 col-xs-8 col-xxs-12">
      {!! Form::password('password', ['class'=>'form-control']) !!}
      {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
    </div>
  </div>

  <div class="form-group form-group--table {!! $errors->has('password_confirmation') ? 'has-error' : '' !!}">
    <label class="col-sm-3 col-xs-4 col-xxs-12 control-label">@lang('user::labels.password_confirmation')</label>
    <div class="col-sm-9 col-xs-8 col-xxs-12">
      {!! Form::password('password_confirmation', ['class'=>'form-control']) !!}
      {!! $errors->first('password_confirmation', '<p class="help-block">:message</p>') !!}
    </div>
  </div>

  <div class="form-group form-group--table {!! $errors->has('profile_picture') ? 'has-error' : '' !!}">
    <label class="col-sm-3 col-xs-4 col-xxs-12 control-label">@lang('user::labels.profile_picture')</label>
    <div class="col-sm-9 col-xs-8 col-xxs-12">
      {!! \HtmlInput::imageFile('profile_picture', get_user_meta($user->id, 'profile_picture')) !!}
      {!! $errors->first('profile_picture', '<p class="help-block">:message</p>') !!}
    </div>
  </div>

  <div class="box-footer">
    {!! Form::submit($user->exists ? __('user::labels.update') : __('user::labels.create'), ['class'=>'btn btn-md btn-brand alignright']) !!}
  </div>
</div>

