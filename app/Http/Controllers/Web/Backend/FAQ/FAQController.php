<?php

namespace App\Http\Controllers\Web\Backend\FAQ;

use App\Models\Faq;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;

class FAQController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Faq::query())
                ->addIndexColumn()
                ->editColumn(
                    'is_active',
                    fn($row) =>
                    $row->is_active
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Inactive</span>'
                )
                ->addColumn('action', function ($row) {
                    return '
                        <a href="' . route('admin.faq.edit', $row->id) . '" class="btn btn-sm btn-outline-primary me-1"><i class="ti ti-pencil"></i></a>
                        <button data-id="' . $row->id . '" class="btn btn-sm btn-outline-danger delete-faq"><i class="ti ti-trash"></i></button>
                    ';
                })
                ->rawColumns(['is_active', 'action'])
                ->make(true);
        }

        return view('backend.layouts.faq.index');
    }

    public function create()
    {
        return view('backend.layouts.faq.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
        ]);

        Faq::create($request->only('question', 'answer', 'is_active'));

        return redirect()
            ->route('admin.faq.index')
            ->with('success', 'FAQ created successfully');
    }

    public function edit(Faq $faq)
    {
        return view('backend.layouts.faq.edit', compact('faq'));
    }

    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer'   => 'required|string',
        ]);

        $faq->update($request->only('question', 'answer', 'is_active'));

        return redirect()
            ->route('admin.faq.index')
            ->with('success', 'FAQ updated successfully');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();
        return response()->json(['success' => true]);
    }
}
