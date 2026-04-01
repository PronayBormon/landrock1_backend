<?php

namespace App\Http\Controllers\Web\Backend\Pages;

use Carbon\Carbon;
use App\Models\Page;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Contracts\DataTable;

class DynamicPagesController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Page::latest())
                ->addIndexColumn()

                ->addColumn('status', function ($row) {
                    $checked = $row->is_active ? 'checked' : '';
                    $id = 'switch_' . $row->id;

                    return '
                    <div>
                        <input type="checkbox"
                            id="' . $id . '"
                            data-id="' . $row->id . '"
                            data-switch="success"
                            ' . $checked . ' disabled>
                        <label for="' . $id . '"
                            data-on-label="Yes"
                            data-off-label="No"
                            class="mb-0 d-block"></label>
                    </div>
                ';
                })

                ->addColumn('action', function ($row) {
                    return '
                    <td class="text-muted">
                        <a href="' . route('admin.pages.edit', $row->id) . '" class="link-reset fs-20 p-1">
                            <i class="ti ti-pencil"></i>
                        </a>
                    </td>
                ';
                })


                // <a href="javascript:void(0);"
                //    class="link-reset fs-20 p-1 delete-page"
                //    data-id="' . $row->id . '">
                //     <i class="ti ti-trash"></i>
                // </a>

                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->format('d M Y');
                })

                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('backend.layouts.pages.index');
    }

    public function edit($id)
    {
        $page = Page::findOrFail($id);

        return view('backend.layouts.pages.edit', compact('page'));
    }

    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();

        return response()->json([
            'success' => true,
            'message' => 'Page deleted successfully'
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug'  => 'required|string|max:255|unique:pages,slug,' . $id,
            'content'  => 'required',
        ]);

        $page = Page::findOrFail($id);

        $page->update([
            'title'     => $request->title,
            'slug'      => $request->slug,
            'content'   => $request->content,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('admin.pages.index')
            ->with('t-success', 'Page updated successfully');
    }
}
