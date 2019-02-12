<select name="is_active" class="form-control">
  <option value='' {{ is_null($status_selected) ? 'selected="selected"' : '' }}>@lang('user::labels.all')</option>
  @foreach($statuses as $status => $desc)
    <option value="{{ $status }}" {{ (strval($status) == $status_selected) ? 'selected="selected"'  : '' }}>{{ $desc }}</option>
  @endforeach
</select>
<input type="submit" class="btn btn-secondary" value=@lang('aksara::tableview.labels.filter')>

