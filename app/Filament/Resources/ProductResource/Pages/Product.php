<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\Page;

class Product extends Page
{
    protected static string $resource = ProductResource::class;
    protected static ?string $modelLabel = 'New Product';

    // using these livewire settings model this form to use livewire:


    public $product_id;
    public $user_id;
    public $name;
    public $description;
    public $price;
    public $category_id;
    public $instock;
    public $images = [];

    protected $rules = [
        'product_id' => 'required|string',
        'user_id' => 'required|string',
        'name' => 'required|string',
        'description' => 'nullable|string',
        'price' => 'required|numeric',
        'category_id' => 'required|integer',
        'instock' => 'required|integer',
        'images' => 'array|min:1|max:4',  // You can adjust the validation as needed
    ];

    public function submit()
    {
        $this->validate();

        $uploadedFileUrl = [];

        dump($this->images);

        foreach ($this->images as $image) {
            // Decode base64 image
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $image));
            
            // Create temporary file
            $tempFile = tempnam(sys_get_temp_dir(), 'cloudinary');
            file_put_contents($tempFile, $imageData);

            $uploadedFileUrl[] = cloudinary()->upload($tempFile, [
                'folder' => 'store/products',
                'transformation' => [
                    'width' => 400,
                    'height' => 680,
                    'crop' => 'fill'
                ]
            ])->getSecurePath();

            // Clean up temporary file
            unlink($tempFile);
        }

        dump($uploadedFileUrl);


        // Optionally, reset the form after submission
        $this->reset();
        $this->redirect(ProductResource::getUrl());
    }

    protected static string $view = 'filament.resources.product-resource.pages.product';
}
