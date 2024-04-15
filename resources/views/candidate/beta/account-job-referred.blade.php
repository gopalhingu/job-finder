@extends('candidate.beta.layouts.master')

@section('page-title'){{$page}}@endsection

@section('breadcrumb')
@include('candidate.beta.partials.breadcrumb')
@endsection

@section('content')

<div class="section-account-alpha-container">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="section-account-alpha-navigation">
                    @include('candidate.beta.partials.account-sidebar')
                </div>
            </div>
            <div class="col-md-9">

                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="table-responsive">
                        <table class="table section-account-alpha-table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">{{__('message.job')}}</th>
                                    <th scope="col">{{__('message.department')}}</th>
                                    <th scope="col">{{__('message.employer')}}</th>
                                    <th scope="col">{{__('message.referred_on')}} </th>
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
                                    <td>{{date('d M, Y', strtotime($job['referred_on']))}}</td>
                                    <td>
                                        <a target="_blank" href="{{ empUrlBySlug($job['employer_slug']) }}job/{{ $job['slug'] }}" class="view-btn">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
