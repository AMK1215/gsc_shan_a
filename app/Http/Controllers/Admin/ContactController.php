<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\PromotionRequest;
use App\Models\Admin\Contact;
use App\Models\Admin\MediaType;
use App\Models\Admin\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = Contact::where('agent_id', Auth::id())->get();

        return view('admin.contact.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mediaTypes = MediaType::all();

        return view('admin.contact.create', compact('mediaTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactRequest $request)
    {
        if ($this->isExist($request->media_type_id)) {
            return redirect(route('admin.contact.index'))->with('error', 'Already Account Exist');
        }
        Contact::create([
            'account' => $request->account,
            'media_type_id' => $request->media_type_id,
            'agent_id' => Auth::id(),
        ]);

        return redirect(route('admin.contact.index'))->with('success', 'New Contact Added.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        $mediaTypes = MediaType::all();

        return view('admin.contact.edit', compact('contact', 'mediaTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact)
    {
        $contact->update([
            'account' => $request->account,
        ]);

        return redirect(route('admin.contact.index'))->with('success', 'Contact Updated.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->back()->with('success', 'Contact Deleted.');
    }

    private function isExist($type)
    {
        return Contact::where('agent_id', Auth::id())->where('media_type_id', $type)->first();
    }
}
