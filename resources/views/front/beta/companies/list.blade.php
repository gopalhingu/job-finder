@extends('front.beta.layouts.master')

@section('breadcrumb')
@include('front'.viewPrfx().'partials.companies-search')
@endsection

@section('content')

<!-- Companies List Page Starts -->
<div class="section-icon-boxes-alpha companies-page">
	<div class="container">

		<!-- Controls Section Starts -->
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="section-controls-alpha">
					<div class="row">
						<div class="col-lg-3 col-md-12 col-sm-12">
							<p>{{$pagination_overview}}</p>
						</div>
						<div class="col-lg-3 col-md-12 col-sm-12">
						</div>
						<div class="col-lg-3 col-md-12 col-sm-12">
						</div>
						<div class="col-lg-3 col-md-12 col-sm-12">
							<select class="companies-list-select-sort">
								<option value="">
									{{__('message.sort_by')}}
								</option>
                                <option value="sort_newer" {{sel(app('request')->input('sort'), 'sort_newer')}}>
                                    {{__('message.sort_by')}} {{__('message.newer')}}
                                </option>
                                <option value="sort_older" {{sel(app('request')->input('sort'), 'sort_older')}}>
                                    {{__('message.sort_by')}} {{__('message.older')}}
                                </option>
                                <option value="sort_recent" {{sel(app('request')->input('sort'), 'sort_recent')}}>
                                    {{__('message.sort_by').' '.__('message.recent').' '.__('message.jobs')}}
                                </option>
                                <option value="sort_most" {{sel(app('request')->input('sort'), 'sort_most')}}>
                                    {{__('message.sort_by').' '.__('message.most').' '.__('message.jobs')}}
                                </option>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Controls Section Ends -->

		@if(count($companies) > 0)
		<div class="row">
			@foreach($companies as $comp)
			<!-- Company single Item Starts -->
			<div class="col-lg-3 col-md-12 col-sm-12">
				<a href="{{frontEmpUrl($comp['slug'], $comp['separate_site'])}}">
				<div class="section-icon-boxes-alpha-item h-100">
					<div class="row align-items-center h-100">
						<div class="col-md-12 col-sm-12">
							<div class="section-icon-boxes-alpha-item-icon ">
								@php $thumb = employerThumb($comp['logo'], true); @endphp
								<img src="{{$thumb['image']}}" onerror="this.src='{{$thumb['error']}}'" />
							</div>
							<div class="section-icon-boxes-alpha-item-heading">
								<h2>{{$comp['company']}}</h2>
							</div>
							<div class="section-icon-boxes-alpha-item-content">
								<p title="{{$comp['country'] .($comp['city'] ? ','.$comp['city'] : '')}} | {{$comp['industry']}}">
									<i class="fa-solid fa-location-dot"></i> {{trimString($comp['country'] .($comp['city'] ? ','.$comp['city'] : ''), 15)}} | <i class="fa-solid fa-industry"></i> {{trimString($comp['industry'], 10)}}
								</p>
							</div>
							<div class="section-icon-boxes-alpha-item-highlight">
								<p>{{$comp['jobs_count']}} {{__('message.jobs')}}</p>
							</div>
						</div>
					</div>
				</div>
				</a>
			</div>
			<!-- Company single Item Ends -->
			@endforeach
		</div>
		@else
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="section-icon-boxes-alpha-item h-100">
					<p>{{__('message.no_record_found')}}</p>
				</div>
			</div>
		</div>
		@endif
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
	</div>
</div>
<!-- Companies List Page Ends -->

@endsection