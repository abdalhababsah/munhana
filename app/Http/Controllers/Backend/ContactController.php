<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->get('status');
        $query = Contact::query()->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $contacts = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Contact::count(),
            'new' => Contact::where('status', 'new')->count(),
            'in_progress' => Contact::where('status', 'in_progress')->count(),
            'resolved' => Contact::where('status', 'resolved')->count(),
        ];

        return view('backend.contacts.index', compact('contacts', 'status', 'stats'));
    }

    public function show(Contact $contact): View
    {
        return view('backend.contacts.show', compact('contact'));
    }

    public function update(Request $request, Contact $contact): RedirectResponse
    {
        $data = $request->validate([
            'status' => 'required|in:new,in_progress,resolved',
        ]);

        $contact->update($data);

        return redirect()->route('backend.contacts.show', $contact)
            ->with('success', __('messages.updated_successfully'));
    }
}
