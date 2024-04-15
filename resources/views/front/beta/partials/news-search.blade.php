<div class="section-breadcrumb-delta my-0">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12">
                <h1>{{__('message.news_announcements')}}</h1>
            </div>
            <div class="col-md-12">
                <ul>
                    <li><a href="/">{{__('message.home')}}</a></li>
                    <li>></li>
                    <li class="active"><a href="/">{{__('message.news_announcements')}}</a></li>
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
                        <div class="col-md-5 col-sm-12 col-xs-12 section-search-alpha-container-input">
                            <input type="hidden" id="news-page" value="{{$page}}">                            
                            <i class="fa-icon fa-solid fa-bullseye"></i> 
                            <input type="text" class="form-control news-search-value" 
                                placeholder="{{__('message.keywords')}}" value="{{$search}}">
                        </div>
                        <div class="col-md-5 col-sm-12 col-xs-12 section-search-alpha-container-select">
                            <i class="fa-icon-tag fa-solid fa-tag"></i>
                            <select class="form-control select2" id="news-category-dd">
                                <option value="" disabled selected>{{__('message.category')}}</option>
                                @foreach($categories as $item)
                                <option value="{{encode($item['category_id'])}}" class="sel-opt" {{sel($item['category_id'], $selected_category)}}>{{$item['title']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-12 col-xs-12">
                            <button class="btn" type="button" id="news-search-btn">
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
