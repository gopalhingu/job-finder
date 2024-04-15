@extends('front.beta.layouts.master')

@section('breadcrumb')
@include('front.beta.partials.breadcrumb')
@endsection

@section('content')

<!-- Account Section Starts -->
<div class="section-account-alpha-container">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="section-account-alpha-navigation">
                    @include('front.beta.partials.account-sidebar')
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
                                        <th scope="col">{{__('message.title')}}</th>
                                        <th scope="col"><i class="fa fa-history"></i> {{__('message.qualifications')}}</th>
                                        <th scope="col"><i class="fa fa-graduation-cap"></i> {{__('message.experiences')}}</th>
                                        <th scope="col"><i class="fa fa-trophy"></i> {{__('message.achievements')}}</th>
                                        <th scope="col"><i class="fa fa-language"></i> {{__('message.skills')}}</th>
                                        <th scope="col"><i class="fa fa-language"></i> {{__('message.languages')}}</th>
                                        <th scope="col"><i class="fa fa-globe"></i> {{__('message.references')}}</th>
                                        <th scope="col">{{__('message.actions')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($resumes)
                                    @foreach ($resumes as $key => $resume)
                                    @php $id = encode($resume['resume_id']); @endphp
                                    @if($resume['type'] == 'detailed')                                
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td title="{{ $resume['title'] }}">{{ trimString($resume['title'], 23) }}</td>
                                        <td>{{ $resume['qualification'] }}</td>
                                        <td>{{ $resume['experience'] }}</td>
                                        <td>{{ $resume['achievement'] }}</td>
                                        <td>{{ $resume['reference'] }}</td>
                                        <td>{{ $resume['skills'] }}</td>
                                        <td>{{ $resume['languages'] }}</td>
                                        <td>
                                            <a href="{{ empUrl() }}account/resume/{{ $id }}">
                                                <i class="action-btn fa fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td>{{$key+1}}</td>
                                        <td title="{{ $resume['title'] }}">{{ trimString($resume['title'], 23) }}</td>
                                        <td colspan="6">
                                            {{ __('message.file') }}
                                            @if(strpos($resume['file'], 'pdf'))
                                            <i class="far fa-file-pdf resume-item-box-file"></i>
                                            @else
                                            <i class="far fa-file-word resume-item-box-file"></i>
                                            @endif                                        
                                        </td>
                                        <td>
                                            <a href="{{ empUrl() }}account/resume/{{ $id }}">
                                                <i class="action-btn fa fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="8">
                                            <p>{{ __('message.no_resumes_found') }}</p>
                                        </td>
                                    </tr>
                                    @endif                                
                                </tbody>
                            </table>
                        </div>
                        <!-- Resume List Table Ends -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <button type="submit" class="btn btn-primary btn-sm add-resume" title="{{__('message.add_new')}}">
                        <i class="fa fa-plus"></i>
                        </button>                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Account Section Ends -->

<div id="modal-beta" class="modal-beta modal fade modal-resume-create">
    <div class="modal-dialog">
        <div class="modal-content modal-body-container">
            <div class="modal-header p-0">              
                <h4 class="modal-title">{{ __('message.new_resume') }}</h4>
                <button type="button" class="close close-modal" data-bs-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <form id="resume_create_form">
                @csrf
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div class="form-group form-group-account">
                            <label for="">{{ __('message.title') }} *</label>
                            <input type="text" class="form-control" placeholder="Marketing Resume" name="title">
                        </div>
                        <br />
                    </div>
                    <div class="col-md-12 col-lg-12">
                        <div class="form-group form-group-account">
                            <label for="">{{ __('message.designation') }} *</label>
                            <input type="text" class="form-control" placeholder="Marketing Manager" name="designation">
                        </div>
                        <br />
                    </div>
                    <div class="col-md-12 col-lg-12">
                        <div class="form-group form-group-account">
                            <label for="">{{ __('message.type') }}</label>
                            <select class="form-control" name="type">
                                <option value="detailed">{{ __('message.detailed') }}</option>
                                <option value="document">{{ __('message.document') }}</option>
                            </select>
                        </div>
                        <br />
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 col-lg-12">
                        <div class="form-group form-group-account">
                            <button type="submit" class="btn btn-cf-general" title="Save" id="resume_create_form_button">
                            <i class="fa-regular fa-save"></i> {{ __('message.save') }}
                            </button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
