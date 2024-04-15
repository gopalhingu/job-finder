@if($news)
<!-- Home News Section Starts -->
<div class="section-blogs-alpha">
	<div class="container">
		<div class="row section-blogs-alpha-top">
			<div class="col-md-12 col-sm-12">
				<div class="section-heading-style-gamma">
					<div class="section-heading">
						<h2>{{__('message.news_announcements')}}</h2>
					</div>
					<div class="section-intro-text">
						<p>{{__('message.news_announcements_msg')}}</p>
					</div>
					<div class="section-intro-button">
						<a class="btn" href="{{route('front-news-list')}}">{{__('message.view_all')}}</a>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="position-relative"><div class="section-blogs-alpha-pattern pattern-triangle"></div></div>
			@foreach($news as $n)
			<div class="col-lg-4 col-md-12 col-sm-12">
				<div class="section-blogs-alpha-item">
					<div class="row align-items-center">
						<div class="col-md-12 col-sm-12">
							<div class="section-blogs-alpha-item-image">
								<div class="section-blogs-alpha-item-date">
									<i class="fa fa-calendar"></i> {{timeAgoByTimeStamp($n['created_at'])}}
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
			@endforeach
		</div>
	</div>
</div>
<!-- Home News Section Ends -->
@endif