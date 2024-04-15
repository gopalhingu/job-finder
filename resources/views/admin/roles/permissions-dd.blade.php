<select class="duallistbox" multiple="multiple" id="permissions-multiselect">
    @foreach($permissions as $category => $permission)
    <optgroup label="{{$category}}">
        @foreach($permission as $perm)
        <option value="{{$perm['id']}}" {{$perm['selected'] == 1 ? 'selected="selected"' : ''}}>{{$perm['title']}}</option>
        @endforeach                                    
    </optgroup>
    @endforeach
</select>