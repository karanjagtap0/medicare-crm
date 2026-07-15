<?php

namespace App\Http\Controllers\Api\Crm;

use App\Http\Controllers\Controller;
use App\Services\Crm\CustomerCommunicationService;
use Illuminate\Http\Request;

class CustomerCommunicationController extends Controller
{
    public function __construct(private CustomerCommunicationService $customerCommunicationService)
    {
    }

    public function index(Request $request)
    {
        if (!$request->user()?->can('communication.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $communications = $this->customerCommunicationService->getCommunications($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Communications retrieved successfully.',
            'data' => $communications,
        ], 200);
    }

    public function store(Request $request)
    {
        if (!$request->user()?->can('communication.create')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'type' => 'required|string|in:Email,SMS,WhatsApp',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        $communication = $this->customerCommunicationService->createCommunication($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Communication logged successfully.',
            'data' => $communication,
        ], 201);
    }
}
