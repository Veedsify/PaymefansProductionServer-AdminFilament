<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\ProductCategory;
use App\Models\ProductSize;
use App\Models\Product as ProductModel;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class Product extends Page
{
    protected static string $resource = ProductResource::class;
    protected static ?string $modelLabel = "New Product";

    // using these livewire settings model this form to use livewire:

    public $product_id;
    public $available_sizes;
    public $categories;
    public $user_id;
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $instock;
    public $images = [];
    public $updateTypes = [];

    protected $rules = [
        "product_id" => "required|string",
        "user_id" => "required|integer",
        "name" => "required|string",
        "description" => "nullable|string",
        "price" => "required|numeric",
        "category_id" => "required|integer",
        "instock" => "required|integer",
        "images" => "array|min:1|max:4", // You can adjust the validation as needed
    ];

    public function mount()
    {
        $this->product_id = uniqid();
        $this->user_id = Auth::id();
        $this->available_sizes = ProductSize::all();
        $this->categories = ProductCategory::all();
    }

    public function submit()
    {
        try {
            $this->validate();

            if (empty($this->updateTypes)) {
                $this->showErrorNotification(
                    'Product Not Saved',
                    'Please select at least one size.'
                );
                return;
            }

            DB::beginTransaction();

            try {
                // Upload images and get URLs
                $uploadedFileUrls = $this->uploadImages();

                // Create or update product
                $product = $this->createOrUpdateProduct();

                // Update product images
                $this->updateProductImages($product, $uploadedFileUrls);

                // Update product sizes
                $this->updateProductSizes($product);

                DB::commit();

                $this->showSuccessNotification(
                    'Product Saved',
                    'The product has been saved successfully.'
                );

                // Clear the cached products
                // Redis::del('products');

                $this->reset();
                $this->redirect(ProductResource::getUrl());
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }
        } catch (\Exception $e) {
            $this->showErrorNotification(
                'Error Saving Product',
                'An error occurred while saving the product. Please try again.'
            );

            Log::error('Product submission failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'product_id' => $this->product_id ?? null,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    private function uploadImages(): array
    {
        $uploadedFileUrls = [];

        foreach ($this->images as $image) {
            try {
                $imageData = base64_decode(
                    preg_replace("#^data:image/\w+;base64,#i", "", $image)
                );

                if ($imageData === false) {
                    throw new \Exception('Invalid image data');
                }

                $tempFile = tempnam(sys_get_temp_dir(), "cloudinary");
                if ($tempFile === false) {
                    throw new \Exception('Failed to create temporary file');
                }

                if (file_put_contents($tempFile, $imageData) === false) {
                    throw new \Exception('Failed to write image data');
                }

                try {
                    $uploadedFileUrls[] = cloudinary()
                        ->upload($tempFile, [
                            "folder" => "store/products",
                            "transformation" => [
                                "width" => 480,
                                "height" => 680,
                                "crop" => "fill",
                            ],
                        ])
                        ->getSecurePath();
                } finally {
                    // Ensure temporary file is always cleaned up
                    if (file_exists($tempFile)) {
                        unlink($tempFile);
                    }
                }
            } catch (\Exception $e) {
                throw new \Exception('Failed to upload image: ' . $e->getMessage());
            }
        }

        return $uploadedFileUrls;
    }

    private function createOrUpdateProduct(): ProductModel
    {
        return ProductModel::updateOrCreate(
            ["product_id" => $this->product_id],
            [
                "user_id" => Auth::id(),
                "name" => $this->name,
                "description" => $this->description,
                "price" => $this->price,
                "category_id" => $this->category_id,
                "instock" => $this->instock,
            ]
        );
    }

    private function updateProductImages(ProductModel $product, array $uploadedFileUrls): void
    {
        $product->product_images()->delete();

        foreach ($uploadedFileUrls as $url) {
            $product->product_images()->create([
                "product_id" => $product->id,
                "image_url" => $url,
            ]);
        }
    }

    private function updateProductSizes(ProductModel $product): void
    {
        $product->product_size_pivots()->delete();

        foreach ($this->updateTypes as $size) {
            $product->product_size_pivots()->create([
                "product_id" => $product->id,
                "size_id" => $size,
            ]);
        }
    }

    private function showSuccessNotification(string $title, string $body): void
    {
        Notification::make()
            ->title($title)
            ->success()
            ->icon('heroicon-o-check-circle')
            ->body($body)
            ->send();
    }

    private function showErrorNotification(string $title, string $body): void
    {
        Notification::make()
            ->title($title)
            ->danger()
            ->icon('heroicon-o-x-circle')
            ->body($body)
            ->send();
    }

    public function render(): View
    {
        return parent::render();
    }

    protected static string $view = "filament.resources.product-resource.pages.product";
}
