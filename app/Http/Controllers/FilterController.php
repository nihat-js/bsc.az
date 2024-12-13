<?php

namespace App\Http\Controllers;

use App\Models\CategoryFilter;
use App\Models\CategoryFilterOptions;
use App\Models\CategorySpecs;
use App\Models\Product;
use App\Models\ProductSpec;
use Illuminate\Http\Request;

class FilterController extends Controller
{

    // public function bir(){
    //     $filter = 
    // }

    public function all($categoryId)
    {

        $minPrice = Product::where('category_id', $categoryId)->min('price'); // Min price for a specific category
        $maxPrice = Product::where('category_id', $categoryId)->max('price'); // Max price for a specific category



        $specs = CategorySpecs::where('category_id', $categoryId)
            ->where("show_in_filter", 1)
            ->with("options")
            ->get();

            return response()->json([
                'specs' => $specs,
                'minPrice' => $minPrice,
                'maxPrice' => $maxPrice,
            ]);


        foreach($specs as $spec){
            // $spec["options"] = Product::with("translations")-where("category_id",);

        }

        return response()->json([
            'filters' => $filters,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
        ]);

        foreach ($filters as $key => $value) {
            $filters[$key]->options = CategorySpecs::where('filter_id', $value->id)->get();
        }

        // $filter = CategoryFilter::where('category_id', $categoryId)->get();

        // foreach ($filter as $index => $f) {
        //     if ($f == "options_manual") {
        //         $filter[$index]->options = CategoryFilterOptions::where('filter_id', $f->id)->get()->pluck("name");
        //     } else {
        //         $filter[$index]->options = Product::where('category_id', $categoryId)->distinct()->pluck($f->type);
        //     }
        // }
        // foreach ($filter as $key => $value) {
        //     $filter[$key]->options = FilterOption::where('filter_id', $value->id)->get();
        // }
    }

    public function add(Request $request)
    {

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

        foreach ($request->options as $key => $value) {
            $option = new FilterOption();
            $option->filter_id = $filter->id;
            $option->name = $value;
            $option->save();
        }
    }

}
