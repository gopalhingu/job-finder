@if($todos)
@foreach($todos as $todo)
@php $to_do_id = encode($todo['to_do_id']); @endphp
<li>
    <input type="checkbox" 
    data-id="{{ $to_do_id }}"
    class="minimal todo" {{ $todo['status'] == 1 ? 'checked="checked"' : '' }}>
    <span class="text">{{ $todo['title'] }}</span>
    <div class="tools">
        <i class="fa fa-edit create-or-edit-todo" data-id="{{ $to_do_id }}"></i>
        <i class="far fa-trash-alt delete-todo" data-id="{{ $to_do_id }}"></i>
    </div>
</li>
@endforeach
@endif