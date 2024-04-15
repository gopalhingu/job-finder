@php $lang = frontLanguage(); @endphp
<li class="nav-item dropdown">
    <a class="nav-link flag-dropdown" href="#" data-bs-toggle="dropdown">
        <span class="parent-flag flag-icon flag-icon-{{$lang['flag']}}" data-parent-flag="flag-icon-{{$lang['flag']}}"></span> 
        <span class="parent-title">{{$lang['title']}}</span>
        <i class="fas fa-chevron-down"></i>
    </a>
    <ul class="dropdown-menu flag-dropdown-list flag-menu shadow">
        @foreach($languages as $language)
        <li>
            <a class="dropdown-item flag-item" href="#" 
                data-id="{{encode($language['language_id'])}}"
                data-flag="flag-icon-{{$language['flag']}}" 
                data-title="{{$language['title']}}">
                @if($language['display'] == 'both' || $language['display'] == 'only_flag')
                    <span class="flag-icon flag-icon-{{$language['flag']}}"></span> 
                @endif
                @if($language['display'] == 'both' || $language['display'] == 'only_title')
                    <span>{{$language['title']}}</span>
                @endif
            </a>
        </li>
        @endforeach
    </ul>
</li>
