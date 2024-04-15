@php $lang = employerLanguage(); @endphp
<select class="alpha-language-selector-select">
    @foreach($languages as $language)
    <option 
        {{sel($lang['flag'], $language['flag'])}}
        data-id="{{encode($language['language_id'])}}"
        value="{{$language['flag']}}" 
        data-thumbnail="{{url('a-assets')}}/flags/{{$language['flag']}}.png">
        {{$language['title']}}
    </option>
    @endforeach
</select>
<div class="alpha-language-selector">
    <button class="alpha-language-selector-btn" value=""></button>
    <div class="alpha-language-selector-b"><ul class="alpha-language-selector-ul"></ul></div>
</div>

<style>
.alpha-language-selector {margin: 0px 10px; width: 90px;}
.alpha-language-selector-ul {padding-left: 0px; position: absolute; background: white; width: 90px; border: 1px solid #dbdcdd; border-radius: 4px; max-height: 200px; overflow: auto;}
.alpha-language-selector-ul li {list-style: none; padding-top: 5px; padding-bottom: 5px;}
.alpha-language-selector-ul li:hover {background-color: #F4F3F3;}
.alpha-language-selector-ul li img {margin: 5px;}
.alpha-language-selector-select {display: none;}
.alpha-language-selector-b {display: none; width: 100%; max-width: 350px; box-shadow: 0 6px 12px rgba(0,0,0,.175); 
    border: 1px solid rgba(0,0,0,.15); border-radius: 5px;}
.alpha-language-selector-btn {margin-top: 10px; width: 100%; max-width: 350px; height: 34px; border-radius: 5px; 
    background-color: #fff; border: 1px solid #ccc;}
.alpha-language-selector-btn li {list-style: none; float: left; padding-bottom: 0px;}
.alpha-language-selector-btn:hover li {margin-left: 0px;}
.alpha-language-selector-btn:hover {background-color: #F4F3F3; border: 1px solid transparent; box-shadow: inset 0 0px 0px 1px #ccc;}
.alpha-language-selector-btn:focus {outline:none;}
</style>
