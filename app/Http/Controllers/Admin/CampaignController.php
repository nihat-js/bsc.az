<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Service;
use DB;
use Illuminate\Http\Request;
use Str;

class CampaignController extends Controller
{
    public function all()
    {

        $campaigns = Campaign::with("translations")->get();
        return response()->json([
            'status' => 'ok',
            'data' => $campaigns
        ]);
    }

    public function one(Request $request, $id)
    {
        $service = Campaign::with("translations")
        ->with("products")
        ->with("products.product")
        // ->with("products.product.translations")
        ->with("products.product.images")
        ->with("products.product.colors")
        ->with("products.product.images")
        ->findOrFail($id);
        return response()->json([
            'status' => 'ok',
            'data' => $service
        ]);
    }

    public function add(Request $request)
    {

        $request->validate([
            'name' => 'required|string|unique:campaigns,name',
            'text' => 'nullable|string',
            "description" => "nullable|string",
            'cover_image' => 'nullable|string',
            "start_date" => "required|date",
            "end_date" => "required|date",

            "translations" => "nullable|array",
            "translations.*.lang_code" => "required|string|exists:languages,code",
            "translations.*.name" => "required|string",
            "translations.*.description" => "nullable|string",
            "translations.*.text" => "nullable|string",
        ]);

        $request["slug"] = Str::slug($request->name);
        DB::beginTransaction();
        $campaign = Campaign::create($request->all());

        if ($request->has('translations')) {
            foreach ($request->translations as $translation) {
                $translation["slug"] = Str::slug($translation["name"]);
                $campaign->translations()->create($translation);
            }
        }
        DB::commit();
        return response()->json([
            'status' => 'ok',
            'message' => 'campaign added successfully',
            'data' => $campaign
        ]);
    }

    public function edit(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:campaigns,name,' . $id,
            'text' => 'nullable|string',
            "description" => "nullable|string",
            'cover_image' => 'nullable|string',
            "start_date" => "nullable|date",
            "end_date" => "nullable|date",

            "translations" => "nullable|array",
            "translations.*.lang_code" => "required|string|exists:languages,code",
            "translations.*.name" => "nullable|string",
            "translations.*.description" => "nullable|string",
            "translations.*.text" => "nullable|string",
        ]);

        if ($request->has('name')) {
            $request["slug"] = Str::slug($request->name);
        }

        $campaign = Campaign::with("translations")->findOrFail($id);
        DB::beginTransaction();
        $campaign->update($request->all());
        if ($request->has('translations')) {
            foreach ($request->translations as $translation) {
                if (@$translation["name"]) {
                    $translation["slug"] = Str::slug($translation["name"]);
                }
                $campaign->translations()->updateOrCreate([
                    "lang_code" => $translation['lang_code']
                ], $translation);
            }
        }
        DB::commit();

        return response()->json([
            'status' => 'ok',
            'message' => 'Campaign updated successfully',
            'data' => $campaign->load("translations")
        ]);
    }

    public function delete(Request $request, $id)
    {

        $campaign = Campaign::with("translations")->findOrFail($id);
        $campaign->translations()->delete();
        $campaign->delete();
        return response()->json([
            'status' => 'ok',
            'message' => 'Campaign deleted successfully',
            'data' => $campaign
        ]);
    }



    public function addProduct(Request $request, $id, )
    {
        $request->validate([
            'product_id' => 'required|exists:products,id|unique:campaign_products,product_id,NULL,id,campaign_id,' . $id,
        ]);
        $campaign = Campaign::findOrFail($id);
        $campaign->products()->create($request->all());
        return response()->json([
            'status' => 'ok',
            'message' => 'Product added to campaign successfully',
            'data' => $campaign->load("products")
        ]);
    }

    public function removeProduct(Request $request, $id){
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        $campaign = Campaign::findOrFail($id);
        $campaign->products()->where("product_id", $request->product_id)->delete();
        return response()->json([
            'status' => 'ok',
            'message' => 'Product removed from campaign successfully',
            'data' => $campaign
        ]);
    }
}
