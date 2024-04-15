@extends('front.beta.layouts.master')

@section('breadcrumb')
@include('front'.viewPrfx().'partials.breadcrumb')
@endsection

@section('content')

<!-- Page Content Starts -->
<div class="section-general-page">
	<div class="container">
		<div class="col-lg-12 col-md-12 col-sm-12">
			{!! $page_description !!}
		</div>
	</div>
</div>
<!-- Page Content Ends -->

@endsection
