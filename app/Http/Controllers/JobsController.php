<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Http\Resources\JobResource;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobsController extends Controller
{
/**
 * @OA\Get(
 *     path="/api/jobs",
 *     tags={"Jobs"},
 *     description="Get all jobs",
 *     operationId="index",
 *     
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(ref="#/components/schemas/JobResource"),
 *         )  
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request",  
 *     ),
 *     security={
 *         {"api_key": {}}
 *     },
 * )
 */
    public function index()
    {
        return JobResource::collection(Job::where('user_id', Auth::user()->id)->get());
    }

/**
 * @OA\Post(
 *     path="/api/jobs",
 *     tags={"Jobs"},
 *     description="Create a new job",
 *     operationId="store",
 *     @OA\RequestBody(
 *         description="Input data format",
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 type="object",
 *                 @OA\Property(
 *                     property="position:",
 *                     description="Name of the job position eg Full-stack Developer",
 *                     type="string",
 *                 ),
 *                 @OA\Property(
 *                     property="company:",
 *                     description="Name of the company",
 *                     type="string"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Job created successfully",
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request",
 *         
 *     ),
 *     security={
 *         {"api_key": {}}
 *     },
 * )
 */

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

    /**
     * @OA\Get(
     *     path="/api/jobs/{jobId}",
     *     tags={"Jobs"},
     *     description="Get a single job",
     *     operationId="show",
     *     @OA\Parameter(
     *         name="jobId",
     *         in="path",
     *         description="ID of job that needs to be fetched",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Job fetched successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     security={
     *         {"api_key": {}}
     *     },
     * )
     */

    public function show(Job $job)
    {
        if(Auth::user()->id !== $job->user_id){
            return response()->json(['error' =>  'You are not authorized to make this request'], 403);
        }
        return new JobResource($job);
    }

    /**
     * @OA\Patch(
     *     path="/api/jobs/{jobId}",
     *     tags={"Jobs"},
     *     description="Updates a job that corresponds to the given id",
     *     operationId="update",
     *     @OA\Parameter(
     *         name="jobId",
     *         in="path",
     *         description="ID of job that needs to be updated",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Job updated successfully",
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request"
     *     ),
     *     security={
     *         {"api_key": {}}
     *     },
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="position:",
     *                     description="Updated job position",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="company:",
     *                     description="Updated company name",
     *                     type="string"
     *                 )
     *             )
     *         )
     *     )
     * )
     */

    public function update(UpdateJobRequest $request, Job $job)
    {
        $validatedData = $request->validated();

        if(Auth::user()->id !== $job->user_id){
            return response()->json(['error' => 'You are not authorized to update this job'], 403);
        }

        $job->update($validatedData);

        return new JobResource($job);
    }

    /**
     * @OA\Delete(
     *     path="/api/jobs/{jobId}",
     *     tags={"Jobs"},
     *     description="Deletes a job",
     *     operationId="destroy",
     *     @OA\Parameter(
     *         name="api_key",
     *         in="header",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="jobId",
     *         in="path",
     *         description="Job id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Success; no content",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Job not found",
     *     ),
     *     security={
     *         {"api_key": {}}
     *     },
     * )
     */

    public function destroy(Job $job)
    {
        if(Auth::user()->id !== $job->user_id){
            return response()->json(['error' => 'You are not authorized to delete this job'], 403);
        }

        $job->delete();

        return response(null, 204);
    }
}
