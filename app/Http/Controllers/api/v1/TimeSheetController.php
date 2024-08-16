<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timesheet;
use Validator;

class TimeSheetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $timesheets = Timesheet::with('user','project')->get();
        return response(['message' => 'all timesheets','timesheets' => $timesheets]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'task_name' => 'required',
            'date' => 'required',
            'hours' => 'required',
            'user_id' => 'required',
            'project_id' => 'required',
        ]);

        if($validator->fails()){
             return response($validator->errors(),"something went wrong!");   
        }
        Timesheet::create([
            'task_name' => $request->task_name,
            'date' => $request->date,
            'hours' => $request->hours,
            'user_id' => $request->user_id,
            'project_id' => $request->project_id,
        ]);
        return response(['message' => 'timesheet added'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $timesheet = Timesheet::with('user','project')->findOrFail($id);
        return response(['message' => 'single timesheet','timesheet' => $timesheet]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'task_name' => 'required',
            'date' => 'required',
            'hours' => 'required',
        ]);

        if($validator->fails()){
             return response($validator->errors(),"something went wrong!");   
        }
        $timesheet = Timesheet::findOrFail($id);
        $timesheet->update([
            'task_name' => $request->task_name,
            'date' => $request->date,
            'hours' => $request->hours,
            'user_id' => $request->user_id ? $request->user_id : $timesheet->user_id,
            'project_id' => $request->project_id ? $request->project_id : $timesheet->project_id,
        ]);
        return response(['message' => 'timesheet updated'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $timesheet = Timesheet::findOrFail($id);
        $timesheet->delete();
        return response(['message' => 'timesheet deleted']);
    }
}
