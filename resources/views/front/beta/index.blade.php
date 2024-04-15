@extends('front.beta.layouts.master')

@section('content')
        
    <input type="hidden" id="home-page" value="1" />
    @if(setting('home_banner') == 'yes')
        @if(setting('home_banner_type') == 'side_image')
            @include('front.beta.partials.home-banner-normal')
        @elseif(setting('home_banner_type') == 'full_background_image')
            @include('front.beta.partials.home-banner-absolute')
        @endif
    @endif

    @php 
        $orders = setting('home_display_order') ? objToArr(json_decode(setting('home_display_order'))) : array(); 
        $orders = array_flip($orders);
        ksort($orders);
    @endphp

    @foreach($orders as $section)
        @if(setting($section) == 'enabled')
        @include('front.beta.partials.'.str_replace('_', '-', $section))
        @endif
    @endforeach

@endsection
