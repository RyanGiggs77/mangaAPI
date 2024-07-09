<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Models\Manga;

class ChapterController extends Controller
{
    public function index($manga_slug)
    {
        $manga = Manga::where('slug', $manga_slug)->first();
        if (!$manga) {
            return response()->json(['message' => 'Manga not found'], 404);
        }

        $chapters = $manga->chapters;
        return response()->json($chapters);
    }

    public function show($manga_slug, $chapter_number)
    {
        $manga = Manga::where('slug', $manga_slug)->first();
        if (!$manga) {
            return response()->json(['message' => 'Manga not found'], 404);
        }

        $chapter = Chapter::where('manga_id', $manga->id)
                          ->where('number', $chapter_number)
                          ->first();

        if (!$chapter) {
            return response()->json(['message' => 'Chapter not found'], 404);
        }

        return response()->json($chapter);
    }

    public function store(Request $request, $manga_slug)
    {
        $manga = Manga::where('slug', $manga_slug)->first();
        if (!$manga) {
            return response()->json(['message' => 'Manga not found'], 404);
        }

        $request->validate([
            'number' => 'required|integer',
            'title' => 'required|string|max:255',
        ]);

        $chapter = new Chapter();
        $chapter->manga_id = $manga->id;
        $chapter->number = $request->input('number');
        $chapter->title = $request->input('title');
        $chapter->save();

        return response()->json($chapter, 201);
    }


    public function update(Request $request, $manga_slug, $chapter_number)
    {
        $manga = Manga::where('slug', $manga_slug)->first();
        if (!$manga) {
            return response()->json(['message' => 'Manga not found'], 404);
        }

        $chapter = Chapter::where('manga_id', $manga->id)
                          ->where('number', $chapter_number)
                          ->first();

        if (!$chapter) {
            return response()->json(['message' => 'Chapter not found'], 404);
        }

        $request->validate([
            'title' => 'string|max:255',
        ]);

        $chapter->update($request->all());
        return response()->json($chapter);
    }

    // public function delete($id)
    // {
    //     Chapter::findOrFail($id)->delete();
    //     return response()->json(null, 204);
    // }

    public function delete($manga_slug, $chapter_number)
    {
        $manga = Manga::where('slug', $manga_slug)->first();
        if (!$manga) {
            return response()->json(['message' => 'Manga not found'], 404);
        }

        $chapter = Chapter::where('manga_id', $manga->id)
                          ->where('number', $chapter_number)
                          ->first();

        if (!$chapter) {
            return response()->json(['message' => 'Chapter not found'], 404);
        }

        $chapter->delete();
        return response()->json(['message' => 'Chapter deleted successfully']);
    }
    
}
