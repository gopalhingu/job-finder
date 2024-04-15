<div class="dd" id="nestable">
    <ol class="dd-list">
    @foreach ($items as $item)
    <li class="dd-item" data-id="{{ $item['menu_id'] }}">
        <div class="dd-handle">
            {{ __($item['title']). ' ('.$item['menu_id'].')' }}
        </div>
        @if ($item['childs'])
        <ol class="dd-list">
        @foreach ($item['childs'] as $it)
            <li class="dd-item" data-id="{{ $it['menu_id'] }}">
                <div class="dd-handle">
                    {{ __($it['title']). ' ('.$it['menu_id'].')' }}
                </div>
                @if($it['childs'])
                    <ol class="dd-list">
                        @foreach ($it['childs'] as $i)
                            <li class="dd-item" data-id="{{ $i['menu_id'] }}">
                                <div class="dd-handle">
                                    {{ __($i['title']). ' ('.$it['menu_id'].')' }}
                                </div>
                            </li>
                        @endforeach
                    </ol>
                @endif
            </li>
        @endforeach
        </ol>
        @endif
    </li>
    @endforeach
    </ol>
</div>