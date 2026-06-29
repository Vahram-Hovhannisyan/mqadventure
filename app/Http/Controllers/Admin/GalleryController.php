<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;


class GalleryController extends Controller
{
    // Define available sections here — edit to match your site
    public const SECTIONS = [
        'general'  => 'Общее',
        'tours'    => 'Туры',
        'nature'   => 'Природа',
        'vehicles' => 'Техника',
        'events'   => 'Мероприятия',
    ];

    public function index()
    {
        $items    = GalleryItem::orderBy('section')->orderBy('sort_order')->get();
        $grouped  = $items->groupBy('section');
        $sections = self::SECTIONS;

        return view('admin.gallery.index', compact('items', 'grouped', 'sections'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'photos'   => ['required', 'array'],
            // Removed max:8192 — allow full 4K files (controlled by php.ini upload_max_filesize)
            'photos.*' => ['image'],
            'section'  => ['required', 'string', 'in:' . implode(',', array_keys(self::SECTIONS))],
        ]);

        $section = $request->input('section', 'general');
        $order   = GalleryItem::where('section', $section)->max('sort_order') ?? 0;

        foreach ($request->file('photos') as $file) {
            $path = $file->store('gallery', 'public');
            $thumbPath = 'gallery/thumbs/' . basename($path);

            // Создаём миниатюру через GD
            $sourcePath = Storage::disk('public')->path($path);
            $source = imagecreatefromstring(file_get_contents($sourcePath));

            $srcW = imagesx($source);
            $srcH = imagesy($source);

            // Считаем crop для cover 400x300
            $srcRatio   = $srcW / $srcH;
            $thumbRatio = 400 / 300;

            if ($srcRatio > $thumbRatio) {
                $cropH = $srcH;
                $cropW = (int) ($srcH * $thumbRatio);
                $cropX = (int) (($srcW - $cropW) / 2);
                $cropY = 0;
            } else {
                $cropW = $srcW;
                $cropH = (int) ($srcW / $thumbRatio);
                $cropX = 0;
                $cropY = (int) (($srcH - $cropH) / 2);
            }

            $thumb = imagecreatetruecolor(400, 300);
            imagecopyresampled($thumb, $source, 0, 0, $cropX, $cropY, 400, 300, $cropW, $cropH);

            // Сохраняем
            ob_start();
            imagejpeg($thumb, null, 85);
            $jpegData = ob_get_clean();

            imagedestroy($source);
            imagedestroy($thumb);

            Storage::disk('public')->put($thumbPath, $jpegData);

            GalleryItem::create([
                'path'       => $path,
                'thumb_path' => $thumbPath,
                'section'    => $section,
                'is_active'  => true,
                'sort_order' => ++$order,
            ]);
        }

        return back()->with('success', 'Фото загружены')->with('active_section', $section);
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'order'   => ['required', 'array'],
            'order.*' => ['integer', 'exists:gallery,id'],
        ]);

        foreach ($request->order as $position => $id) {
            GalleryItem::where('id', $id)->update(['sort_order' => $position]);
        }

        return response()->json(['ok' => true]);
    }

    public function destroy(GalleryItem $galleryItem)
    {
        Storage::disk('public')->delete([$galleryItem->path, $galleryItem->thumb_path]);
        $galleryItem->delete();

        return back()->with('success', 'Фото удалено');
    }
}
