<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /**
     * Display products with filters.
     */
    public function index(Request $request)
    {
        $query = Product::query()->with('inventory');

        if ($request->filled('search')) {
            $search = trim($request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $products = $query
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        $categories = Product::query()
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('admin.products.index', compact(
            'products',
            'categories'
        ));
    }

    /**
     * Show product creation form.
     */
    public function create()
    {
        return view('admin.products.create');
    }

    /**
     * Store a new product and create its inventory.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            $this->rules()
        );

        $validated['is_active'] =
            $request->boolean('is_active');

        $validated['is_featured'] =
            $request->boolean('is_featured');

        // Wholesale fields are optional.
        $validated['wholesale_price'] =
            $validated['wholesale_price'] ?? null;

        $validated['wholesale_moq'] =
            $validated['wholesale_moq'] ?? 1;

        if ($request->hasFile('image')) {
            $validated['image'] = $request
                ->file('image')
                ->store('products', 'public');
        }

        $validated['number'] =
            $validated['sort_order'] ?? 0;

        $product = DB::transaction(function () use ($validated) {
            $product = Product::create($validated);

            ProductInventory::query()->create([
                'product_slug' => $product->slug,
                'stock' => 0,
                'low_stock_threshold' => 5,
            ]);

            return $product;
        });

        return redirect()
            ->route('admin.products.edit', $product)
            ->with(
                'success',
                'Product created successfully.'
            );
    }

    /**
     * Show product editing form.
     */
    public function edit(Product $product)
    {
        $product->load('inventory');

        return view(
            'admin.products.edit',
            compact('product')
        );
    }

    /**
     * Update an existing product.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate(
            $this->rules($product)
        );

        $validated['is_active'] =
            $request->boolean('is_active');

        $validated['is_featured'] =
            $request->boolean('is_featured');

        // Wholesale pricing and MOQ.
        $validated['wholesale_price'] =
            $validated['wholesale_price'] ?? null;

        $validated['wholesale_moq'] =
            $validated['wholesale_moq'] ?? 1;

        // Existing product slug remains immutable.
        unset($validated['slug']);

        if ($request->hasFile('image')) {
            $newImage = $request
                ->file('image')
                ->store('products', 'public');

            $oldImage = $product->image;

            $validated['image'] = $newImage;

            $product->update($validated);

            // Delete only previously uploaded product images.
            if (
                $oldImage &&
                str_starts_with($oldImage, 'products/')
            ) {
                Storage::disk('public')->delete($oldImage);
            }
        } else {
            $product->update($validated);
        }

        return redirect()
            ->route('admin.products.edit', $product)
            ->with(
                'success',
                'Product updated successfully.'
            );
    }

    /**
     * Enable or disable a product.
     */
    public function toggleStatus(Product $product)
    {
        $product->update([
            'is_active' => !$product->is_active,
        ]);

        return back()->with(
            'success',
            'Product status updated successfully.'
        );
    }

    /**
     * Validation rules for product creation and updates.
     */
    private function rules(?Product $product = null): array
    {
        return [
            'slug' => [
                $product ? 'sometimes' : 'required',
                'string',
                'max:150',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('products', 'slug')
                    ->ignore($product?->id),
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],

            // Normal customer price.
            'price' => [
                'required',
                'numeric',
                'min:0.01',
                'max:99999999.99',
                'decimal:0,2',
            ],

            // Optional B2B wholesale price.
            'wholesale_price' => [
                'nullable',
                'numeric',
                'min:0.01',
                'max:99999999.99',
                'decimal:0,2',
            ],

            
            // Minimum quantity for wholesale orders.
            // Must match the checkout quantity limit.
            'wholesale_moq' => [
                'nullable',
                'integer',
                'min:1',
                'max:50',
            ],


            'category' => [
                'required',
                'string',
                'max:100',
            ],

            'sort_order' => [
                'required',
                'integer',
                'min:0',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'is_active' => [
                'sometimes',
                'boolean',
            ],

            'is_featured' => [
                'sometimes',
                'boolean',
            ],
        ];
    }
}
