<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::latest()->paginate(15);
        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'website' => 'nullable|url',
            'status' => 'nullable|in:active,inactive'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['status'] = $request->status === 'active' ? true : false;

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $data['logo'] = $this->uploadLogo($request->file('logo'));
        }

        Brand::create($data);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand created successfully.');
    }

    public function show(Brand $brand)
    {
        $brand->load(['products']);
        return view('admin.brands.show', compact('brand'));
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:brands,name,' . $brand->id,
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'website' => 'nullable|url',
            'status' => 'nullable|in:active,inactive'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        $data['status'] = $request->status === 'active' ? true : false;

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($brand->logo && !str_contains($brand->logo, 'placeholder')) {
                $oldLogoPath = str_replace('storage/', '', $brand->logo);
                $fullPath = storage_path('app/public/' . $oldLogoPath);
                if (file_exists($fullPath)) {
                    unlink($fullPath);
                }
            }
            $data['logo'] = $this->uploadLogo($request->file('logo'));
        }

        $brand->update($data);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand updated successfully.');
    }

    public function destroy(Brand $brand)
    {
        // Check if brand has products
        if ($brand->products()->count() > 0) {
            return redirect()->route('admin.brands.index')
                ->with('error', 'Cannot delete brand that has products assigned to it.');
        }

        // Delete logo
        if ($brand->logo && !str_contains($brand->logo, 'placeholder')) {
            $logoPath = str_replace('storage/', '', $brand->logo);
            $fullPath = storage_path('app/public/' . $logoPath);
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }

        $brand->delete();

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand deleted successfully.');
    }

    private function uploadLogo($logo, $directory = 'brands')
    {
        $path = storage_path('app/public/' . $directory);
        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $filename = time() . '_' . uniqid() . '.' . $logo->getClientOriginalExtension();
        $logo->move($path, $filename);
        
        return 'storage/' . $directory . '/' . $filename;
    }
}
