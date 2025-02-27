<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return response()->json($categories);
    }

    public function store(CategoryRequest $request)
    {
        $existingCategory = Category::where('name', $request->name)->first();
        if ($existingCategory) {
            return response()->json(['error' => 'A category with this name already exists.'], 400);
        }

        $category = Category::create($request->validated());
        return response()->json(['message' => 'Category successfully created', 'category' => $category], 201);
    }

    public function show($id)
    {
        $category = Category::find($id);
    
        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }
    
        return response()->json($category);
    }
    
    public function update(CategoryRequest $request, $id)
    {
        $category = Category::find($id);
    
        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }
    
        $category->update($request->validated());
    
        return response()->json([
            'message' => 'Category successfully updated',
            'category' => $category
        ]);
    }

    public function destroy($id)
    {
        $category = Category::find($id);
    
        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }
    
        $category->delete();
    
        return response()->json(['message' => 'Category successfully deleted']);
    }
}