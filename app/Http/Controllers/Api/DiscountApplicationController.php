<?php

namespace App\Http\Controllers;

use App\Http\Resources\DiscountApplicationResource;
use App\Models\DiscountApplication;
use Illuminate\Http\Request;

class DiscountApplicationController extends Controller
{
    public function store(Request $request)
    {
        $application = DiscountApplication::create($request->all());
        return new DiscountApplicationResource($application->load('discount'));
    }

    public function destroy(DiscountApplication $discount)
    {
        $discount->delete();
        return response()->noContent();
    }
}
