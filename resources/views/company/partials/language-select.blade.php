@php $lang = companyLanguage(); @endphp
<select class="alpha-language-selector-select">
    @foreach($languages as $language)
    <option 
        {{sel($lang['flag'], $language['flag'])}}
        value="{{encode($language['language_id'])}}" 
        data-thumbnail="{{url('a-assets')}}/flags/{{$language['flag']}}.png">
        {{$language['title']}}
    </option>
    @endforeach
</select>
<div class="alpha-language-selector">
    <button class="alpha-language-selector-btn" value=""></button>
    <div class="alpha-language-selector-b">
        <ul class="alpha-language-selector-ul company-lang-select"></ul>
    </div>
</div>
