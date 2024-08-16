<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use Validator;
use DB;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::with('users','timesheets')->get();
        return response(['message' => 'all projects','projects' => $projects]);
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
            'name' => 'required',
            'department' => 'required',
            'start_date' => 'required',
            'status' => 'required',
            'user_ids' => 'required',
        ]);

        if($validator->fails()){
             return response($validator->errors(),"something went wrong!");   
        }
        $project  = Project::create([
            'name' => $request->name,
            'department' => $request->department,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ]);
        $userIds = $request->user_ids;

        foreach ($userIds as $userId) {
            DB::table('user_projects')->insert([
                'project_id' => $project->id,
                'user_id' => $userId,
            ]);
        }
        return response(['message' => 'project added']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $project = Project::with('users','timesheets')->findOrFail($id);
        return response(['message' => 'single project','project' => $project]);
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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'department' => 'required',
            'start_date' => 'required',
            'status' => 'required',
            'user_ids' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors(), 'message' => 'Something went wrong!'], 422);
        }

        $project = Project::findOrFail($id);

        $project->update([
            'name' => $request->name,
            'department' => $request->department,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ]);

        $userIds = $request->user_ids;

        DB::table('user_projects')->where('project_id', $project->id)->delete();

        foreach ($userIds as $userId) {
            DB::table('user_projects')->insert([
                'project_id' => $project->id,
                'user_id' => $userId,
            ]);
        }

        return response(['message' => 'Project updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
        return response(['message' => 'project deleted']);
    }
}
