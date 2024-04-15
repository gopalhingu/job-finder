<style>
    table {color: #464646;}
    p, h2, h3 {padding:0px; margin: 0px; color: #464646;}
    .section-heading {background: #afafaf; padding: 4px; color: #0e0e0e; border-radius: 2px; font-size: 18px;}
</style>

@if ($resume)
    <table>
        <tr>
            <td width="20%">
                @php $image = candidateThumbForPdf($resume['image']); @endphp
                <img src="{{$image['image']}}" onerror="this.src='{{$image['error']}}'" height="70" />
            </td>
            <td width="80%">
                <h2>{{ $resume['first_name'].' '.$resume['last_name'] }}</h2>
                <p>
                    {{ ($resume['email'] ? $resume['email'] : '') 
                        . ($resume['phone1'] ? ", ".$resume['phone1'] : '')
                        . ($resume['phone2'] ? ", ".$resume['phone2'] : '') }}<br />
                    {{ ($resume['address'] ? " ".$resume['address'] : '')
                        . ($resume['city'] ? ", ".$resume['city'] : '')
                        . ($resume['state'] ? ", ".$resume['state'] : '')
                        . ($resume['country'] ? ", ".$resume['country'] : '')
                     }}
                </p>
            </td>
        </tr>
        <br />
    </table>
    <div>
        <h2 class="section-heading">{{ __('message.objective') }}</h2>
        <p>{{ $resume['objective'] }}</p><br />
    </div>
    <div>
        <h2 class="section-heading">{{ __('message.experiences') }}</h2>
        @if ($resume['experiences'])
        <div>
            <ul>
            @foreach ($resume['experiences'] as $experience)
            <li>
                <u><h3>{{ $experience['title'] }} - {{ $experience['company'] }}</h3></u>
                <p>({{ timeFormat($experience['from']) }} - {{ timeFormat($experience['to']) }})</p>
                <p>{!! nl2br($experience['description']) !!}</p>
            </li>
            <br />
            @endforeach
            </ul>
        </div>
        @else
        <p>{{ __('message.there_are_no_experiences') }}</p><br />
        @endif
    </div>
    <div>
        <h2 class="section-heading">{{ __('message.qualifications') }}</h2>
        @if ($resume['qualifications'])
        <div>
            <ul>
                @foreach ($resume['qualifications'] as $qualification)
                <li>
                    <u><h3>{{ $qualification['title'] }} - {{ $qualification['institution'] }}</h3></u>
                    <p>({{ timeFormat($qualification['from']) }} - {{ timeFormat($qualification['to']) }})</p>
                    <p>{{ $qualification['marks'] }} Out of {{ $qualification['out_of'] }}</p>
                </li>
                <br />
                @endforeach
            </ul>
        </div>
        @else
        <p>{{ __('message.there_are_no_qualifications') }}</p><br />
        @endif
    </div>
    <div>
        <h2 class="section-heading">{{ __('message.languages') }}</h2>
        @if($resume['languages'])
        <div>
            <ul>
                @foreach ($resume['languages'] as $language)
                <li>
                    <u><h3>{{ $language['title'] }} ({{ $language['proficiency'] }})</h3></u>
                </li>
                <br />
                @endforeach
            </ul>
        </div>
        @else
        <p>{{ __('message.there_are_no_languages') }}</p><br />
        @endif
    </div>
    <div>
        <h2 class="section-heading">{{ __('message.achievements') }}</h2>
        @if($resume['achievements'])
        <div>
            <ul>
                @foreach ($resume['achievements'] as $achievement)
                <li>
                    <u><h3>{{ $achievement['title'] }} ({{ $achievement['type'] }})</h3></u>
                    @if ($achievement['date'])
                    <p>({{ $achievement['date'] }})</p>
                    @endif
                    @if ($achievement['link'])
                    <p>({{ $achievement['link'] }})</p>
                    @endif
                    <p>{{ $achievement['description'] }}</p>
                </li>
                <br />
                @endforeach
            </ul>
        </div>
        @else
        <p>{{ __('message.there_are_no_achievements') }}</p><br />
        @endif
    </div>
    <div>
        <h2 class="section-heading">{{ __('message.references') }}</h2>
        @if ($resume['references'])
        <div>
            <ul>
                @foreach ($resume['references'] as $reference)
                <li>
                    <u><h3>{{ $reference['title'] }} ({{ $reference['relation'] }})</h3></u>
                    @if ($reference['company'])
                    <p>({{ $reference['company'] }})</p>
                    @endif
                    @if ($reference['phone'])
                    <p>({{ $reference['phone'] }})</p>
                    @endif
                    <p>({{ $reference['email'] }})</p>
                </li>
                @endforeach
            </ul>
        </div>
        @else
        <p>{{ __('message.there_are_no_references') }}</p><br />
        @endif
    </div>

<hr />
@else
@endif