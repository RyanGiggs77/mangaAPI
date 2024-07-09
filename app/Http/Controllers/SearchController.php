<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;
use App\Models\Chapter;

class SearchController extends Controller
{
    public function searchChapter(Request $request)
    {
        $mangaTitle = $request->input('manga_title');
        $chapterNumber = $request->input('chapter_number');

        $manga = Manga::where('title', $mangaTitle)->first();
        if (!$manga) {
            return response()->json(['message' => 'Manga not found'], 404);
        }

        $chapter = Chapter::where('manga_id', $manga->id)
                          ->where('number', $chapterNumber)
                          ->first();

        if (!$chapter) {
            return response()->json(['message' => 'Chapter not found'], 404);
        }

        return response()->json($chapter);
    }
}
