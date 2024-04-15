@if($companies)
<!-- Home Companies Section Starts -->
<div class="section-icon-boxes-alpha">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12">
				<div class="section-heading-style-alpha">
					<div class="section-heading">
						<h2>{{__('message.companies')}}</h2>
					</div>
					<div class="section-intro-text">
						<p>{{__('message.search_jobs_by_companies')}}</p>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			@foreach($companies as $comp)
			<!-- Home Companies Single Item Starts -->
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
			<!-- Home Companies Single Item ends -->
			@endforeach
		</div>
	</div>
</div>
<!-- Home Companies Section Ends -->
@endif