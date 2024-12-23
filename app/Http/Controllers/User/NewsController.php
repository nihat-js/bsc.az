<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function getBySlug(Request $request){

        $slug = $request->slug;

        $news = News::where('slug', $slug)->first();

        return view('user.news.show', compact('news'));

    }
}
