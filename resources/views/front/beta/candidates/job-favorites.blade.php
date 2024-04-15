@extends('front'.viewPrfx().'layouts.master')

@section('breadcrumb')
@include('front'.viewPrfx().'partials.breadcrumb')
@endsection

@section('content')

<!-- Account Section Starts -->
<div class="section-account-alpha-container">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="section-account-alpha-navigation">
                    @include('front'.viewPrfx().'partials.account-sidebar')
                </div>
            </div>
            <div class="col-md-9">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <!-- Resume List Table Starts -->
                        <div class="table-responsive">
                            <table class="table section-account-alpha-table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">{{__('message.job')}}</th>
                                        <th scope="col">{{__('message.department')}}</th>
                                        <th scope="col">{{__('message.employer')}}</th>
                                        <th scope="col">{{__('message.applied_on')}} </th>
                                        <th scope="col">{{__('message.details')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($jobs)
                                    @foreach ($jobs as $key => $job)
                                    <tr>
                                        <td>{{$key + 1}}</td>
                                        <td>{{$job['title']}}</td>
                                        <td>{{$job['department'] ? $job['department'] : '---'}}</td>
                                        <td>{{$job['company']}}</td>
                                        <td>{{date('d M, Y', strtotime($job['favorited_on']))}}</td>
                                        <td>
                                            <a href="{{frontJobLink($job['employer_slug'], $job['separate_site'])}}{{$job['slug']}}"
                                                target="_blank" class="view-btn">
                                                {{__('message.view')}}
                                            </a>                                        
                                        </td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="6">{{__('message.no_record_found')}}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- Resume List Table Ends -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Account Section Ends -->

@endsection
