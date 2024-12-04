<?php

namespace App\Http\Controllers;

use App\Models\CategoryFilter;
use App\Models\CategoryTranslation;
use Illuminate\Http\Request;

class CategoryFilterController extends Controller
{
    public function all($slug){
        $categoryId = CategoryTranslation::where('slug', $slug)->first()->category_id;

        $minPrice = Product::where('category_id', $categoryId)->min('price'); // Min price for a specific category
        $maxPrice = Product::where('category_id', $categoryId)->max('price'); // Max price for a specific category


        $filterType = ["checkbox_automatic","checkbox_manual","price_range","select",];
        $filterType = "checkbox_manual";
        $filterType = "price_range";
        $filterType = "select";
        $filterType = "select";

        [
            "type" => "checkbox_automatic",
            "name" => "Color",
            "options" => [
                "Red",
                "Blue",
                "Green",
                "Yellow",
                "Black",
                "White",
            ]
        ],
        [
            "type" => "checkbox_manual",
            "name" => "Size",
            "options" => [
                "S",
                "M",
                "L",
                "XL",
                "XXL",
            ]
        ],
        [
            "type" => "price_range",
            "name" => "Price",
            "options" => [
                "min" => $minPrice,
                "max" => $maxPrice,
            ]
        ],
        [ 
            "type" => "select",
            "name" => "Brand",
            "options" => [
                "Nike",
                "Adidas",
                "Puma",
                "Reebok",
                "New Balance",
            ]
        ]

        

        

        $filters  = CategoryFilter::where('category_id', $categoryId)->get();


        foreach($filters as $index => $filter){
            if ($filter->type == "options_manual"){
                $filters[$index]->options = FilterOption::where('filter_id', $filter->id)->get();

            }else if ($filter->type == "options_automatic"){
                $filters[$index]->options = Product::groupBy('filter_option_id')->where('category_id', $categoryId)->get();
            }else {

            }
            

            // $filter->options = Product::groupBy('filter_option_id')->where('category_id', $categoryId)->get();
            $filters[$key]->options = FilterOption::where('filter_id', $value->id)->get();

        }
    }
}
