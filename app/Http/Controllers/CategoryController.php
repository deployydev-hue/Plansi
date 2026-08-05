<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display the logged-in user's categories.
     */
    public function index()
    {
        $categories = auth()
            ->user()
            ->categories()
            ->withCount('tasks')
            ->orderBy('name')
            ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Store a new category.
     */
    public function store(CategoryRequest $request)
    {
        $validated = $request->validated();

        auth()
            ->user()
            ->categories()
            ->create($validated);

        return redirect()
            ->route('categories.index')
            ->with(
                'success',
                'Category created successfully.'
            );
    }

    /**
     * Update an existing category.
     */
    public function update(
        CategoryRequest $request,
        Category $category
    ) {
        $this->ensureCategoryBelongsToUser($category);

        $validated = $request->validated();

        $category->update($validated);

        return redirect()
            ->route('categories.index')
            ->with(
                'success',
                'Category updated successfully.'
            );
    }

    /**
     * Delete a category.
     */
    public function destroy(Category $category)
    {
        $this->ensureCategoryBelongsToUser($category);

        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with(
                'success',
                'Category deleted successfully.'
            );
    }

    /**
     * Ensure the category belongs
     * to the logged-in user.
     */
    private function ensureCategoryBelongsToUser(
        Category $category
    ): void {
        abort_unless(
            $category->user_id === auth()->id(),
            403
        );
    }
}
