@extends('employer.layouts.master')
@section('page-title'){{$page}}@endsection
@section('menu'){{$menu}}@endsection
@section('content')
<!-- Content Wrapper Starts -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="far fa-list-alt"></i> {{ __('message.quiz_designer') }}</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fas fa-tachometer-alt"></i> {{ __('message.home') }}</a></li>
            <li class="active"><i class="far fa-list-alt"></i> {{ __('message.quiz_designer') }}</li>
        </ol>
    </section>
    <!-- Main content Starts-->
    <section class="content">
        <!-- Main row Starts-->
        <div class="row">
            <!-- Questions Bank / Left Starts -->
            <section class="col-lg-4">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title quiz-page-question-bank-title">
                            <i class="fa fa-question-circle"></i> {{ __('message.questions_bank') }}
                            &nbsp;
                            @if(empAllowedTo('create_questions'))
                            <button type="button" class="btn btn-xs btn-primary btn-blue add-question-btn create-or-edit-question" 
                                title="{{__('message.add_question')}}" data-id="">
                            <i class="fa fa-plus"></i>
                            </button>
                            @endif
                        </h3>
                        <div class="btn-group pull-right quiz-page-question-bank-pagination">
                            <button type="button" class="btn btn-xs btn-primary btn-blue previos-button"><</button>
                            <button type="button" class="btn btn-xs btn-primary btn-blue disabled" id="pagination-container">
                            {{ $pagination }}
                            </button>
                            <button type="button" class="btn btn-xs btn-primary btn-blue next-button">></button>
                        </div>
                        <div class="btn-group pull-right quiz-page-question-bank-perpage-btn">
                            <button type="button" class="btn btn-xs btn-primary btn-blue dropdown-toggle" 
                                data-toggle="dropdown" aria-expanded="false">
                            <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="#" class="per_page" data-value="10">10 {{ __('message.per_page') }}</a></li>
                                <li><a href="#" class="per_page" data-value="25">25 {{ __('message.per_page') }}</a></li>
                                <li><a href="#" class="per_page" data-value="50">50 {{ __('message.per_page') }}</a></li>
                                <li><a href="#" class="per_page" data-value="200">200 {{ __('message.per_page') }}</a></li>
                            </ul>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group question-bank-question-search">
                                    <input type="hidden" id="questions_page" value="{{ $questions_page }}">
                                    <input type="hidden" id="questions_per_page"  value="{{ $questions_per_page }}">
                                    <input type="hidden" id="total_pages"  value="{{ $total_pages }}">
                                    <input type="text" class="form-control" placeholder="Search Questions" id="questions_search" value="{{ $questions_search }}">
                                    <span class="input-group-btn">
                                    <button type="button" class="btn btn-primary btn-blue btn-flat questions-search-button">
                                    <i class="fa fa-search"></i>
                                    </button>
                                    </span>
                                </div>
                                <div class="btn-group btn-sm pull-right question-bank-question-filter" title="More Filters">
                                    <button type="button" class="btn btn-primary btn-blue dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fa fa-filter"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <h4 class="question-bank-filters-heading">{{ __('message.filters') }}</h4>
                                            <form role="form">
                                                <div class="box-body">
                                                    <div class="form-group">
                                                        <label>{{ __('message.category') }}</label>
                                                        <select class="form-control" id="questions_category_id">
                                                            <option value="">{{ __('message.all') }}</option>
                                                            @if ($question_categories)
                                                            @foreach ($question_categories as $category)
                                                            <option value="{{ encode($category['question_category_id']) }}" {{ sel($questions_category_id, $category['question_category_id']) }}>
                                                            {{ $category['title'] }}
                                                            </option>
                                                            @endforeach
                                                            @endif
                                                        </select>
                                                        <br />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>{{ __('message.type') }}</label>
                                                        <select class="form-control" id="questions_type">
                                                            <option value="">{{ __('message.all') }}</option>
                                                            <option value="radio" {{ sel($questions_type, 'radio') }}>{{ __('message.radio_single_true') }}</option>
                                                            <option value="checkbox" {{ sel($questions_type, 'checkbox') }}>{{ __('message.checkbox_multiple_true') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="box-footer">
                                                    <button type="submit" class="btn btn-primary btn-blue btn-xs question-bank-question-filter-apply-btn">
                                                    {{ __('message.apply') }}
                                                    </button>
                                                </div>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        @if(empAllowedTo('view_questions'))
                        <ul class="quiz-list" id="questions-bank">
                            {!! $questions !!}
                        </ul>
                        @endif
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </section>
            <!-- Questions Bank / Left Ends -->
            <!-- Quizes / Right Starts -->
            <section class="col-lg-8">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title quiz-page-quizes-title">
                            <i class="far fa-list-alt"></i> {{ __('message.quizes') }}
                            &nbsp;
                            @if(empAllowedTo('create_quizes'))
                            <button type="button" class="btn btn-xs btn-primary btn-blue create-or-edit-quiz" 
                            title="{{_('message.add_quiz')}}" data-id="">
                            <i class="fa fa-plus"></i>
                            </button>
                            @endif
                            @if(empAllowedTo('clone_quizes'))
                            <button type="button" class="btn btn-xs btn-primary btn-blue clone-quiz" 
                            title="{{__('message.clone_quiz')}}">
                            <i class="fa fa-clone"></i>
                            </button>
                            @endif
                            @if(empAllowedTo('download_quizes'))
                            <button type="button" class="btn btn-xs btn-primary btn-blue download-quiz" 
                            title="{{_('message.download_quiz_as_pdf')}}">
                            <i class="fa fa-download"></i>
                            </button>
                            @endif
                        </h3>
                        <div class="row">
                            <div class="col-md-8 col-sm-12">
                                <div class="input-group quiz-page-quiz-select-group">
                                    <span class="input-group-btn">
                                    <button type="button" class="btn btn-default disabled btn-flat">{{ __('message.select_quiz') }}</button>
                                    </span>
                                    <select class="form-control select2 quiz-dropdown">
                                    </select>
                                    @if(empAllowedTo('edit_quizes'))
                                    <span class="input-group-btn">
                                    <button type="button" class="btn btn-primary btn-blue btn-flat create-or-edit-quiz" 
                                        id="edit-quiz"
                                        title="{{_('message.edit_selected_quiz')}}">
                                    <i class="far fa-edit"></i>
                                    </button>
                                    </span>
                                    @endif
                                    @if(empAllowedTo('delete_quizes'))
                                    <span class="input-group-btn">
                                    <button type="button" class="btn btn-danger btn-flat delete-quiz" 
                                        title="{{__('message.delete_selected_quiz')}}"
                                        id="delete-quiz">
                                    <i class="far fa-trash-alt"></i>
                                    </button>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="input-group quiz-page-quiz-select-group">
                                    <span class="input-group-btn">
                                    <button type="button" class="btn btn-default disabled btn-flat">{{ __('message.category') }}</button>
                                    </span>
                                    <select class="form-control select2" name="quiz_category_id" id="quizes_category_id">
                                        <option value="">{{ __('message.all') }}</option>
                                        @if ($quiz_categories)
                                        @foreach ($quiz_categories as $key => $category)
                                        <option value="{{ encode($category['quiz_category_id']) }}">
                                        {{ $category['title'] }}
                                        </option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        @if(empAllowedTo('view_quizes'))
                        <ul class="quiz-list" id="quiz-questions">
                        </ul>
                        @endif
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </section>
            <!-- Quizes / Right Ends -->
        </div>
        <!-- Main row Ends-->
    </section>
    <!-- Main content Ends-->
</div>
<!-- Content Wrapper Ends -->
<!-- Right Modal -->
<div class="modal right fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel2">Right Sidebar</h4>
            </div>
            <div class="modal-body">
                <p>This is the content</p>
            </div>
        </div>
        <!-- modal-content -->
    </div>
    <!-- modal-dialog -->
</div>
<!-- modal -->
<!-- Forms for questions section / left side -->
<form id="questions_form"></form>
<input type="hidden" id="nature" value="quiz" />
<!-- page script -->
@endsection
@section('page-scripts')
<script src="{{url('e-assets')}}/js/cf/question.js"></script>
<script src="{{url('e-assets')}}/js/cf/quiz.js"></script>
<script src="{{url('e-assets')}}/js/cf/quiz_question.js"></script>
@endsection