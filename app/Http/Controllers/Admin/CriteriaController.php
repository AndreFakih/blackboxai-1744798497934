<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Criteria;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{
    public function index()
    {
        $criteria = Criteria::all();
        return view('admin.criteria.index', compact('criteria'));
    }

    public function create()
    {
        return view('admin.criteria.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'weight' => 'required|numeric|min:0|max:1',
            'type' => 'required|in:cost,benefit',
        ]);

        Criteria::create($validated);

        return redirect()
            ->route('admin.criteria.index')
            ->with('success', 'Kriteria berhasil ditambahkan.');
    }

    public function edit(Criteria $criteria)
    {
        return view('admin.criteria.edit', compact('criteria'));
    }

    public function update(Request $request, Criteria $criteria)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'weight' => 'required|numeric|min:0|max:1',
            'type' => 'required|in:cost,benefit',
        ]);

        $criteria->update($validated);

        return redirect()
            ->route('admin.criteria.index')
            ->with('success', 'Kriteria berhasil diperbarui.');
    }

    public function destroy(Criteria $criteria)
    {
        $criteria->delete();

        return redirect()
            ->route('admin.criteria.index')
            ->with('success', 'Kriteria berhasil dihapus.');
    }

    public function show(Criteria $criteria)
    {
        return view('admin.criteria.show', compact('criteria'));
    }

    public function updateWeights(Request $request)
    {
        $request->validate([
            'weights' => 'required|array',
            'weights.*' => 'required|numeric|min:0|max:1',
        ]);

        $totalWeight = array_sum($request->weights);
        if ($totalWeight != 1) {
            return back()->withErrors(['weights' => 'Total bobot harus sama dengan 1 (100%)']);
        }

        foreach ($request->weights as $id => $weight) {
            Criteria::where('id', $id)->update(['weight' => $weight]);
        }

        return redirect()
            ->route('admin.criteria.index')
            ->with('success', 'Bobot kriteria berhasil diperbarui.');
    }
}
