<div class="box box-solid box-form-sm">
  <div class="box-header with-border">
    <h3 class="box-title">@lang('user::labels.profile_picture')</h3>
  </div>
  <div class="box-body">
    <form method="POST" enctype="multipart/form-data" action="{{ route('aksara-user-set-profile-picture', $user->id) }}">
      {{ csrf_field() }}
      <div class="form-group {!! $errors->has('profile_picture') ? 'has-error' : '' !!}">
        {!! \HtmlInput::imageFile('profile_picture', get_user_meta($user->id, 'profile_picture')) !!}
        {!! $errors->first('profile_picture', '<p class="help-block">:message</p>') !!}
        <input type="submit" class="btn btn-md btn-brand alignright" value="Save">
      </div>
    </form>
  </div>
</div>
