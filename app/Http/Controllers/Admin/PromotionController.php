<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PromotionRequest;
use App\Models\Admin\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class PromotionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $promotions = Promotion::where('agent_id', Auth::id())->get();

        return view('admin.promotion.index', compact('promotions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.promotion.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PromotionRequest $request)
    {
        $image = $request->file('image');
        $ext = $image->getClientOriginalExtension();
        $filename = uniqid('promotion').'.'.$ext;
        $image->move(public_path('assets/img/promotions/'), $filename);

        Promotion::create([
            'image' => $filename,
            'title' => $request->title,
            'description' => $request->description,
            'agent_id' => Auth::id(),
        ]);

        return redirect(route('admin.promotion.index'))->with('success', 'New Promotion Added.');
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
    public function edit(Promotion $promotion)
    {
        return view('admin.promotion.edit', compact('promotion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PromotionRequest $request, Promotion $promotion)
    {
        $validatedData = $request->validated();
        if ($request->file('image')) {
            File::delete(public_path('assets/img/promotions/'.$promotion->image));
            $image = $request->file('image');
            $ext = $image->getClientOriginalExtension();
            $filename = uniqid('promotion').'.'.$ext;
            $image->move(public_path('assets/img/promotions/'), $filename);
            $validatedData['image'] = $filename;
        }
        $promotion->fill($validatedData);
        $promotion->save();

        return redirect(route('admin.promotion.index'))->with('success', 'Promotion Updated.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Promotion $promotion)
    {
        File::delete(public_path('assets/img/promotions/'.$promotion->image));
        $promotion->delete();

        return redirect()->back()->with('success', 'Promotion Deleted.');
    }
}
