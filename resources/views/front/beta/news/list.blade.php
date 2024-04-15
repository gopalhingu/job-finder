@extends('front.beta.layouts.master')

@section('breadcrumb')
@include('front'.viewPrfx().'partials.news-search')
@endsection

@section('content')

<!-- News List Page Starts -->
<div class="section-blogs-alpha">
	<div class="container">
		@if(count($news) > 0)
		<div class="row">
			@foreach($news as $n)
			<!-- News Single Item Starts -->
			<div class="col-lg-4 col-md-12 col-sm-12">
				<div class="section-blogs-alpha-item">
					<div class="row align-items-center">
						<div class="col-md-12 col-sm-12">
							<div class="section-blogs-alpha-item-image">
								<div class="section-blogs-alpha-item-date">
									<i class="fa-regular fa-calendar"></i> {{timeAgoByTimeStamp($n['created_at'])}}
								</div>
								@php $thumb = newsThumb($n['image']); @endphp
								<img src="{{$thumb['image']}}" onerror="this.src='{{$thumb['error']}}'" />
							</div>
							<div class="section-blogs-alpha-item-heading">
								<a href="{{route('front-news-detail', $n['slug'])}}"><div class="section-blogs-alpha-item-more" title="{{__('message.read_more')}}">&#62;</div></a>
								<a href="{{route('front-news-detail', $n['slug'])}}"><h2>{{$n['title']}}</h2></a>
							</div>
							<div class="section-blogs-alpha-item-content">
								<p>{{$n['summary']}}</p>
							</div>
							<div class="section-blogs-alpha-item-bottom">
								@if($n['category'])
								<div class="section-blogs-alpha-item-bottom-right">
									<span>{{$n['category']}}</span>
								</div>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- News Single Item Ends -->
			@endforeach
		</div>
		@else
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<p>{{__('message.no_results')}}</p>
			</div>
		</div>
		@endif

		<!-- News List Pagination Starts -->
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="section-pagination-alpha">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12">
							{!!$pagination!!}
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- News List Pagination Ends -->

	</div>
</div>
<!-- News List Page Ends -->

@endsection
