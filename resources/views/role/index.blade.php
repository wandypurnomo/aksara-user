@extends_backend('layouts.layout')

@section('breadcrumb')
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ route('admin.root') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">@lang('user::labels.all_roles')</li>
  </ol>
@endsection

@section('content-header')
<h2 class="page-title">@lang('user::labels.role_list')
      @if(has_capability('add-role'))
        <a href="{{ route('aksara-role-create') }}" class="btn btn-orange">@lang('user::labels.add_role')</a>
      @endif
    </h2>
@endsection

@section('content')

  <!-- /.content__head -->

  <div class="content__body">
    <div class="row">
      <div class="col-md-8">
        <form class="posts-filter clearfix">
          <div class="tablenav top clearfix">
            <div class="alignleft search-box">

              <input name="search" value="{{ $search }}" type="text" class="form-control">
              <input type="submit" class="btn btn-secondary" value=@lang('user::labels.search')>

            </div>
            <div class="tablenav-pages"><span class="displaying-num">{{ $total }} @if($total > 1 )items @else item @endif</span>
              {!! $roles->appends(['search' => $search])->links() !!}
            </div>
          </div>
          {{-- Start Table --}}
          <div class="table-box">
            <table class="table table-bordered table-striped table-main" cellspacing="0" width="100%">
              <thead>
                <tr>
                  <th class="text-center" width="20px">
                      <input type="checkbox">
                  </th>
                  <th>@lang('user::labels.role_name')</th>
                  @if(has_capability([ 'edit-role', 'delete-role' ]))
                    <th class="text-center" width="100px">@lang('user::labels.edit')</th>
                  @endif
                </tr>
              </thead>
              <tbody>
                @if($roles->count() > 0)
                  @foreach($roles as $role)
                    <tr>
                      <td class="text-center">
                          <input name="role_id[]" type="checkbox" value="{{ $role->id }}">
                      </td>
                      <td>{{ $role->name }}</td>
                      @if(has_capability([ 'edit-role', 'delete-role' ]))
                        <td class="text-center">
                          @if(has_capability('edit-role'))
                            <a href="{{ route('aksara-role-edit', $role->id) }}" class="btn btn-xs btn-default"><i title="Edit" class="fa fa-pencil" data-toggle="modal" data-target="#edit-komponen"></i> </a>
                          @endif
                          @if(has_capability('delete-role'))
                            <a onclick='{{ "return confirm('".__('user::messages.confirm_delete_role')."');" }}' href="{{ route('aksara-role-destroy', $role->id) }}" class="btn btn-xs btn-default"><i title="Trash" class="fa fa-trash"></i></a>
                          @endif
                        </td>
                      @endif
                    </tr>
                  @endforeach
                @endif
              </tbody>
            </table>
          </div>
          <div class="tablenav bottom clearfix">
            <div class="alignleft action bulk-action">
              <select name="apply" class="form-control">
                <option disabled selected>@lang('user::labels.bulk_action')</option>
                <option value='destroy'>@lang('user::labels.delete')</option>
              </select>
              <input name="bapply" type="submit" class="btn btn-secondary" value=@lang('user::labels.apply')>
            </div>
            <div class="tablenav-pages"><span class="displaying-num">{{ $total }} @if($total > 1 )items @else item @endif</span>
              {!! $roles->appends(['search' => $search])->links() !!}
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
