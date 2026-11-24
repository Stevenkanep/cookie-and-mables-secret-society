<?php

namespace App\Http\Controllers;

use App\Models\MembershipLevel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LevelController extends Controller
{
    public function index(): View
    {
        $levels = MembershipLevel::orderBy('fee')->get();

        return view('levels.index', compact('levels'));
    }

    public function create(): View
    {
        return view('levels.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', 'unique:membership_levels,name'],
            'fee' => ['required', 'numeric', 'min:0', 'max:99999999'],
            'validity_months' => ['required', 'integer', 'min:1', 'max:120'],
            'benefits' => ['nullable', 'string', 'max:1000'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        MembershipLevel::create($data);

        return redirect()->route('levels.index')
            ->with('success', 'Level added.');
    }

    public function edit(MembershipLevel $level): View
    {
        return view('levels.edit', compact('level'));
    }

    public function update(Request $request, MembershipLevel $level): RedirectResponse
    {
        $data = $request->validate([
            'name' => [
                'required', 'string', 'max:100',
                Rule::unique('membership_levels', 'name')->ignore($level->id),
            ],
            'fee' => ['required', 'numeric', 'min:0', 'max:99999999'],
            'validity_months' => ['required', 'integer', 'min:1', 'max:120'],
            'benefits' => ['nullable', 'string', 'max:1000'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $level->update($data);

        return redirect()->route('levels.index')
            ->with('success', 'Level updated.');
    }

    public function destroy(MembershipLevel $level): RedirectResponse
    {
        if ($level->members()->exists()) {
            return redirect()->route('levels.index')
                ->with('error', 'Cannot delete: this level still has members.');
        }

        $level->delete();

        return redirect()->route('levels.index')
            ->with('success', 'Level deleted.');
    }
}