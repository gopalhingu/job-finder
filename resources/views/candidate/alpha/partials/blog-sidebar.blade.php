<div class="blog-listing-left">
    <div class="input-group blog-listing-blog-search">
        <input type="text" class="form-control blog-search-value" placeholder="{{__('message.search_blogs')}}" 
            value="{{ $search }}">
        <span class="input-group-btn">
        <button type="button" class="btn btn-primary btn-blue btn-flat blog-search-button">
        <i class="fa fa-search"></i>
        </button>
        </span>
    </div>
    <hr />
    @if ($categories)
    <p class="blog-listing-heading">
        <span class="blog-listing-heading-text"><i class="fa fa-filter"></i> {{ __('message.categories') }}</span>
        <span class="blog-listing-heading-line"></span>
    </p>
    <ul class="blog-listing-filters-list">
        @foreach($categories as $key => $value)
        <li title="{{ $value['title'] }}">
            <input type="checkbox" class="category-check" {{ jobsCheckboxSel($categoriesSel, $value['blog_category_id']) }} value="{{ encode($value['blog_category_id']) }}" /> 
            {{ trimString($value['title']) }}
        </li>
        @endforeach
    </ul>
    @endif
</div>