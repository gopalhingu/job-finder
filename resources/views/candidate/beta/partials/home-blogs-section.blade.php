@if($blogs)
<!-- Home News Section Starts -->
<div class="section-blogs-alpha">
	<div class="container">
		<div class="row section-blogs-alpha-top">
			<div class="col-md-12 col-sm-12">
				<div class="section-heading-style-gamma">
					<div class="section-heading">
						<h2>{{__('message.blogs')}}</h2>
					</div>
					<div class="section-intro-text">
						<p>{{__('message.home_blogs_msg')}}</p>
					</div>
					<div class="section-intro-button">
						<a class="btn" href="{{ empUrl() }}blogs">{{__('message.view_all')}}</a>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="position-relative"><div class="section-blogs-alpha-pattern pattern-triangle"></div></div>
			@foreach($blogs as $blog)
			<div class="col-lg-4 col-md-12 col-sm-12">
				<div class="section-blogs-alpha-item">
					<div class="row align-items-center">
						<div class="col-md-12 col-sm-12">
							<div class="section-blogs-alpha-item-image">
								<div class="section-blogs-alpha-item-date">
									<i class="fa fa-calendar"></i> {{timeAgoByTimeStamp($blog['created_at'])}}
								</div>
								@php $thumb = blogThumb($blog['image']); @endphp
								<img src="{{$thumb['image']}}" onerror="this.src='{{$thumb['error']}}'" />
							</div>							
							<div class="section-blogs-alpha-item-heading">
								<a href="{{ empUrl() }}blog/{{ encode($blog['blog_id']) }}"><div class="section-blogs-alpha-item-more" title="{{__('message.read_more')}}">&#62;</div></a>
								<a href="{{ empUrl() }}blog/{{ encode($blog['blog_id']) }}"><h2>{{$blog['title']}}</h2></a>
							</div>
							<div class="section-blogs-alpha-item-content">
								<p>{!! trimString($blog['description'], 100) !!}</p>
							</div>
							<div class="section-blogs-alpha-item-bottom">
								<div class="section-blogs-alpha-item-bottom-right">
									<span>{{$blog['category']}}</span>
								</div>
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