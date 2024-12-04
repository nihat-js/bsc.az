<?php

namespace App\Http\Controllers;

use App\Models\CategoryFilter;
use App\Models\CategoryFilterOptions;
use App\Models\Product;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    public function all()
    {

        $minPrice = Product::where('category_id', $categoryId)->min('price'); // Min price for a specific category
        $maxPrice = Product::where('category_id', $categoryId)->max('price'); // Max price for a specific category


        $filter = CategoryFilter::where('category_id', $categoryId)->get();

        foreach($filter as $index => $f){
            if ($f == "options_manual"){
                $filter[$index]->options = CategoryFilterOptions::where('filter_id', $f->id)->get()->pluck("name");
            }else{
                $filter[$index]->options = Product::where('category_id', $categoryId)->distinct()->pluck($f->type);
            }
        }
        foreach($filter as $key => $value){
            $filter[$key]->options = FilterOption::where('filter_id', $value->id)->get();
        }
    }

    public function add(Request $request){

        $request->validate([ 
            'category_id' => 'required|integer',
            'type' => 'required|string',
            'name' => 'required|string',
            'options' => 'required|array',
        ]);

        $filter = new Filter();
        $filter->category_id = $request->category_id;
        $filter->type = $request->type;
        $filter->name = $request->name;
        $filter->save();

        foreach($request->options as $key => $value){
            $option = new FilterOption();
            $option->filter_id = $filter->id;
            $option->name = $value;
            $option->save();
        }
    }

    public function all()
}
