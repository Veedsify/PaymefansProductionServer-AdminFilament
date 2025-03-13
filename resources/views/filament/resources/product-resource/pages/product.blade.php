<x-filament-panels::page>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <div class="w-full bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-3">
            <!-- Sidebar Navigation -->
            <div class="bg-gray-100 dark:bg-gray-700 p-6 hidden md:block">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-6">Create Product</h2>
                <nav>
                    <ul class="space-y-2">
                        <li>
                            <a href="javascript:void()"
                                class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors font-medium">
                                Basic Information
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void()"
                                class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors font-medium">
                                Product Details
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void()"
                                class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors font-medium">
                                Images
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-span-1 md:col-span-2 p-8">
                <form wire:submit="submit" class="space-y-6">
                    @if (session()->has('message'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                            role="alert">
                            {{ session('message') }}
                        </div>
                    @endif

                    <div>
                        <label for="name"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Product
                            Name</label>
                        <input type="text" id="name" wire:model="name" required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-purple-400"
                            placeholder="Enter full product name">
                        @error('name')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="description"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Product
                            Description</label>
                        <textarea id="description" wire:model="description" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-purple-400"
                            placeholder="Provide a detailed description of the product"></textarea>
                        @error('description')
                            <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div>
                            <label for="price"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Price</label>
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 dark:text-gray-400">₦</span>
                                <input type="number" id="price" wire:model="price" step="0.01" min="0"
                                    class="w-full pl-8 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-purple-400"
                                    placeholder="0.00">
                            </div>
                            @error('price')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="category_id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                            <select id="category_id" wire:model="category_id"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-purple-400">
                                <option selected>Select Product Category</option>
                                @foreach($this->categories as $category)
                                    <option value="{{$category->id}}">{{ucwords($category->name)}}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="block">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Available Sizes</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($this->available_sizes as $size)
                                <label class="flex items-center space-x-3">
                                    <input type="checkbox" wire:model="updateTypes" value="{{ $size->id }}" class="form-checkbox h-5 w-5 text-purple-600 rounded border-gray-300 focus:ring-purple-500">
                                    <span class="text-gray-700 dark:text-gray-300">
                                        {{$size->name}}
                                    </span>
                                </label>
                                @endforeach
                            </div>
                            @error('sizes')
                                <span class="text-red-500 text-sm mt-1">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Product
                            Images</label>
                        <div wire:ignore x-data="imageUploader()" class="space-y-4">
                            <div x-cloak x-show="!images.length"
                                class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <input type="file" multiple accept="image/*" @change="handleFileUpload($event)"
                                    class="hidden" x-ref="fileInput">
                                <div @click="$refs.fileInput.click()" class="cursor-pointer">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                        Click to upload or drag and drop images
                                    </p>
                                </div>
                            </div>

                            <div x-show="images.length" class="grid grid-cols-3 md:grid-cols-4 gap-4">
                                <template x-for="(image, index) in images" :key="index">
                                    <div class="relative group">
                                        <img :src="image"
                                            class="w-full aspect-square object-cover rounded-lg shadow-md">
                                        <button type="button" @click="removeImage(index)"
                                            class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                                <div @click="$refs.fileInput.click()"
                                    class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg flex items-center justify-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors aspect-square">
                                    <svg class="h-8 w-8 text-gray-400 dark:text-gray-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col mb-4">
                         <label for="instock" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                             Product is available in stock
                         </label>
                        <input
                        placeholder="300"
                         type="text" id="instock" wire:model="instock"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all dark:bg-gray-700 dark:text-gray-200 dark:focus:ring-purple-400">
                    </div>

                    <div class="flex justify-end space-x-4 pt-4">
                        <button type="button" wire:click.prevent="$reset"
                            class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-3 flex items-center gap-2 bg-purple-600 dark:bg-purple-700 text-white dark:text-gray-200 rounded-lg hover:bg-purple-700 dark:hover:bg-purple-600 transition-colors">
                            Create Product
                            <span wire:loading wire:target="submit">
                                <svg class="animate-spin h-5 w-5 text-white bg-purple-600 mx-3" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="white"
                                        stroke-width="4"></circle>
                                    <path class="opacity-75" fill="white"
                                        d="M4 12a8 8 0 018-8V0c4.418 0 8 3.582 8 8s-3.582 8-8 8V4a4 4 0 00-4 4H4z">
                                    </path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function imageUploader() {
            return {
                images: [],
                handleFileUpload(event) {
                    const files = event.target.files;
                    const maxFileSize = 3 * 1024 * 1024; // 3MB in bytes

                    @this.set('images', []); // Reset Livewire images array

                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];
                        if (!file.type.startsWith('image/')) {
                            alert('Please upload only image files.');
                            continue;
                        }
                        if (file.size > maxFileSize) {
                            alert('Image size must be less than 3MB');
                            continue;
                        }
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.images.push(e.target.result);
                            @this.set('images', [...this.images]); // Update Livewire images array
                        };
                        reader.readAsDataURL(file);
                    }
                },
                removeImage(index) {
                    this.images.splice(index, 1);
                    @this.set('images', this.images); // Update Livewire images array
                }
            }
        }
    </script>

</x-filament-panels::page>
