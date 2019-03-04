{{-- User Module Option --}}
<div class="box box-solid box-form-md">
  <div class="box-header">
    <h3 class="box-title">{{ __('user::menu.user') }}</h3>
  </div>
  <div class="box-body">
    <div class="form-group">
      <label class="col-sm-3 col-xs-4 col-xxs-12 control-label">@lang('option::global.enabled-register')</label>
      <div class="col-sm-9 col-xs-8 col-xxs-12">
        {!! Form::select('options[enabled_register]', ['1' => __('option::global.yes'), '0' => __('option::global.no')], @$site_options['enabled_register'], ['class'=>'form-control']) !!}
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-3 col-xs-4 col-xxs-12 control-label">@lang('user::labels.default-role')</label>
      <div class="col-sm-9 col-xs-8 col-xxs-12">
        {!! Form::select('options[default_role]', $roles , @$site_options['default_role'], ['class'=>'form-control', 'placeholder' => __('user::labels.no-default-role'), ]) !!}
      </div>
    </div>
  </div>
</div>
