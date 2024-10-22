@extends('layouts.admin')

@section('admin_content')
    <div class="main-content">
        <div class="main-content-inner">
            <div class="main-content-wrap">
                <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                    <h3>Brand infomation</h3>
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
                            <a href="{{ route('admin.brands') }}">
                                <div class="text-tiny">Brands</div>
                            </a>
                        </li>
                        <li>
                            <i class="icon-chevron-right"></i>
                        </li>
                        <li>
                            <div class="text-tiny">New Brand</div>
                        </li>
                    </ul>
                </div>
                <!-- new-category -->
                <div class="wg-box">
                    <form class="form-new-product form-style-1" action="{{ route('admin.brand.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <fieldset class="name">
                            <div class="body-title">Brand Name <span class="tf-color-1">*</span></div>
                            <input class="flex-grow @error('name') is-invalid @enderror" type="text"
                                placeholder="Brand name" name="name" tabindex="0" value="{{ old('name') }}"
                                aria-required="true" required="">
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </fieldset>
                     
                        <fieldset>
                            <div class="body-title">Upload images <span class="tf-color-1">*</span>
                            </div>
                           <div class="mb-3">
                                <label class="form-label">Category image</label>
                                <input oninput="newImg.src=window.URL.createObjectURL(this.files[0])" class="form-control"
                                name="image" type="file" id="image">
                            </div>

                             <div class="mb-3">
                                <img class="" id="newImg" width="150">
                            </div>
                        </fieldset>

                        <div class="bot">
                            <div></div>
                            <button class="tf-button w208" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="bottom-page">
            <div class="body-text">Copyright © 2024 SurfsideMedia</div>
        </div>
    </div>
@endsection
