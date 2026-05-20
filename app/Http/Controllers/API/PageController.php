<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Page;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class PageController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $pages = Page::where('is_active', true)->get();

        return $this->successResponse('Page list fetched successfully', $pages);
    }

    public function showBySlug($slug)
    {
        $page = Page::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (! $page) {
            return $this->errorResponse('Page not found', 404);
        }

        return $this->successResponse('Page fetched successfully', $page);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:191',
            'slug' => 'required|string|max:191|unique:pages,slug',
            'content' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $page = Page::create($data);

        return $this->successResponse('Page created successfully', $page, 201);
    }

    public function update(Request $request, Page $page)
    {
        $data = $request->validate([
            'title' => 'sometimes|required|string|max:191',
            'slug' => 'sometimes|required|string|max:191|unique:pages,slug,' . $page->id,
            'content' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $page->update($data);

        return $this->successResponse('Page updated successfully', $page);
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return $this->successResponse('Page deleted successfully');
    }
    public function faqs(Request $request)
    {
        $faqs = Faq::latest()->paginate($request->items ?? 10);

        return $this->successResponse('Page deleted successfully', $faqs);
    }
}
