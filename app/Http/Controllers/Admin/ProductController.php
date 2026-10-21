<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PRODUCT LIST
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $view = $request->query('view') === 'archived'
            ? 'archived'
            : 'current';

        $query = $view === 'archived'
            ? Product::onlyTrashed()
            : Product::query();

        $query->with('inventory');

        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('slug', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where(
                'category',
                (string) $request->input('category')
            );
        }

        if (in_array(
            $request->input('status'),
            ['active', 'inactive'],
            true
        )) {
            $query->where(
                'is_active',
                $request->input('status') === 'active'
            );
        }

        $products = $query
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(15)
            ->withQueryString();

        $categories = Product::withTrashed()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $currentCount = Product::count();

        $archivedCount = Product::onlyTrashed()->count();

        return view('admin.products.index', compact(
            'products',
            'categories',
            'view',
            'currentCount',
            'archivedCount'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE PRODUCT
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $categories = Category::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view(
            'admin.products.create',
            compact('categories')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | STORE PRODUCT
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate(array_merge(
            $this->rules(),
            $this->mediaRules(),
            [
                'initial_stock' => [
                    'required',
                    'integer',
                    'min:0',
                    'max:1000000',
                ],
            ]
        ));

        $initialStock = (int) $validated['initial_stock'];

        unset($validated['initial_stock']);

        $this->removeMediaInputFields($validated);

        $validated['slug'] = Str::slug(
            $validated['slug'] ?? $validated['name']
        );

        $validated['is_active'] =
            $request->boolean('is_active');

        $validated['is_featured'] =
            $request->boolean('is_featured');

        $validated['show_premium_badge'] =
            $request->boolean('show_premium_badge');

        // SEO indexing
        $validated['seo_noindex'] =
            $request->boolean('seo_noindex');

        $validated['sort_order'] = (int) (
            $validated['sort_order'] ?? 0
        );

        $validated['wholesale_moq'] =
            $validated['wholesale_moq'] ?? null;

        $uploaded = [];

        try {
            /*
            |--------------------------------------------------------------
            | MAIN PRODUCT IMAGE
            |--------------------------------------------------------------
            */

            if ($request->hasFile('image')) {
                $validated['image'] = $this->saveUpload(
                    $request->file('image'),
                    'products',
                    $uploaded
                );
            } else {
                unset($validated['image']);
            }

            /*
            |--------------------------------------------------------------
            | PRODUCT BANNER
            |--------------------------------------------------------------
            */

            if ($request->hasFile('banner_image')) {
                $validated['banner_image'] = $this->saveUpload(
                    $request->file('banner_image'),
                    'products/banners',
                    $uploaded
                );
            }

            /*
            |--------------------------------------------------------------
            | SEO SOCIAL SHARING IMAGE
            |--------------------------------------------------------------
            */

            if ($request->hasFile('seo_image')) {
                $validated['seo_image'] = $this->saveUpload(
                    $request->file('seo_image'),
                    'products/seo',
                    $uploaded
                );
            }

            /*
            |--------------------------------------------------------------
            | PRODUCT GALLERY
            |--------------------------------------------------------------
            */

            $galleryPaths = $this->saveGalleryUploads(
                $request,
                $uploaded
            );

            $primary = (string) $request->input(
                'primary_image',
                'main'
            );

            /*
            |--------------------------------------------------------------
            | DATABASE TRANSACTION
            |--------------------------------------------------------------
            */

            DB::transaction(function () use (
                $validated,
                $initialStock,
                $galleryPaths,
                $primary
            ) {
                $product = Product::create($validated);

                ProductInventory::create([
                    'product_slug' => $product->slug,
                    'stock' => $initialStock,
                    'low_stock_threshold' => 5,
                ]);

                foreach ($galleryPaths as $index => $path) {
                    $product->galleryImages()->create([
                        'image' => $path,
                        'sort_order' => $index,
                    ]);
                }

                if (preg_match(
                    '/^new:(\d+)$/',
                    $primary,
                    $matches
                )) {
                    $index = (int) $matches[1];

                    if (isset($galleryPaths[$index])) {
                        $product->update([
                            'image' => $galleryPaths[$index],
                        ]);

                        if (
                            !empty($validated['image'])
                            && $validated['image'] !== $galleryPaths[$index]
                        ) {
                            $product->galleryImages()->create([
                                'image' => $validated['image'],
                                'sort_order' => count($galleryPaths),
                            ]);
                        }
                    }
                }
            });
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($uploaded);

            throw $exception;
        }

        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'Product, gallery and SEO settings created successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT PRODUCT
    |--------------------------------------------------------------------------
    */

    public function edit(Product $product)
    {
        $categories = Category::query()
            ->where(function ($query) use ($product) {
                $query
                    ->where('is_active', true)
                    ->orWhere('slug', $product->category);
            })
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $product->load('galleryImages');

        return view(
            'admin.products.edit',
            compact('product', 'categories')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE PRODUCT
    |--------------------------------------------------------------------------
    */

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate(array_merge(
            $this->rules($product),
            $this->mediaRules($product),
            [
                'remove_image' => [
                    'sometimes',
                    'boolean',
                ],
            ]
        ));

        unset(
            $validated['slug'],
            $validated['remove_image']
        );

        $this->removeMediaInputFields($validated);

        $validated['is_active'] =
            $request->boolean('is_active');

        $validated['is_featured'] =
            $request->boolean('is_featured');

        $validated['show_premium_badge'] =
            $request->boolean('show_premium_badge');

        // SEO indexing
        $validated['seo_noindex'] =
            $request->boolean('seo_noindex');

        $validated['sort_order'] = (int) (
            $validated['sort_order'] ?? 0
        );

        $validated['wholesale_moq'] =
            $validated['wholesale_moq'] ?? null;

        $uploaded = [];

        try {
            /*
            |--------------------------------------------------------------
            | MAIN IMAGE
            |--------------------------------------------------------------
            */

            if ($request->hasFile('image')) {
                $validated['image'] = $this->saveUpload(
                    $request->file('image'),
                    'products',
                    $uploaded
                );
            } elseif ($request->boolean('remove_image')) {
                $validated['image'] = null;
            } else {
                unset($validated['image']);
            }

            /*
            |--------------------------------------------------------------
            | BANNER IMAGE
            |--------------------------------------------------------------
            */

            if ($request->hasFile('banner_image')) {
                $validated['banner_image'] = $this->saveUpload(
                    $request->file('banner_image'),
                    'products/banners',
                    $uploaded
                );
            } elseif ($request->boolean('remove_banner_image')) {
                $validated['banner_image'] = null;
            }

            /*
            |--------------------------------------------------------------
            | SEO IMAGE
            |--------------------------------------------------------------
            */

            if ($request->hasFile('seo_image')) {
                $validated['seo_image'] = $this->saveUpload(
                    $request->file('seo_image'),
                    'products/seo',
                    $uploaded
                );
            } elseif ($request->boolean('remove_seo_image')) {
                $validated['seo_image'] = null;
            }

            /*
            |--------------------------------------------------------------
            | GALLERY
            |--------------------------------------------------------------
            */

            $galleryPaths = $this->saveGalleryUploads(
                $request,
                $uploaded
            );

            $removeIds = array_map(
                'intval',
                $request->input('remove_gallery_images', [])
            );

            $primary = (string) $request->input(
                'primary_image',
                'main'
            );

            $orders = $request->input('gallery_order', []);

            /*
            |--------------------------------------------------------------
            | DATABASE TRANSACTION
            |--------------------------------------------------------------
            */

            DB::transaction(function () use (
                $product,
                $validated,
                $galleryPaths,
                $removeIds,
                $primary,
                $orders
            ) {
                $previousMain = $product->image;

                $product->update($validated);

                if ($removeIds) {
                    $product->galleryImages()
                        ->whereIn('id', $removeIds)
                        ->delete();
                }

                $nextOrder = (int) $product
                    ->galleryImages()
                    ->max('sort_order') + 1;

                foreach ($galleryPaths as $path) {
                    $product->galleryImages()->create([
                        'image' => $path,
                        'sort_order' => $nextOrder++,
                    ]);
                }

                $selectedPath = null;

                if (preg_match(
                    '/^existing:(\d+)$/',
                    $primary,
                    $match
                )) {
                    $selectedPath = $product
                        ->galleryImages()
                        ->whereKey((int) $match[1])
                        ->value('image');
                } elseif (preg_match(
                    '/^new:(\d+)$/',
                    $primary,
                    $match
                )) {
                    $selectedPath =
                        $galleryPaths[(int) $match[1]] ?? null;
                }

                if ($selectedPath) {
                    $product->update([
                        'image' => $selectedPath,
                    ]);

                    if (
                        $previousMain
                        && $previousMain !== $selectedPath
                    ) {
                        $alreadySaved = $product
                            ->galleryImages()
                            ->where('image', $previousMain)
                            ->exists();

                        if (!$alreadySaved) {
                            $product->galleryImages()->create([
                                'image' => $previousMain,
                                'sort_order' => $nextOrder++,
                            ]);
                        }
                    }
                }

                foreach ($orders as $id => $order) {
                    if (!ctype_digit((string) $id)) {
                        continue;
                    }

                    $product->galleryImages()
                        ->whereKey((int) $id)
                        ->update([
                            'sort_order' => max(
                                0,
                                min(1000000, (int) $order)
                            ),
                        ]);
                }
            });
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($uploaded);

            throw $exception;
        }

        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'Product, gallery, banner and SEO settings updated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | ACTIVATE / DEACTIVATE PRODUCT
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(Product $product)
    {
        $product->update([
            'is_active' => !$product->is_active,
        ]);

        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                $product->is_active
                    ? 'Product activated successfully.'
                    : 'Product deactivated successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | ARCHIVE PRODUCT
    |--------------------------------------------------------------------------
    */

    public function archive(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with(
                'success',
                'Product archived successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | RESTORE PRODUCT
    |--------------------------------------------------------------------------
    */

    public function restore(int $id)
    {
        $product = Product::onlyTrashed()
            ->findOrFail($id);

        $product->restore();

        return redirect()
            ->route('admin.products.index', [
                'view' => 'archived',
            ])
            ->with(
                'success',
                'Product restored successfully.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | UPLOAD SINGLE IMAGE
    |--------------------------------------------------------------------------
    */

    private function saveUpload(
        $file,
        string $directory,
        array &$uploaded
    ): string {
        $path = $file->store($directory, 'public');

        if (!$path) {
            throw new \RuntimeException(
                'Image upload failed.'
            );
        }

        $uploaded[] = $path;

        return $path;
    }

    /*
    |--------------------------------------------------------------------------
    | UPLOAD GALLERY IMAGES
    |--------------------------------------------------------------------------
    */

    private function saveGalleryUploads(
        Request $request,
        array &$uploaded
    ): array {
        $paths = [];

        foreach ($request->file('gallery_images', []) as $file) {
            $paths[] = $this->saveUpload(
                $file,
                'products/gallery',
                $uploaded
            );
        }

        return $paths;
    }

    /*
    |--------------------------------------------------------------------------
    | REMOVE MEDIA INPUTS FROM PRODUCT DATA
    |--------------------------------------------------------------------------
    */

    private function removeMediaInputFields(
        array &$validated
    ): void {
        unset(
            $validated['gallery_images'],
            $validated['banner_image'],
            $validated['remove_banner_image'],
            $validated['remove_gallery_images'],
            $validated['gallery_order'],
            $validated['primary_image'],
            $validated['seo_image'],
            $validated['remove_seo_image']
        );
    }

    /*
    |--------------------------------------------------------------------------
    | MEDIA VALIDATION
    |--------------------------------------------------------------------------
    */

    private function mediaRules(
        ?Product $product = null
    ): array {
        return [
            'gallery_images' => [
                'nullable',
                'array',
                'max:20',
            ],

            'gallery_images.*' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'banner_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'remove_banner_image' => [
                'sometimes',
                'boolean',
            ],

            'seo_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'remove_seo_image' => [
                'sometimes',
                'boolean',
            ],

            'remove_gallery_images' => [
                'nullable',
                'array',
            ],

            'remove_gallery_images.*' => [
                'integer',
                Rule::exists('product_images', 'id')
                    ->where(
                        'product_id',
                        $product?->id ?? 0
                    ),
            ],

            'gallery_order' => [
                'nullable',
                'array',
            ],

            'gallery_order.*' => [
                'integer',
                'min:0',
                'max:1000000',
            ],

            'primary_image' => [
                'nullable',
                'string',
                'max:40',
                'regex:/^(main|existing:[0-9]+|new:[0-9]+)$/',
            ],
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | PRODUCT VALIDATION
    |--------------------------------------------------------------------------
    */

    private function rules(
        ?Product $product = null
    ): array {
        $categoryRule = Rule::exists(
            'categories',
            'slug'
        )->where(function ($query) use ($product) {
            $query->where(function ($q) use ($product) {
                $q->where('is_active', true);

                if ($product !== null) {
                    $q->orWhere(
                        'slug',
                        $product->category
                    );
                }
            });
        });

        return [
            /*
            |--------------------------------------------------------------
            | BASIC PRODUCT INFORMATION
            |--------------------------------------------------------------
            */

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                $product === null
                    ? 'nullable'
                    : 'sometimes',
                'string',
                'max:150',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('products', 'slug')
                    ->ignore($product?->id),
            ],

            'number' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
                'max:99999999.99',
            ],

            'category' => [
                'required',
                'string',
                $categoryRule,
            ],

            /*
            |--------------------------------------------------------------
            | WHOLESALE
            |--------------------------------------------------------------
            */

            'wholesale_price' => [
                'nullable',
                'numeric',
                'min:0',
                'max:99999999.99',
            ],

            'wholesale_moq' => [
                'nullable',
                'integer',
                'min:1',
                'max:50',
            ],

            /*
            |--------------------------------------------------------------
            | PRODUCT SETTINGS
            |--------------------------------------------------------------
            */

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'is_active' => [
                'sometimes',
                'boolean',
            ],

            'is_featured' => [
                'sometimes',
                'boolean',
            ],

            /*
            |--------------------------------------------------------------
            | SEO SETTINGS
            |--------------------------------------------------------------
            */

            'seo_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'seo_description' => [
                'nullable',
                'string',
                'max:500',
            ],

            'seo_keywords' => [
                'nullable',
                'string',
                'max:500',
            ],

            'seo_noindex' => [
                'sometimes',
                'boolean',
            ],

            /*
            |--------------------------------------------------------------
            | PRODUCT DETAIL CONTENT
            |--------------------------------------------------------------
            */

            'subtitle' => [
                'nullable',
                'string',
                'max:150',
            ],

            'show_premium_badge' => [
                'sometimes',
                'boolean',
            ],

            'premium_badge_text' => [
                'nullable',
                'string',
                'max:150',
            ],

            'ingredients' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'nutrition_information' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'storage_instructions' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'shipping_delivery' => [
                'nullable',
                'string',
                'max:5000',
            ],

            /*
            |--------------------------------------------------------------
            | BENEFIT 1
            |--------------------------------------------------------------
            */

            'benefit_1_icon' => [
                'nullable',
                'string',
                'max:150',
            ],

            'benefit_1_title' => [
                'nullable',
                'string',
                'max:150',
            ],

            'benefit_1_description' => [
                'nullable',
                'string',
                'max:500',
            ],

            /*
            |--------------------------------------------------------------
            | BENEFIT 2
            |--------------------------------------------------------------
            */

            'benefit_2_icon' => [
                'nullable',
                'string',
                'max:150',
            ],

            'benefit_2_title' => [
                'nullable',
                'string',
                'max:150',
            ],

            'benefit_2_description' => [
                'nullable',
                'string',
                'max:500',
            ],

            /*
            |--------------------------------------------------------------
            | BENEFIT 3
            |--------------------------------------------------------------
            */

            'benefit_3_icon' => [
                'nullable',
                'string',
                'max:150',
            ],

            'benefit_3_title' => [
                'nullable',
                'string',
                'max:150',
            ],

            'benefit_3_description' => [
                'nullable',
                'string',
                'max:500',
            ],

            /*
            |--------------------------------------------------------------
            | BENEFIT 4
            |--------------------------------------------------------------
            */

            'benefit_4_icon' => [
                'nullable',
                'string',
                'max:150',
            ],

            'benefit_4_title' => [
                'nullable',
                'string',
                'max:150',
            ],

            'benefit_4_description' => [
                'nullable',
                'string',
                'max:500',
            ],
        ];
    }
}