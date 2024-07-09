<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;
use Illuminate\Support\Str;

class MangaController extends Controller
{
    public function index()
    {
        $mangas = Manga::all();
        return response()->json($mangas);
    }

    public function show($slug)
    {
        $manga = Manga::where('slug', $slug)->first();
        if (!$manga) {
            return response()->json(['message' => 'Manga not found'], 404);
        }
        return response()->json($manga);
    }

    public function store(Request $request)
    {
        $data = $request->all();

        // Generate slug
        $data['slug'] = Str::slug($data['title'], '-');

        // Upload cover image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'_'.$image->getClientOriginalName();
            $imagePath = 'images/' . $data['slug'];
            $image->storeAs($imagePath, $imageName, 'public');
            $data['image'] = $imagePath . '/' . $imageName;
        }

        $manga = Manga::create($data);
        return response()->json($manga, 201);
    }

    public function update(Request $request, $slug)
    {
        $manga = Manga::where('slug', $slug)->first();
        if (!$manga) {
            return response()->json(['message' => 'Manga not found'], 404);
        }

        $data = $request->all();

        // Update slug if title is changed
        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title'], '-');
        }

        // Update cover image if provided
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'_'.$image->getClientOriginalName();
            $imagePath = 'images/' . $data['slug'];
            $image->storeAs($imagePath, $imageName, 'public');
            $data['image'] = $imagePath . '/' . $imageName;
        }

        $manga->update($data);
        return response()->json($manga);
    }

    public function delete($slug)
    {
        $manga = Manga::where('slug', $slug)->first();
        if (!$manga) {
            return response()->json(['message' => 'Manga not found'], 404);
        }

        $manga->delete();
        return response()->json(['message' => 'Manga deleted successfully']);
    }
}
