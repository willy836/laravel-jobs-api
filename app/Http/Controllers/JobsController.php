<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobRequest;
use App\Http\Resources\JobResource;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobsController extends Controller
{
    public function index()
    {
        // return Job::all(); // returns a collection. we need to send json response
        return JobResource::collection(Job::where('user_id', Auth::user()->id)->get());
    }

    public function store(StoreJobRequest $request)
    {
        $validatedData = $request->validated();

        $job = Job::create([
            'user_id' => Auth::user()->id,
            'company' => $validatedData['company'],
            'position' => $validatedData['position']
        ]);

        return new JobResource($job);   // return response()->json(['job' => $job], 201);
    }

    public function show(Job $job)
    {
        if(Auth::user()->id !== $job->user_id){
            return response()->json(['error' =>  'You are not authorized to make this request'], 403);
        }
        return new JobResource($job);
    }

    public function update(Request $request, Job $job)
    {

        if(Auth::user()->id !== $job->user_id){
            return response()->json(['error' => 'You are not authorized to update this job'], 403);
        }

        $job->update($request->all());

        return new JobResource($job);
    }

    public function destroy(Job $job)
    {
        if(Auth::user()->id !== $job->user_id){
            return response()->json(['error' => 'You are not authorized to delete this job'], 403);
        }

        $job->delete();

        return response(null, 204);
    }
}
