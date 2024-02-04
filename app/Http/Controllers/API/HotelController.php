<?php

namespace App\Http\Controllers\API;
use App\Models\Hotel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class HotelController extends Controller
{
    public function index()
{
    $hotels = Hotel::all();
    return response()->json(['data' => $hotels], 200);

}
    public function show($id)
    {
        $hotels = Hotel::find($id);
        if (!$hotels) {
            return response()->json(['message' => 'Hotel not found'], 404);
        }
        return response()->json(['data' => $hotels], 200);
    }

    public function store(Request $request)
    {
        $hotels = new Hotel;
        $hotels->fill($request->all());
        $hotels->save();
        return response()->json(['message' => 'Hotel created'], 201);
    }

    public function update(Request $request, $id)
    {
        $hotels = Hotel::find($id);
        if (!$hotels) {
            return response()->json(['message' => 'Hotel not found'], 404);
        }
        $hotels->update($request->all());
        return response()->json(['message' => 'Hotel updated'], 200);
    }

    public function destroy($id)
    {
        $hotels = Hotel::find($id);
        if (!$hotels) {
            return response()->json(['message' => 'Hotel not found'], 404);
        }
        $hotels->delete();
        return response()->json(['message' => 'Hotel deleted'], 200);
    }
}




