<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/start', function () {
    $ingredientsPath = storage_path('app/ingredients.json');
    $recipesPath = storage_path('app/recipes.json');

    $ingredients = [];
    $recipes = [];

    if (File::exists($ingredientsPath)) {
        $data = json_decode(File::get($ingredientsPath), true);
        $ingredients = $data['ingredients'] ?? $data ?? [];
    }

    if (File::exists($recipesPath)) {
        $data = json_decode(File::get($recipesPath), true);

        // поддержка формата {recipes: []} или просто []
        $recipes = $data['recipes'] ?? $data ?? [];
    }

    return view('start', [
        'ingredients' => $ingredients,
        'recipes' => $recipes
    ]);
});
