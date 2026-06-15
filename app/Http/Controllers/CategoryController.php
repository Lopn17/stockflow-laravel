<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(
        private CategoryRepository $categories
    ) {}

    public function index(Request $request): View
    {
        $categories = $this->categories->paginated($request->only(['search']));

        return view('categories.index', [
            'categories' => $categories,
            'filters'    => $request->only(['search']),
        ]);
    }

    public function create(): View
    {
        $this->authorize('create', Category::class);
        return view('categories.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Category::class);

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
        ]);

        $this->categories->create($validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category created.');
    }

    public function edit(Category $category): View
    {
        $this->authorize('update', Category::class);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $this->authorize('update', Category::class);

        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255',
                              \Illuminate\Validation\Rule::unique('categories', 'name')->ignore($category)],
            'description' => ['nullable', 'string'],
        ]);

        $this->categories->update($category, $validated);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category updated.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorize('delete', Category::class);

        if ($category->products()->count() > 0) {
            return redirect()
                ->back()
                ->with('error', 'Cannot delete a category that has products.');
        }

        $this->categories->delete($category);

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category deleted.');
    }
}