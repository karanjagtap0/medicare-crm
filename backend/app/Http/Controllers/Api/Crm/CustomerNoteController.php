<?php

namespace App\Http\Controllers\Api\Crm;

use App\Http\Controllers\Controller;
use App\Services\Crm\CustomerNoteService;
use Illuminate\Http\Request;

class CustomerNoteController extends Controller
{
    public function __construct(private CustomerNoteService $customerNoteService)
    {
    }

    public function index(Request $request, $id)
    {
        if (!$request->user()?->can('note.view')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $notes = $this->customerNoteService->getCustomerNotes($id, $request->all());

        return response()->json([
            'success' => true,
            'message' => 'Customer notes retrieved successfully.',
            'data' => $notes,
        ], 200);
    }

    public function store(Request $request, $id)
    {
        if (!$request->user()?->can('note.create')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'note' => 'required|string',
        ]);

        $data = $request->only(['note']);
        $data['customer_id'] = $id;

        $note = $this->customerNoteService->createNote($data);

        return response()->json([
            'success' => true,
            'message' => 'Note created successfully.',
            'data' => $note,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        if (!$request->user()?->can('note.edit')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'note' => 'required|string',
        ]);

        $note = $this->customerNoteService->updateNote($id, $request->only(['note']));

        return response()->json([
            'success' => true,
            'message' => 'Note updated successfully.',
            'data' => $note,
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->user()?->can('note.delete')) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $this->customerNoteService->deleteNote($id);

        return response()->json([
            'success' => true,
            'message' => 'Note deleted successfully.',
        ], 200);
    }
}
