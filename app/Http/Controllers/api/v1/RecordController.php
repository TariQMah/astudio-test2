<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class RecordController extends Controller
{
    public function index(Request $request)
    {
        // $query = User::query();

        // if ($request->has('first_name')) {
        //     $query->where('first_name', 'like', '%' . $request->input('first_name') . '%');
        // }

        // if ($request->has('gender')) {
        //     $query->where('gender', $request->input('gender'));
        // }

        // if ($request->has('date_of_birth')) {
        //     $query->where('date_of_birth', $request->input('date_of_birth'));
        // }

        // $records = $query->get();

        // return response()->json($records);

        $query = User::query();

        // Get all query parameters
        $filters = $request->all();

        // Loop through each filter and apply it to the query
        // foreach ($filters as $key => $value) {
        //     if (!empty($value)) {
        //         $query->where($key, 'like', '%' . $value . '%');
        //     }
        // }

        foreach ($filters as $key => $value) {
            if (!empty($value)) {
                if (in_array($key, ['gender'])) {
                    // Use exact match for gender and date_of_birth
                    $query->where($key, $value);
                } else {
                    // Use partial match for other fields
                    $query->where($key, 'like', '%' . $value . '%');
                }
            }
        }

        // Get the filtered records
        $records = $query->get();
        $records->makeHidden(['password']);
        return response()->json($records);



    }
}
