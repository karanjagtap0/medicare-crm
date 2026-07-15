<?php

namespace App\Http\Controllers\Api\Crm;

use App\Http\Controllers\Controller;
use App\Services\Crm\SupportTicketService;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function __construct(private SupportTicketService $supportTicketService)
    {
    }

    public function index(Request $request)
    {
        if (!$request->user()?->can('ticket.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $tickets = $this->supportTicketService->getTickets($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Support tickets retrieved successfully.',
            'data' => $tickets,
        ], 200);
    }

    public function show(Request $request, $id)
    {
        if (!$request->user()?->can('ticket.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $ticket = $this->supportTicketService->getTicket($id);

        return response()->json([
            'success' => true,
            'message' => 'Support ticket retrieved successfully.',
            'data' => $ticket,
        ], 200);
    }

    public function store(Request $request)
    {
        if (!$request->user()?->can('ticket.create')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|string|in:Low,Medium,High',
        ]);

        $ticket = $this->supportTicketService->createTicket($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Support ticket created successfully.',
            'data' => $ticket,
        ], 201);
    }

    public function updateStatus(Request $request, $id)
    {
        if (!$request->user()?->can('ticket.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'status' => 'required|string|in:Open,In Progress,Resolved,Closed',
        ]);

        $ticket = $this->supportTicketService->updateTicketStatus($id, $request->status);

        return response()->json([
            'success' => true,
            'message' => 'Support ticket status updated successfully.',
            'data' => $ticket,
        ], 200);
    }

    public function reply(Request $request, $id)
    {
        if (!$request->user()?->can('ticket.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        $reply = $this->supportTicketService->addReply($id, $request->only(['message']));

        return response()->json([
            'success' => true,
            'message' => 'Reply added successfully.',
            'data' => $reply,
        ], 201);
    }
}
