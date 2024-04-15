<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Admin\News;
use App\Models\Admin\NewsCategory;

class NewsController extends Controller
{    
    /**
     * View function to display all news page
     *
     * @return html/string
     */
    public function news(Request $request)
    {
        $news = News::getAll(true, setting('news_per_page'), $request);
        $data['news'] = $news;
        $data['page'] = $request->get('page');
        $data['search'] = $request->get('search');
        $data['selected_category'] = decode($request->get('category'));
        $data['pagination'] = $news->links('front'.viewPrfx().'partials.news-pagination');
        $data['categories'] = NewsCategory::getAll();
        $data['page_title'] = __('message.news');
        return view('front'.viewPrfx().'news.list', $data);
    }

    /**
     * View function to display news detail page
     *
     * @return html/string
     */
    public function newsDetail(Request $request, $slug = NULL)
    {
        $news = News::getNews('news.slug', $slug);
        if (!$news) {
            return redirect('news');
        }
        $data['news'] = $news;
        $data['page'] = $request->get('page');
        $data['search'] = $request->get('search');
        $data['selected_category'] = decode($request->get('category'));
        $data['categories'] = NewsCategory::getAll();
        $data['page_title'] = $news['title'];
        $data['page_keywords'] = $news['keywords'];
        $data['page_summary'] = $news['summary'];
        $data['image'] = route('uploads-view', $news['image']);
        return view('front'.viewPrfx().'news.detail', $data);
    }
}
