<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\SaveBrandRequest;
use App\Models\Shop\Brand;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BrandController extends AdminController
{
    public function __construct(private ImageService $imageService) {}

    public function index(): View
    {
        $search = request('search');
        $query = Brand::withCount('products')->orderBy('title');

        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        $brands = $query->get();

        return view('admin.brands.index', compact('brands', 'search'));
    }

    public function create(): View
    {
        return view('admin.brands.create');
    }

    public function store(SaveBrandRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo'] = $this->imageService->store($request->file('logo'), 'brands');
        } else {
            $data['logo'] = null;
        }

        Brand::create($data);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand created.');
    }

    public function edit(Brand $brand): View
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(SaveBrandRequest $request, Brand $brand): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            if ($brand->logo) {
                $this->imageService->delete($brand->logo, 'brands');
            }
            $data['logo'] = $this->imageService->store($request->file('logo'), 'brands');
        } else {
            unset($data['logo']);
        }

        $brand->update($data);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand updated.');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        if ($brand->products()->exists()) {
            return back()->with('error', 'Cannot delete: brand has products');
        }

        if ($brand->logo) {
            $this->imageService->delete($brand->logo, 'brands');
        }

        $brand->delete();

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand deleted.');
    }
}
