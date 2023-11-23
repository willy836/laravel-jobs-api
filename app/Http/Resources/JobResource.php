<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    /**
 * @OA\Schema(
 *     schema="JobResource",
 *     title="Job Resource",
 *     description="Schema for a job resource",
 *     @OA\Property(
 *         property="id",
 *         type="string",
 *         description="ID of the job"
 *     ),
 *     @OA\Property(
 *         property="company",
 *         type="string",
 *         description="Company of the job"
 *     ),
 *     @OA\Property(
 *         property="position",
 *         type="string",
 *         description="Position of the job"
 *     ),
 *     @OA\Property(
 *         property="status",
 *         type="string",
 *         description="Status of the job"
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="string",
 *         description="User ID associated with the job"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Creation timestamp of the job"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Last update timestamp of the job"
 *     ),
 * )
 */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string)$this->id,
            'company' => $this->company,
            'position' => $this->position,
            'status' => $this->status,
            'user_id' => (string)$this->user_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
