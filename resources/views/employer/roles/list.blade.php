<input type="hidden" id="selected_role_id" />
@if(empAllowedTo('create_role'))
<div class="row roles-create-btn-row">
    <div class="col-sm-12">
        <label>
        {{ __('message.create_new_role') }}
        </label>    
        <form id="employer_role_create_form">
            <div class="input-group">
                <input type="text" name="title" class="form-control" placeholder="{{__('message.enter_role_title')}}" />
                <span class="input-group-btn">
                <button type="submit" class="btn btn-info btn-blue btn-flat" id="employer_role_create_form_button">
                <i class="fa fa-plus"></i> {{ __('message.add_new') }}
                </button>
                </span>
            </div>
        </form>
    </div>
</div>
@endif
@if(empAllowedTo('view_roles'))
<div class="row roles-create-btn-row">
    <div class="col-sm-12">
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <label>
        {{ __('message.all_roles') }}
        </label>
        <ul class="roles-list">
            @php $cnt = 1; @endphp
            @foreach ($roles as $role)
            <li class="{{ $cnt == 1 ? 'selected' : '' }}" data-role_id="{{ encode($role['role_id']) }}">
                <small class="label label-primary label-blue">{{ $role['permissions_count'] }}</small>
                <span class="text role-title">{{ $role['title'] }}</span>
                <div class="tools">
                    @if(empAllowedTo('delete_role'))
                    <i class="far fa-trash-alt delete-role" data-id="{{ encode($role['role_id']) }}"></i>
                    @endif
                </div>
            </li>
            @php $cnt++; @endphp
            @endforeach
        </ul>
    </div>
</div>
<div class="row roles-create-btn-row">
    <div class="col-sm-12">
    </div>
</div>
@if(empAllowedTo('edit_role'))
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label>
            {{ __('message.add_remove_permission_for') }} <br />"<span class="for-edit-role"></span>"
            </label>
            <div class="edit-permissions-container">
                <select id='optgroup' multiple='multiple'>
                    @foreach($permissions as $key => $values)
                    <optgroup label='{{ $key }}'>
                        @foreach ($values as $k => $v)
                        <option value='{{ encode($v["id"]) }}' {{ $v['title'] == 1 ? 'selected="selected"' : '' }}>
                        {{ $v['title'] }}
                        </option>
                        @endforeach
                    </optgroup>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
@endif
@endif