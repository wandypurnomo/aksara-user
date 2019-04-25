<div class="box-body">
  <div class="row">
    <div class="col-lg-6">
      <div class="form-group">
        <label class="col-sm-5 control-labe">@lang('user::labels.role_name')</label>
        <div class="col-sm-7">
          {!! Form::text('name', $role->name, ['class'=>'form-control']) !!}
          <input name="context" type="hidden" value="{{ $context }}">
        </div>
      </div>
    </div>
  </div>

  @action('aksara.role-capability.tabs', $context, $role, $capabilities)

  <div class="row">
    @foreach ( $capabilities as $id => $args)
      <div class="col-md-4">
        <table class="table table-bordered roleTable">
          <tr>
            <th class="no-sort selectbox bg-gray-light">{!! Form::checkbox('permissions[]', $id, ($role->permission_collection->contains($context.'.'.$id)) ? true : false) !!}</th>
            <th class="no-sort bg-gray-light">{{ $args['name'] }}</th>
          </tr>
          @foreach ( $args['capabilities'] as $childId => $childArgs )
            <tr>
              <td>{!! Form::checkbox('permissions[]', $childId , ($role->permission_collection->contains($context.'.'.$childId)) ? true : false, ['class'=>'dt-check']) !!}</td>
              <td>{{ $childArgs['name'] }}</td>
            </tr>
          @endforeach
        </table>
      </div>
    @endforeach
  </div>
</div>

