@foreach($items as $item)
    <option value="{{$item['menu_id']}}">{{__($item['title'])}} ({{$item['menu_id']}})</option>
@endforeach