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
                                    <th scope="col">{{__('message.title')}}</th>
                                    <th scope="col">{{__('message.job')}}</th>
                                    <th scope="col">{{__('message.allowed_time')}}</th>
                                    <th scope="col">{{__('message.questions')}}</th>
                                    <th scope="col">{{__('message.result')}}</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($quizes)
                                @foreach ($quizes as $k => $q)
                                @php $d = objToArr(json_decode($q['quiz_data'])); @endphp
                                <tr>
                                    <td>{{$k + 1}}</td>
                                    <td>{{ $q['quiz_title'] ? $q['quiz_title'] : '---' }}</td>
                                    <td>{{ $q['job_title'] ? $q['job_title'] : ''; }}</td>
                                    <td>{{ $q['allowed_time'] }} minutes</td>
                                    <td>{{ $q['total_questions'] }}</td>
                                    <td>
                                        @if($q['attempt'] > 0)
                                        {{ $q['total_questions'] != 0 ? round(($q['correct_answers']/$q['total_questions'])*100).'%' : '';  }}
                                        @else
                                        {{ __('message.n_a') }}
                                        @endif                                        
                                    </td>
                                    <td>
                                        <a href="{{ empUrl() }}account/quiz/{{ encode($q['candidate_quiz_id']) }}" class="view-btn">
                                            {{ __('message.attempt') }}
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="7">{{ __('message.no_quizes_found') }}</td>
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
