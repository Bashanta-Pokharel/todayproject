<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AttributeCreateRequest;
use App\Models\Attribute;
use Illuminate\Support\Facades\Auth;

class AttributeController extends Controller
{
    /**
     * Display a listing of attributes
     */
    public function index()
    {
        $data['records'] = Attribute::orderBy('title')->get();

        return view('admin.attribute.index', compact('data'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('admin.attribute.create');
    }

    /**
     * Store new attribute
     */
    public function store(AttributeCreateRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = Auth::id();

        Attribute::create($data);

        return redirect()
            ->route('admin.attribute.index')
            ->with('success', 'Attribute Created Successfully');
    }

    /**
     * Show single attribute
     */
    public function show(string $id)
    {
        $record = Attribute::find($id);

        if (! $record) {
            return redirect()->route('admin.attribute.index')
                ->with('error', 'Attribute Not Found');
        }

        return view('admin.attribute.show', compact('record'));
    }

    /**
     * Edit form
     */
    public function edit(string $id)
    {
        $record = Attribute::find($id);

        if (! $record) {
            return redirect()->route('admin.attribute.index')
                ->with('error', 'Attribute Not Found');
        }

        return view('admin.attribute.edit', compact('record'));
    }

    /**
     * Update attribute
     */
    public function update(AttributeCreateRequest $request, string $id)
    {
        $record = Attribute::find($id);

        if (! $record) {
            return redirect()->route('admin.attribute.index')
                ->with('error', 'Attribute Not Found');
        }

        $data = $request->validated();
        $data['updated_by'] = Auth::id();

        $record->update($data);

        return redirect()->route('admin.attribute.index')
            ->with('success', 'Attribute Updated Successfully');
    }

    /**
     * Delete attribute
     */
    public function destroy(string $id)
    {
        $record = Attribute::find($id);

        if ($record) {
            $record->delete();
        }

        return redirect()->route('admin.attribute.index')
            ->with('success', 'Attribute Deleted Successfully');
    }

    /**
     * Show trashed attributes
     */
    public function trashed()
    {
        $data['records'] = Attribute::onlyTrashed()->get();

        return view('admin.attribute.trashed', compact('data'));
    }

    /**
     * Restore attribute
     */
    public function restore($id)
    {
        $record = Attribute::onlyTrashed()->findOrFail($id);

        $record->restore();

        return redirect()
            ->route('admin.attribute.index')
            ->with('success', 'Attribute Restored Successfully');
    }

    /**
     * Permanent delete
     */
    public function forceDelete($id)
    {
        $record = Attribute::onlyTrashed()->findOrFail($id);

        $record->forceDelete();

        return redirect()
            ->route('admin.attribute.trashed')
            ->with('success', 'Attribute Permanently Deleted Successfully');
    }
}
