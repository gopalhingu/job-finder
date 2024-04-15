@extends('front.beta.layouts.master')

@section('breadcrumb')
@include('front'.viewPrfx().'partials.news-search')
@endsection

@section('content')

<style type="text/css">
:root {
--blog-banner:url({{$image}});
}	
</style>

<!-- News Detail Page Starts -->
@if(setting('news_detail_image_full_width') == 'yes')
<div class="container-fluid section-blogs-detail-alpha-item-image-spreaded"></div>
@endif
<div class="section-blogs-detail-alpha">
	<div class="container">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="section-blogs-detail-alpha-item">
					<div class="row align-items-center">
						<div class="col-md-12 col-sm-12">
							<div class="section-blogs-detail-alpha-item-heading">
								<h2>{{$news['title']}}</h2>
							</div>
							@if(setting('news_detail_image_full_width') == 'no')
							<div class="section-blogs-detail-alpha-item-image"></div>
							@endif
							<div class="section-blogs-detail-alpha-item-detail-info">
								<div class="section-blogs-detail-alpha-item-date">
									<i class="fa fa-calendar"></i> {{timeAgoByTimeStamp($news['created_at'])}}
								</div>
								@if($news['category'])
								<div class="section-blogs-detail-alpha-item-detail-info-right">
									{{__('message.in')}} <span>{{$news['category']}}</span>
								</div>
								@endif
							</div>

							<div class="section-blogs-detail-alpha-item-content">
								{!!$news['description']!!}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- News Detail Page Ends -->

@endsection
