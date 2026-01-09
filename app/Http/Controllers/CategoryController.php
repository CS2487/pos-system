<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Repositories\Contracts\CategoryRepositoryInterface;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryRepositoryInterface $categoryRepository
    ) {}

    public function index()
    {
        $categories = $this->categoryRepository->all();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(CategoryRequest $request)
    {
        $this->categoryRepository->create($request->validated());

        return redirect()->route('categories.index')
            ->with('success', 'Category created successfully.');
    }

    public function show(int $id)
    {
        $category = $this->categoryRepository->find($id);
        return view('categories.show', compact('category'));
    }

    public function edit(int $id)
    {
        $category = $this->categoryRepository->find($id);
        return view('categories.edit', compact('category'));
    }

    public function update(CategoryRequest $request, int $id)
    {
        $this->categoryRepository->update($id, $request->validated());

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    public function destroy(int $id)
    {
        $this->categoryRepository->delete($id);

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
