<div class="section-breadcrumb-delta my-0">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12">
                <h1>{{__('message.blogs')}}</h1>
            </div>
            <div class="col-md-12">
                <ul>
                    <li><a href="{{empUrl()}}">{{__('message.home')}}</a></li>
                    <li>></li>
                    <li class="active"><a href="{{empUrl()}}blogs">{{__('message.blogs')}}</a></li>
                    @if(isset($blog))
                    <li>></li>
                    <li class="active"><a href="{{empUrl()}}blogs/{{ encode($blog['blog_id']) }}">{{$blog['title']}}</a></li>
                    @endif
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
                            <input type="hidden" id="blog-page" value="{{$page}}">                            
                            <i class="fa-icon fa-solid fa-bullseye"></i> 
                            <input type="text" class="form-control blog-search-value" 
                                placeholder="{{__('message.keywords')}}" value="{{$search}}">
                        </div>
                        <div class="col-md-5 col-sm-12 col-xs-12 section-search-alpha-container-select">
                            <i class="fa-icon-tag fa-solid fa-tag"></i>
                            <select class="form-control select2" id="blog-category-dd">
                                <option value="" selected>{{__('message.all')}}</option>
                                @foreach($categories as $item)
                                <option value="{{encode($item['blog_category_id'])}}" class="sel-opt" {{sel($item['blog_category_id'], $categoriesSel) ? 'selected' : ''}}>{{$item['title']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 col-sm-12 col-xs-12">
                            <button class="btn blog-search-button" type="button" id="blog-search-btn">
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
