<?php
// app/Http/Controllers/Admin/QuadController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quad;
use Illuminate\Http\Request;

class QuadController extends Controller
{
    public function index()
    {
        return view('admin.quads.index', ['quads' => Quad::latest()->get()]);
    }

    public function create()
    {
        return view('admin.quads.form', ['quad' => new Quad()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:4096',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('quads', 'public');
        }

        Quad::create($data);
        return redirect()->route('admin.quads.index')->with('success', 'Квадроцикл добавлен');
    }

    public function edit(Quad $quad)
    {
        return view('admin.quads.form', compact('quad'));
    }

    public function update(Request $request, Quad $quad)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:4096',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('quads', 'public');
        }

        $quad->update($data);
        return redirect()->route('admin.quads.index')->with('success', 'Сохранено');
    }

    public function destroy(Quad $quad)
    {
        $quad->delete();
        return back()->with('success', 'Удалено');
    }
}
