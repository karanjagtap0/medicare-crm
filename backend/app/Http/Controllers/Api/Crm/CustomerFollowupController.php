<?php

namespace App\Http\Controllers\Api\Crm;

use App\Http\Controllers\Controller;
use App\Services\Crm\CustomerFollowupService;
use Illuminate\Http\Request;

class CustomerFollowupController extends Controller
{
    public function __construct(private CustomerFollowupService $customerFollowupService)
    {
    }

    public function index(Request $request)
    {
        if (!$request->user()?->can('followup.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $followups = $this->customerFollowupService->getFollowups($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Follow-ups retrieved successfully.',
            'data' => $followups,
        ], 200);
    }

    public function store(Request $request)
    {
        if (!$request->user()?->can('followup.create')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'followup_date' => 'required|date',
            'type' => 'required|string|in:Call,Email,WhatsApp,Visit',
            'remarks' => 'nullable|string',
        ]);

        $followup = $this->customerFollowupService->createFollowup($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Follow-up created successfully.',
            'data' => $followup,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        if (!$request->user()?->can('followup.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'followup_date' => 'sometimes|required|date',
            'type' => 'sometimes|required|string|in:Call,Email,WhatsApp,Visit',
            'status' => 'sometimes|required|string|in:Pending,Completed,Cancelled',
            'remarks' => 'nullable|string',
        ]);

        $followup = $this->customerFollowupService->updateFollowup($id, $request->all());

        return response()->json([
            'success' => true,
            'message' => 'Follow-up updated successfully.',
            'data' => $followup,
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->user()?->can('followup.delete')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $this->customerFollowupService->deleteFollowup($id);

        return response()->json([
            'success' => true,
            'message' => 'Follow-up deleted successfully.',
        ], 200);
    }
}
