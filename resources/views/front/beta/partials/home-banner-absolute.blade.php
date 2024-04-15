<!-- Home Banner Starts -->
<div class="banner-absolute-section">
	<div class="container h-100">
		<div class="banner-absolute-section-content h-100 pop-up-small">
    		<div class="row align-items-center h-100">
    			<div class="col-md-12">
    				<div class="banner-absolute-section-top">
			    		{!! setting('home_banner_text') !!}
		    		</div>
		    		@if(setting('home_banner_filters_display') == 'yes')
    				<div class="banner-absolute-section-bottom">
    					<div class="banner-absolute-search shadow">
    						<div class="row">
		    					<div class="col-md-5 col-sm-12 col-xs-12 banner-absolute-search-input">
		    						<i class="fa-icon fa-solid fa-bullseye"></i> 
		    						<input type="text" class="form-control job-search-value" placeholder="{{__('message.keywords')}}">
		    					</div>
		    					<div class="col-md-5 col-sm-12 col-xs-12 banner-absolute-search-select">
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
		    					<div class="col-md-2 col-sm-12 col-xs-12">
		    						<button class="btn job-search-btn" type="button"><i class="fa fa-search"></i></button>
		    					</div>
	    					</div>	    					
    					</div>
			    		@if($search_logs && setting('home_banner_filters_display') == 'yes')
			    		<div class="row align-items-center h-100">
			    			<div class="col-md-12 p-0">
			    				<div class="banner-absolute-tags-container slide-up">
			    					<p>{{__('message.most_searched')}}</p>
			    					@foreach($search_logs as $sl)
			    					<a href="#" class="keyword-history-search" data-value="{{$sl['title']}}">
			    					<div class="banner-absolute-section-left-tag-item item">
			    						<div class="banner-absolute-section-left-tag-item-dot"><i class="fa-icon-tag fa-solid fa-tag"></i></div>
			    						{{$sl['title']}}
			    					</div>
			    					</a>
			    					@endforeach
			    				</div>
			    			</div>
			    		</div>
			    		@endif

	            	</div>
	            	@endif
	    		</div>
    		</div>
		</div>
	</div>    	
	<div class="banner-absolute-section-bg"></div>
</div>
<!-- Home Banner Ends -->