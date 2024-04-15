@if($departments)
<!-- Home Departments Section Starts -->
<div class="section-icon-boxes-beta">
	<div class="container">
		<div class="row">
			<div class="col-md-12 col-sm-12">
				<div class="section-heading-style-alpha">
					<div class="section-heading">
						<h2>{{__('message.departments')}}</h2>
					</div>
					<div class="section-intro-text">
						<p>{{__('message.search_jobs_by_department')}}.</p>
					</div>
				</div>				
			</div>
		</div>
		<div class="row">
			@foreach($departments as $dept)
			<!-- Home Department Single Item Starts -->
			<div class="col-lg-3 col-md-12 col-sm-12">
				<a href="{{url('/')}}/jobs?page=1&sort=sort_newer&search=&departments={{encode($dept['department_id'])}}&companies=&filters={}">
				<div class="section-icon-boxes-beta-item" title="{{$dept['title'].' '.__('message.jobs')}}">
					<div class="section-icon-boxes-beta-item-heading">
						<h2>{{$dept['title']}}</h2>
					</div>
					<div class="section-icon-boxes-beta-item-highlight">
						<p>{{$dept['count']}} {{__('message.jobs')}}</p>
					</div>							
					<div class="section-icon-boxes-beta-item-icon">
						@php $thumb = departmentThumb($dept['image']); @endphp
						<img src="{{$thumb['image']}}" onerror="this.src='{{$thumb['error']}}'" />
					</div>
				</div>
				</a>
			</div>
			<!-- Home Department Single Item Ends -->
			@endforeach
		</div>
	</div>
</div>
<!-- Home Departments Section Ends -->
@endif