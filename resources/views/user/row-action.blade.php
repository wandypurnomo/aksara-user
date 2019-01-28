@if(has_capability('edit-user'))
  <a href="{{ $edit_url }}" class="btn btn-xs btn-default"><i title="Edit" class="fa fa-pencil"></i> </a>
@endif
@if(has_capability('delete-user'))
  <a onclick='return confirm("{{ __('user::messages.confirm_delete_user') }}");' href="{{ $delete_url }}" class="btn btn-xs btn-default"><i title="Trash" class="fa fa-eye"></i></a>
@endif

