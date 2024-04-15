<div class="section-breadcrumb-delta my-0">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12">
                <h1>{{__('message.companies')}}</h1>
            </div>
            <div class="col-md-12">
                <ul>
                    <li><a href="/">{{__('message.home')}}</a></li>
                    <li>></li>
                    <li class="active"><a href="/companies">{{__('message.companies')}}</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="section-search-alpha">
    <div class="container">
        <div class="row">
            <div class="col-md-2 col-sm-12 col-xs-12"></div>
            <div class="col-md-8 col-sm-12 col-xs-12">
                <div class="section-search-alpha-container">
                    <div class="row">
                        <div class="col-md-4 col-sm-12 col-xs-12 section-search-alpha-container-input">
                            <input type="hidden" id="companies-page" value="{{$page}}">                            
                            <i class="fa-icon fa-solid fa-bullseye"></i> 
                            <input type="text" class="form-control companies-search-value" 
                                placeholder="{{__('message.keywords')}}" value="{{$search}}">
                        </div>
                        <div class="col-md-3 col-sm-12 col-xs-12 section-search-alpha-container-select">
                            <i class="fa-icon-tag fa-solid fa-industry"></i>
                            <select class="form-control select2" id="industry-dd">
                                <option value="" disabled selected>{{__('message.industry')}}</option>
                                @foreach($industry_dd as $item)
                                <option value="{{$item}}" class="sel-opt" {{sel($industry, $item)}}>{{$item}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-12 col-xs-12 section-search-alpha-container-select">
                            <i class="fa-icon-tag fa-solid fa-users"></i>
                            <select class="form-control select2" id="no-of-employees-dd">
                                <option value="" disabled selected>{{__('message.no_of_employees')}}</option>
                                @foreach($no_of_employees_dd as $item)
                                <option value="{{$item}}" class="sel-opt" {{sel($no_of_employees, $item)}}>{{$item}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-12 col-xs-12">
                            <button class="btn" type="button" id="companies-search-btn">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 col-sm-12 col-xs-12"></div>
        </div>
    </div>
</div>
<style type="text/css">
    .section-blogs-alpha-top {display: none;}
</style>