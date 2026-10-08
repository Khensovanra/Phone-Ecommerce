{{-- Validation Errors --}}
@if($errors->any())

    <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4">

        <h3 class="font-semibold text-red-800 mb-2">
            Please fix the following errors:
        </h3>

        <ul class="list-disc list-inside text-red-700 space-y-1">

            @foreach($errors->all() as $error)

                <li>
                    {{ $error }}
                </li>

            @endforeach

        </ul>

    </div>

@endif


<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Category --}}
    <div>

        <label
            for="category_id"
            class="block text-sm font-semibold text-gray-700 mb-2"
        >
            Category *
        </label>

        <select
            id="category_id"
            name="category_id"
            required
            class="w-full rounded-lg border border-gray-300 px-4 py-3"
        >

            <option value="">
                Select Category
            </option>

            @foreach($categories as $category)

                <option
                    value="{{ $category->id }}"
                    @selected(
                        old(
                            'category_id',
                            $product->category_id ?? ''
                        ) == $category->id
                    )
                >
                    {{ $category->name }}
                </option>

            @endforeach

        </select>

    </div>


    {{-- Brand --}}
    <div>

        <label
            for="brand_id"
            class="block text-sm font-semibold text-gray-700 mb-2"
        >
            Brand *
        </label>

        <select
            id="brand_id"
            name="brand_id"
            required
            class="w-full rounded-lg border border-gray-300 px-4 py-3"
        >

            <option value="">
                Select Brand
            </option>

            @foreach($brands as $brand)

                <option
                    value="{{ $brand->id }}"
                    @selected(
                        old(
                            'brand_id',
                            $product->brand_id ?? ''
                        ) == $brand->id
                    )
                >
                    {{ $brand->name }}
                </option>

            @endforeach

        </select>

    </div>


    {{-- Product Name --}}
    <div>

        <label
            for="name"
            class="block text-sm font-semibold text-gray-700 mb-2"
        >
            Product Name *
        </label>

        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name', $product->name ?? '') }}"
            required
            placeholder="e.g. iPhone 17 Pro Max"
            class="w-full rounded-lg border border-gray-300 px-4 py-3"
        >

    </div>


    {{-- SKU --}}
    <div>

        <label
            for="sku"
            class="block text-sm font-semibold text-gray-700 mb-2"
        >
            SKU *
        </label>

        <input
            type="text"
            id="sku"
            name="sku"
            value="{{ old('sku', $product->sku ?? '') }}"
            required
            placeholder="e.g. APL-IP17PM"
            class="w-full rounded-lg border border-gray-300 px-4 py-3"
        >

    </div>


    {{-- Slug --}}
    <div>

        <label
            for="slug"
            class="block text-sm font-semibold text-gray-700 mb-2"
        >
            Slug
        </label>

        <input
            type="text"
            id="slug"
            name="slug"
            value="{{ old('slug', $product->slug ?? '') }}"
            placeholder="Leave empty to keep current slug"
            class="w-full rounded-lg border border-gray-300 px-4 py-3"
        >

        <p class="text-xs text-gray-500 mt-1">
            Example: iphone-17-pro-max
        </p>

    </div>


    {{-- Price --}}
    <div>

        <label
            for="price"
            class="block text-sm font-semibold text-gray-700 mb-2"
        >
            Price ($) *
        </label>

        <input
            type="number"
            id="price"
            name="price"
            value="{{ old('price', $product->price ?? '') }}"
            required
            min="0"
            step="0.01"
            placeholder="0.00"
            class="w-full rounded-lg border border-gray-300 px-4 py-3"
        >

    </div>


    {{-- Stock --}}
    <div>

        <label
            for="stock"
            class="block text-sm font-semibold text-gray-700 mb-2"
        >
            Stock Quantity *
        </label>

        <input
            type="number"
            id="stock"
            name="stock"
            value="{{ old('stock', $product->stock ?? 0) }}"
            required
            min="0"
            step="1"
            class="w-full rounded-lg border border-gray-300 px-4 py-3"
        >

    </div>


    {{-- Image --}}
    <div>

        <label
            for="image"
            class="block text-sm font-semibold text-gray-700 mb-2"
        >
            Product Image
        </label>

        <input
            type="file"
            id="image"
            name="image"
            accept=".jpg,.jpeg,.png,.webp"
            class="w-full rounded-lg border border-gray-300 px-4 py-3 bg-white"
        >

        <p class="text-xs text-gray-500 mt-1">
            JPG, JPEG, PNG or WEBP. Maximum 2MB.
        </p>

        @if(isset($product) && $product->image)

            <div class="mt-4">

                <p class="text-sm text-gray-600 mb-2">
                    Current Image
                </p>

                <img
                    src="{{ asset('storage/' . $product->image) }}"
                    alt="{{ $product->name }}"
                    class="w-32 h-32 object-cover rounded-lg border"
                >

            </div>

        @endif

    </div>


    {{-- Description --}}
    <div class="lg:col-span-2">

        <label
            for="description"
            class="block text-sm font-semibold text-gray-700 mb-2"
        >
            Description
        </label>

        <textarea
            id="description"
            name="description"
            rows="6"
            placeholder="Enter product description..."
            class="w-full rounded-lg border border-gray-300 px-4 py-3"
        >{{ old('description', $product->description ?? '') }}</textarea>

    </div>


    {{-- Active --}}
    <div class="lg:col-span-2">

        <label class="flex items-center gap-3">

            <input
                type="hidden"
                name="is_active"
                value="0"
            >

            <input
                type="checkbox"
                name="is_active"
                value="1"
                @checked(
                    old(
                        'is_active',
                        $product->is_active ?? true
                    )
                )
                class="w-5 h-5 rounded border-gray-300 text-blue-600"
            >

            <span class="font-semibold text-gray-700">
                Product is active
            </span>

        </label>

        <p class="text-sm text-gray-500 ml-8 mt-1">
            Active products can be displayed and purchased by customers.
        </p>

    </div>

</div>