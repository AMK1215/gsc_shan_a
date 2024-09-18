<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\BannerText;
use Illuminate\Http\Request;

class BannerTextController extends Controller
{
    public function index()
    {
        $bannerText = BannerText::latest()->first();

        return view('admin.bannerText.index', compact('bannerText'));
    }

    public function create()
    {
        return view('admin.bannerText.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required',
        ]);
        BannerText::create([
            'text' => $request->text,
        ]);

        return redirect(route('admin.bannerText.index'))->with('success', 'New Text Created Successfully.');
    }

    public function show(BannerText $bannerText)
    {
        return view('admin.bannerText.show', compact('bannerText'));
    }

    public function edit(BannerText $bannerText)
    {
        return view('admin.bannerText.edit', compact('bannerText'));
    }

    public function update(Request $request, BannerText $bannerText)
    {
        $request->validate([
            'text' => 'required',
        ]);

        $bannerText->update([
            'text' => $request->text,
        ]);

        return redirect(route('admin.bannerText.index'))->with('success', 'Banner Text Updated Successfully.');
    }

    public function destroy(BannerText $text)
    {
        $text->delete();

        return redirect()->back()->with('success', 'Marquee Text Deleted Successfully.');
    }
}
