<!-- Home Banner Section Starts -->
<div class="banner-normal-section">
	<div class="container">
		<div class="row align-items-center h-100">
	    	<div class="col-md-6">
	    		<div class="banner-normal-section-left">
		    		<div class="row align-items-center h-100">
		    			<div class="col-md-12">
		    				<div class="banner-normal-section-left-top slide-down">
					    		{!! setting('home_banner_text') !!}
				    		</div>
			    		</div>
			    		@if(setting('home_banner_filters_display') == 'yes')
		    			<div class="col-md-12 p-0">
		    				<div class="banner-normal-section-left-bottom slide-up">
		    					<div class="banner-normal-search">
		    						<div class="row">
				    					<div class="col-md-5 col-sm-12 col-xs-12 banner-normal-search-input">
				    						<i class="fa-icon fa-solid fa-bullseye"></i> 
				    						<input type="text" class="form-control job-search-value" placeholder="{{__('message.keywords')}}">
				    					</div>
				    					<div class="col-md-6 col-sm-12 col-xs-12 banner-normal-search-select">
				    						<i class="fa-icon-tag fa fa-briefcase"></i>
						                    <select class="form-control select2" id="job-department-home">
						                        <option value="" disabled selected>{{__('message.department')}}</option>
						                		@if($departments_banner)
						                        @foreach($departments_banner as $db)
						                        <option value="{{encode($db['department_id'])}}" class="sel-opt">{{$db['title']}}</option>
						                        @endforeach
				    							@endif
						                    </select>
				    					</div>
				    					<div class="col-md-1 col-sm-12 col-xs-12">
				    						<button class="btn job-search-btn" type="button">
				    							<i class="fa fa-search"></i>
				    						</button>
				    					</div>
			    					</div>
		    					</div>
			            	</div>
			    		</div>
			    		@endif
		    		</div>
	    		</div>
	    		@if($search_logs && setting('home_banner_filters_display') == 'yes')
	    		<div class="row align-items-center h-100">
	    			<div class="col-md-12 p-0">
	    				<div class="banner-normal-section-left-tags-container slide-up">
	    					<p>{{__('message.most_searched')}}</p>
	    					@foreach($search_logs as $sl)
	    					<a href="#" class="keyword-history-search" data-value="{{$sl['title']}}">
	    					<div class="banner-normal-section-left-tag-item item">
	    						<div class="banner-normal-section-left-tag-item-dot"><i class="fa-icon-tag fa-solid fa-tag"></i></div>
	    						{{$sl['title']}}
	    					</div>
	    					</a>
	    					@endforeach
	    				</div>
	    			</div>
	    		</div>
	    		@endif
	    	</div>
	    	<div class="col-md-6">
	    		<div class="banner-normal-section-right-top pop-up">
	    			<img class="banner-normal" src="{{ setting('site_banner') }}" onerror="this.src='{{url('/g-assets').'/essentials/images/banner-default.png'}}'" />
	    		</div>
	    	</div>
    	</div>
	</div>
</div>
<!-- Home Banner Section Ends -->