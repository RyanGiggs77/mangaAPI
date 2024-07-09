<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Manga;
use App\Models\Chapter;
use App\Models\Page;

class PageController extends Controller
{
    public function index($manga_slug, $chapter_number)
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

        $pages = $chapter->pages;
        return response()->json($pages);
    }

    public function show($manga_slug, $chapter_number, $page_number)
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

        $page = Page::where('chapter_id', $chapter->id)
                    ->where('number', $page_number)
                    ->first();

        if (!$page) {
            return response()->json(['message' => 'Page not found'], 404);
        }

        return response()->json($page);
    }

    public function store(Request $request, $manga_slug, $chapter_number)
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
            'page_number' => 'required|integer',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'_'.$image->getClientOriginalName();
            $imagePath = 'PAGE/' . $manga->slug . '/' . $chapter->number;
            $image->storeAs($imagePath, $imageName, 'public');

            $page = new Page();
            $page->chapter_id = $chapter->id;
            $page->number = $request->input('page_number');
            $page->image_path = $imagePath . '/' . $imageName;
            $page->save();

            return response()->json($page, 201);
        }

        return response()->json(['message' => 'Image upload failed'], 500);
    }

    public function update(Request $request, $manga_slug, $chapter_number, $page_number)
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

        $page = Page::where('chapter_id', $chapter->id)
                    ->where('number', $page_number)
                    ->first();

        if (!$page) {
            return response()->json(['message' => 'Page not found'], 404);
        }

        $request->validate([
            'page_number' => 'integer',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time().'_'.$image->getClientOriginalName();
            $imagePath = 'PAGE/' . $manga->slug . '/' . $chapter->number;
            $image->storeAs($imagePath, $imageName, 'public');
            $page->image_path = $imagePath . '/' . $imageName;
        }

        $page->update($request->except('image'));
        return response()->json($page);
    }

    public function delete($manga_slug, $chapter_number, $page_number)
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

        $page = Page::where('chapter_id', $chapter->id)
                    ->where('number', $page_number)
                    ->first();

        if (!$page) {
            return response()->json(['message' => 'Page not found'], 404);
        }

        $page->delete();
        return response()->json(['message' => 'Page deleted successfully']);
    }
}
