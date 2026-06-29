<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TourController extends Controller
{
    public function index()
    {
        $tours = Tour::orderBy('sort_order')->get();
        return view('admin.tours.index', compact('tours'));
    }

    public function create()
    {
        return view('admin.tours.form', ['tour' => new Tour()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['image'] = $this->handleMainImage($request, null);
        $data['route_points'] = $this->handleRoutePoints($request, []);

        Tour::create($data);
        return redirect()->route('admin.tours.index')->with('success', 'Շրջայցը ստեղծված է');
    }

    public function edit(Tour $tour)
    {
        return view('admin.tours.form', compact('tour'));
    }

    public function update(Request $request, Tour $tour)
    {
        $data = $this->validated($request, $tour->id);

        $newImage = $this->handleMainImage($request, $tour->image);
        if ($newImage !== null) $data['image'] = $newImage;

        $data['route_points'] = $this->handleRoutePoints($request, $tour->route_points ?? []);

        $tour->update($data);
        return redirect()->route('admin.tours.index')->with('success', 'Շրջայցը թարմացված է');
    }

    public function destroy(Tour $tour)
    {
        if ($tour->image) Storage::disk('public')->delete($tour->image);

        // Delete all point media
        foreach ($tour->route_points ?? [] as $point) {
            foreach ($point['media'] ?? [] as $m) {
                Storage::disk('public')->delete($m['path']);
            }
        }

        $tour->delete();
        return redirect()->route('admin.tours.index')->with('success', 'Շրջայցը հեռացված է');
    }

    // ── Private helpers ───────────────────────────────────────────────────

    private function handleMainImage(Request $request, ?string $existing): ?string
    {
        if ($request->hasFile('image')) {
            if ($existing) Storage::disk('public')->delete($existing);
            return $request->file('image')->store('tours', 'public');
        }
        return $existing;
    }

    /**
     * Processes route_points from the form.
     *
     * Form fields structure (arrays indexed by point order):
     *   route_points[0][lat], route_points[0][lng]
     *   route_points[0][label][hy|ru|en]
     *   route_points[0][media_existing]   — JSON array of already-saved media
     *   route_points[0][media_delete][]   — paths to delete
     *   route_points[0][media_new][]      — new uploaded files
     */
    private function handleRoutePoints(Request $request, array $existing): array
    {
        $rawPoints = $request->input('route_points', []);
        $files     = $request->file('route_points', []);

        // Index existing points by their original order for media lookup
        $existingByIndex = [];
        foreach ($existing as $i => $p) {
            $existingByIndex[$i] = $p;
        }

        $points = [];

        foreach ($rawPoints as $i => $raw) {
            $lat = (float) ($raw['lat'] ?? 0);
            $lng = (float) ($raw['lng'] ?? 0);

            if ($lat === 0.0 && $lng === 0.0) continue; // skip empty rows

            // Labels
            $label = [
                'hy' => $raw['label']['hy'] ?? '',
                'ru' => $raw['label']['ru'] ?? '',
                'en' => $raw['label']['en'] ?? '',
            ];

            // Media: start from existing saved media for this point
            $existingMedia = [];
            if (isset($raw['media_existing'])) {
                $existingMedia = json_decode($raw['media_existing'], true) ?? [];
            } elseif (isset($existingByIndex[$i]['media'])) {
                $existingMedia = $existingByIndex[$i]['media'];
            }

            // Delete marked media
            $toDelete = $raw['media_delete'] ?? [];
            $existingMedia = array_values(array_filter($existingMedia, function ($m) use ($toDelete) {
                if (in_array($m['path'], $toDelete)) {
                    Storage::disk('public')->delete($m['path']);
                    return false;
                }
                return true;
            }));

            // Upload new media
            $newFiles = $files[$i]['media_new'] ?? [];
            foreach ($newFiles as $file) {
                if (!$file->isValid()) continue;
                $mime = $file->getMimeType();
                $type = str_starts_with($mime, 'video/') ? 'video' : 'image';
                $path = $file->store('tours/points', 'public');
                $existingMedia[] = ['type' => $type, 'path' => $path];
            }

            $points[] = [
                'lat'   => $lat,
                'lng'   => $lng,
                'label' => $label,
                'media' => $existingMedia,
            ];
        }

        return $points;
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $request->validate([
            'slug'         => ['required', 'string', 'max:80', 'unique:tours,slug' . ($ignoreId ? ",$ignoreId" : '')],
            'badge_color'  => ['required', 'in:orange,green'],
            'duration_min' => ['required', 'integer', 'min:1'],
            'duration_max' => ['required', 'integer', 'min:1'],
            'people_min'   => ['required', 'integer', 'min:1'],
            'people_max'   => ['required', 'integer', 'min:1'],
            'price_from'   => ['required', 'integer', 'min:0'],
            'sort_order'   => ['nullable', 'integer'],
            'is_active'    => ['nullable', 'boolean'],
            'image'        => ['nullable', 'image', 'max:4096'],
            'name.hy'         => ['required', 'string', 'max:200'],
            'name.ru'         => ['required', 'string', 'max:200'],
            'name.en'         => ['required', 'string', 'max:200'],
            'badge.hy'        => ['nullable', 'string', 'max:60'],
            'badge.ru'        => ['nullable', 'string', 'max:60'],
            'badge.en'        => ['nullable', 'string', 'max:60'],
            'description.hy'  => ['required', 'string'],
            'description.ru'  => ['required', 'string'],
            'description.en'  => ['required', 'string'],
        ]);

        return [
            'slug'         => $request->slug,
            'badge_color'  => $request->badge_color,
            'duration_min' => $request->duration_min,
            'duration_max' => $request->duration_max,
            'people_min'   => $request->people_min,
            'people_max'   => $request->people_max,
            'price_from'   => $request->price_from,
            'sort_order'   => $request->sort_order ?? 0,
            'is_active'    => $request->boolean('is_active'),
            'name'         => $request->input('name'),
            'badge'        => $request->input('badge'),
            'description'  => $request->input('description'),
        ];
    }
}
