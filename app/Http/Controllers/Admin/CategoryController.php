<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryCreateRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['records'] = Category::withCount('products')->orderBy('rank')->get();

        return view('admin.category.index', compact('data'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.category.create');
    }

    /**
     * Store new category
     */
    public function store(CategoryCreateRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = Auth::id();

        Category::create($data);

        return redirect()
            ->route('admin.category.index')
            ->with('success', 'Category Created Successfully');
    }

    /**
     * Show single category
     */
    public function show(string $id)
    {
        $record = Category::find($id);

        if (! $record) {
            return redirect()->route('admin.category.index')
                ->with('error', 'Category Not Found');
        }

        return view('admin.category.show', compact('record'));
    }

    /**
     * Edit form
     */
    public function edit(string $id)
    {
        $record = Category::find($id);

        if (! $record) {
            return redirect()->route('admin.category.index')
                ->with('error', 'Category Not Found');
        }

        return view('admin.category.edit', compact('record'));
    }

    /**
     * Update category
     */
    public function update(CategoryCreateRequest $request, string $id)
    {
        $record = Category::find($id);

        if (! $record) {
            return redirect()->route('admin.category.index')
                ->with('error', 'Category Not Found');
        }

        $data = $request->validated();
        $data['updated_by'] = Auth::id();

        $record->update($data);

        return redirect()->route('admin.category.index')
            ->with('success', 'Category Updated Successfully');
    }

    /**
     * Delete category
     */
    public function destroy(string $id)
    {
        $record = Category::find($id);

        if ($record) {
            $record->delete();
        }

        return redirect()->route('admin.category.index')
            ->with('success', 'Category Deleted Successfully');
    }

    public function trashed()
    {
        $data['records'] = Category::onlyTrashed()->get();

        return view('admin.category.trashed', compact('data'));
    }

    public function restore($id)
    {
        $record = Category::onlyTrashed()->findOrFail($id);
        $record->restore();

        return redirect()->route('admin.category.index')->with('success', 'Category Restored  Successfully');
    }

    public function forceDelete($id)
    {
        $record = Category::onlyTrashed()->findOrFail($id);
        $record->forceDelete();

        return redirect()->route('admin.category.trashed')->with('success', 'Category Permanently Deleted  Successfully');
    }
}
