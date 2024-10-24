@extends('layouts.admin')

@section('admin_content')
    <div class="main-content">
        <!-- main-content-wrap -->
        <div class="main-content-inner">
            <!-- main-content-wrap -->
            <div class="main-content-wrap">
                <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                    <h3>Add Product</h3>
                    <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                        <li>
                            <a href="{{ route('admin.index') }}">
                                <div class="text-tiny">Dashboard</div>
                            </a>
                        </li>
                        <li>
                            <i class="icon-chevron-right"></i>
                        </li>
                        <li>
                            <a href="{{ route('admin.product.index') }}">
                                <div class="text-tiny">Products</div>
                            </a>
                        </li>
                        <li>
                            <i class="icon-chevron-right"></i>
                        </li>
                        <li>
                            <div class="text-tiny">Add product</div>
                        </li>
                    </ul>
                </div>
                <!-- form-add-product -->
                <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data"
                    action="{{ route('admin.product.store') }}">
                    @csrf

                    <div class="wg-box">
                        <fieldset class="name">
                            <div class="body-title mb-10">Product name <span class="tf-color-1">*</span>
                            </div>
                            <input class="mb-10" type="text" placeholder="Enter product name" name="name"
                                tabindex="0" value="{{ old('name') }}" aria-required="true" required="">
                            <div class="text-tiny">Do not exceed 100 characters when entering the
                                product name.</div>
                        </fieldset>



                        <div class="gap22 cols">
                            <fieldset class="category">
                                <div class="body-title mb-10">Category <span class="tf-color-1">*</span>
                                </div>
                                <div class="select">
                                    <select class="" name="category_id">
                                        <option>Choose category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach


                                    </select>
                                </div>
                            </fieldset>
                            <fieldset class="brand">
                                <div class="body-title mb-10">Brand <span class="tf-color-1">*</span>
                                </div>
                                <div class="select">
                                    <select class="" name="brand_id">
                                        <option>Choose Brand</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </fieldset>
                        </div>

                        <fieldset class="shortdescription">
                            <div class="body-title mb-10">Short Description <span class="tf-color-1">*</span></div>
                            <textarea class="mb-10 ht-150" name="short_description" placeholder="Short Description" tabindex="0"
                                aria-required="true" required="">{{ old('short_description') }}</textarea>
                            <div class="text-tiny">Do not exceed 100 characters when entering the
                                product name.</div>
                        </fieldset>

                        <fieldset class="description">
                            <div class="body-title mb-10">Description <span class="tf-color-1">*</span>
                            </div>
                            <textarea class="mb-10" name="description" placeholder="Description" tabindex="0" aria-required="true"
                                required="">{{ old('description') }}</textarea>
                            <div class="text-tiny">Do not exceed 100 characters when entering the
                                product name.</div>
                        </fieldset>
                    </div>
                    <div class="wg-box">
                        <fieldset>
                            <div class="body-title">Upload images <span class="tf-color-1">*</span>
                            </div>
                            <div class="upload-image flex-grow">

                                <div class="mb-3">
                                    <label class="form-label">Category image</label>
                                    <input oninput="newImg.src=window.URL.createObjectURL(this.files[0])"
                                        class="form-control" name="image" type="file" id="image">
                                </div>

                                <div class="mb-3">
                                    <img class="" id="newImg" width="150">
                                </div>

                            </div>
                        </fieldset>

                        <fieldset>
                            <div class="body-title mb-10">Upload Gallery Images</div>
                            <div class="upload-image mb-16">
                                <div id="galUpload" class="item up-load">
                                    <input type="file" id="gFile" name="images[]" accept="image/*" multiple
                                        onchange="previewMultipleImages(event)">
                                    <div id="imagePreviews" class="image-preview-container"></div>
                                </div>
                                <!-- Container to hold multiple image previews -->
                            </div>
                        </fieldset>


                        <div class="cols gap22">
                            <fieldset class="name">
                                <div class="body-title mb-10">Regular Price <span class="tf-color-1">*</span></div>
                                <input class="mb-10" type="text" placeholder="Enter regular price" name="regular_price"
                                    tabindex="0" value="{{ old('name') }}" aria-required="true" required="">
                            </fieldset>
                            <fieldset class="name">
                                <div class="body-title mb-10">Sale Price <span class="tf-color-1">*</span></div>
                                <input class="mb-10" type="text" placeholder="Enter sale price" name="sale_price"
                                    tabindex="0" value="{{ old('sale_price') }}" aria-required="true" required="">
                            </fieldset>
                        </div>


                        <div class="cols gap22">
                            <fieldset class="name">
                                <div class="body-title mb-10">SKU <span class="tf-color-1">*</span>
                                </div>
                                <input class="mb-10" type="text" placeholder="Enter SKU" name="SKU"
                                    tabindex="0" value="{{ old('SKU') }}" aria-required="true" required="">
                            </fieldset>
                            <fieldset class="name">
                                <div class="body-title mb-10">Quantity <span class="tf-color-1">*</span>
                                </div>
                                <input class="mb-10" type="text" placeholder="Enter quantity" name="quantity"
                                    tabindex="0" value="{{ old('quantity') }}" aria-required="true" required="">
                            </fieldset>
                        </div>

                        <div class="cols gap22">
                            <fieldset class="name">
                                <div class="body-title mb-10">Stock</div>
                                <div class="select mb-10">
                                    <select class="" name="stock_status">
                                        <option value="instock">InStock</option>
                                        <option value="outofstock">Out of Stock</option>
                                    </select>
                                </div>
                            </fieldset>
                            <fieldset class="name">
                                <div class="body-title mb-10">Featured</div>
                                <div class="select mb-10">
                                    <select class="" name="featured">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </fieldset>
                        </div>
                        <div class="cols gap10">
                            <button class="tf-button w-full" type="submit">Add product</button>
                        </div>
                    </div>
                </form>
                <!-- /form-add-product -->
            </div>
            <!-- /main-content-wrap -->
        </div>
        <!-- /main-content-wrap -->
        <div class="bottom-page">
            <div class="body-text">Copyright © 2024 </div>
        </div>
    </div>
    <script>
        function previewMultipleImages(event) {
            const imageContainer = document.getElementById('imagePreviews');
            imageContainer.innerHTML = ''; // Clear previous previews

            const files = event.target.files; // Get selected files

            if (files) {
                // Loop through each file selected
                Array.from(files).forEach(file => {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const imgElement = document.createElement('img');
                        imgElement.src = e.target.result; // Set image source to the file's data URL
                        imgElement.width = 150; // Set a standard width for the image preview
                        imgElement.style.marginRight = '10px'; // Add some spacing between images
                        imageContainer.appendChild(imgElement); // Add image to the preview container
                    };

                    reader.readAsDataURL(file); // Read the file as a data URL for preview
                });
            }
        }
    </script>
@endsection
