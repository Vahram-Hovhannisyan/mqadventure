<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class PageController extends Controller
{
    private array $groups = [
        'general'  => ['site_title', 'admin_email', 'phone', 'whatsapp', 'viber', 'telegram', 'instagram', 'facebook', 'email', 'address'],
        'hero'     => ['hero_eyebrow', 'hero_desc', 'stat1_num', 'stat1_label', 'stat2_num', 'stat2_label', 'stat3_num', 'stat3_label', 'hero_bg_image'],
        'about'    => ['about_tag', 'about_title', 'about_text1', 'about_text2', 'about_image'],
        'contacts' => ['contact_title', 'contact_desc', 'map_embed_url'],
    ];

    private array $translatableKeys = [
        'hero_eyebrow', 'hero_desc',
        'stat1_label', 'stat2_label', 'stat3_label',
        'about_tag', 'about_title', 'about_text1', 'about_text2',
        'contact_title', 'contact_desc',
        'address',
    ];

    // key => storage subfolder
    private array $imageKeys = [
        'hero_bg_image' => 'settings/hero',
        'about_image'   => 'settings/about',
    ];

    public function index()
    {
        $settings = SiteSetting::all()->keyBy('key');
        $groups   = $this->groups;

        return view('admin.pages.index', compact('settings', 'groups'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hero_bg_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'about_image_file'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ]);

        // ── Image uploads / removals ──────────────────────────────────
        $allSettings = SiteSetting::all()->keyBy('key');

        foreach ($this->imageKeys as $key => $folder) {
            $fileField   = "{$key}_file";
            $removeField = "{$key}_remove";

            if ($request->hasFile($fileField)) {
                $oldPath = $allSettings->get($key)?->value['value'] ?? null;
                if ($oldPath) Storage::disk('public')->delete($oldPath);

                $path = $request->file($fileField)->store($folder, 'public');
                SiteSetting::set($key, ['value' => $path], 'image', $this->groupOf($key));

            } elseif ($request->input($removeField) === '1') {
                $oldPath = $allSettings->get($key)?->value['value'] ?? null;
                if ($oldPath) Storage::disk('public')->delete($oldPath);
                SiteSetting::where('key', $key)->delete();
                Cache::forget('site_settings');
            }
        }

        // ── Text fields ───────────────────────────────────────────────
        $skip = ['_token', '_method'];
        foreach (array_keys($this->imageKeys) as $key) {
            $skip[] = "{$key}_file";
            $skip[] = "{$key}_remove";
        }

        foreach ($request->except($skip) as $key => $rawValue) {
            if (array_key_exists($key, $this->imageKeys)) continue;

            if (in_array($key, $this->translatableKeys)) {
                if (is_array($rawValue)) {
                    SiteSetting::set($key, $rawValue, 'textarea', $this->groupOf($key));
                }
            } else {
                SiteSetting::set($key, ['value' => $rawValue], 'text', $this->groupOf($key));
            }
        }

        Cache::forget('site_settings');

        return back()->with('success', 'Настройки сохранены');
    }

    private function groupOf(string $key): string
    {
        foreach ($this->groups as $group => $keys) {
            if (in_array($key, $keys)) return $group;
        }
        return 'general';
    }
}
