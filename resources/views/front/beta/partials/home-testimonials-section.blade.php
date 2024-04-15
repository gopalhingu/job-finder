@if($testimonials)
<!-- Home testimonials Section Starts -->
<div class="section-carousel-alpha">
	<div class="container">
		<div class="position-relative"><div class="section-carousel-pattern pattern-dots"></div></div>
		<div class="row">
			<div class="col-md-12 col-sm-12">
				<div class="section-heading-style-gamma">
					<div class="section-heading">
						<h2>{{__('message.testimonials')}}</h2>
					</div>
					<div class="section-intro-text">
						<p>{{__('message.home_testimonials_msg')}}</p>
					</div>					
					<div class="section-intro-button">
						<button class="btn customPrevBtn"><</button>
						<button class="btn customNextBtn">></button>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12">
				<div class="item section-carousel-alpha-container">
					<div class="owl-carousel owl-theme testimonial-slider">
						@foreach($testimonials as $test) 
						<!-- Home Testimonial Single Item Starts -->
					    <div class="item section-carousel-alpha-container-item">
					    	<div class="item section-carousel-alpha-container-item-transparency"></div>
					    	<div class="item section-carousel-alpha-container-item-icon">
					    		<img src="{{ route('uploads-view', 'general/commas.png') }}" />
					    	</div>
					    	<div class="section-carousel-alpha-container-item-content">
					    		<p>{{$test['description']}}</p>
					    	</div>
					    	<div class="section-carousel-alpha-container-item-heading">
					    		<h3>{{$test['employer_name']}}</h3>
					    		<p>{{$test['company']}}</p>
					    	</div>
					    	<div class="section-carousel-alpha-container-item-rating">
								<ul class="star-rating-beta">
									<li class="{{ratingValue(1, $test['rating'])}}">
										<a href="javascript:void(0);"><div class="star-icon"></div></a>
									</li>
									<li class="{{ratingValue(2, $test['rating'])}}">
										<a href="javascript:void(0);"><div class="star-icon"></div></a>
									</li>
									<li class="{{ratingValue(3, $test['rating'])}}">
										<a href="javascript:void(0);"><div class="star-icon"></div></a>
									</li>
									<li class="{{ratingValue(4, $test['rating'])}}">
										<a href="javascript:void(0);"><div class="star-icon"></div></a>
									</li>
									<li class="{{ratingValue(5, $test['rating'])}}">
										<a href="javascript:void(0);"><div class="star-icon"></div></a>
									</li>
								</ul>
					    	</div>
					    	<div class="section-carousel-alpha-container-item-avatar">
					    		@php $thumb = employerThumb($test['logo']); @endphp
					    		<img src="{{ $thumb['image'] }}" onerror="this.src='{{$thumb['error']}}'" />
					    	</div>
					    </div>
					    <!-- Home Testimonial Single Item Ends -->
					    @endforeach
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endif
<!-- Home testimonials Section Ends -->