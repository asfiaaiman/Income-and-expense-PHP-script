<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function index(): Response
    {
        $perPage = request('per_page', 10);
        $perPage = in_array($perPage, [10, 25, 50, 100]) ? (int) $perPage : 10;

        return Inertia::render('categories/Index', [
            'categories' => Category::with('parent:id,title')->paginate($perPage),
        ]);
    }

    public function testFlash(): RedirectResponse
    {
        return redirect()->route('categories.index')->with('success', 'Test flash message working!');
    }

    public function create(): Response
    {
        return Inertia::render('categories/Create', [
            'categories' => Category::where('parent_id', null)->where('is_active', true)->get(),
        ]);
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        try {
            $category = Category::create($request->only(['title', 'type', 'parent_id', 'is_active']));
            return redirect()->route('categories.index')->with('success', __('Category created successfully'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Failed to create category. Please try again.'));
        }
    }

    public function show(Category $category): Response
    {
        return Inertia::render('categories/Show', [
            'category' => $category->load('parent:id,title'),
        ]);
    }

    public function edit(Category $category): Response
    {
        return Inertia::render('categories/Edit', [
            'category' => $category->only(['id', 'title', 'type', 'parent_id', 'is_active']),
            'categories' => Category::where('parent_id', null)
                ->where('is_active', true)
                ->where('id', '!=', $category->id)
                ->get(),
        ]);
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        try {
            $category->update($request->only(['title', 'type', 'parent_id', 'is_active']));
            return redirect()->route('categories.index')->with('success', __('Category updated successfully'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Failed to update category. Please try again.'));
        }
    }

    public function destroy(Category $category): RedirectResponse
    {
        try {
            $category->delete();
            return redirect()->route('categories.index')->with('success', __('Category deleted successfully'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Failed to delete category. Please try again.'));
        }
    }
}
