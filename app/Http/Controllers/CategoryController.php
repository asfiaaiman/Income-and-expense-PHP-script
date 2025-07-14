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
        return Inertia::render('categories/Index');
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $category = Category::create($request->only(['title', 'parent_id', 'is_active']));

        return redirect()->route('categories/Index', $category)->withSuccess(__('Category created'));
    }
}
