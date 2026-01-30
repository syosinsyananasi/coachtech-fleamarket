@extends('layouts.app')

@section('title', '商品の出品')

@section('content')
<div class="sell-container">
    <h1 class="sell-form__title">商品の出品</h1>

    <form class="sell-form" action="{{ route('sell.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="sell-form__section">
            <h2 class="sell-form__section-title">商品画像</h2>
            <div class="sell-form__image-upload">
                <label class="sell-form__image-label">
                    <span class="sell-form__image-button">画像を選択する</span>
                    <input type="file" name="image" class="sell-form__image-input" accept="image/jpeg,image/png">
                </label>
                <div class="sell-form__image-preview" id="image-preview"></div>
            </div>
            @error('image')
                <p class="sell-form__error">{{ $message }}</p>
            @enderror
        </div>

        <div class="sell-form__divider"></div>

        <div class="sell-form__section">
            <h2 class="sell-form__section-title">商品の詳細</h2>

            <div class="sell-form__group">
                <label class="sell-form__label">カテゴリー</label>
                <div class="sell-form__categories">
                    @foreach($categories as $category)
                        <label class="sell-form__category">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="sell-form__category-input" {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                            <span class="sell-form__category-text">{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('categories')
                    <p class="sell-form__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="sell-form__group">
                <label class="sell-form__label">商品の状態</label>
                <select name="condition_id" class="sell-form__select">
                    <option value="">選択してください</option>
                    @foreach($conditions as $condition)
                        <option value="{{ $condition->id }}" {{ old('condition_id') == $condition->id ? 'selected' : '' }}>{{ $condition->name }}</option>
                    @endforeach
                </select>
                @error('condition_id')
                    <p class="sell-form__error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="sell-form__divider"></div>

        <div class="sell-form__section">
            <h2 class="sell-form__section-title">商品名と説明</h2>

            <div class="sell-form__group">
                <label class="sell-form__label" for="name">商品名</label>
                <input type="text" name="name" id="name" class="sell-form__input" value="{{ old('name') }}">
                @error('name')
                    <p class="sell-form__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="sell-form__group">
                <label class="sell-form__label" for="brand">ブランド名</label>
                <input type="text" name="brand" id="brand" class="sell-form__input" value="{{ old('brand') }}">
                @error('brand')
                    <p class="sell-form__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="sell-form__group">
                <label class="sell-form__label" for="description">商品の説明</label>
                <textarea name="description" id="description" class="sell-form__textarea" rows="5">{{ old('description') }}</textarea>
                @error('description')
                    <p class="sell-form__error">{{ $message }}</p>
                @enderror
            </div>

            <div class="sell-form__group">
                <label class="sell-form__label" for="price">販売価格</label>
                <div class="sell-form__price-input">
                    <span class="sell-form__price-prefix">¥</span>
                    <input type="number" name="price" id="price" class="sell-form__input sell-form__input--price" value="{{ old('price') }}" min="0">
                </div>
                @error('price')
                    <p class="sell-form__error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <button type="submit" class="sell-form__button">出品する</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.querySelector('.sell-form__image-input').addEventListener('change', function(e) {
        var preview = document.getElementById('image-preview');
        preview.innerHTML = '';
        if (e.target.files && e.target.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.createElement('img');
                img.src = e.target.result;
                preview.appendChild(img);
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });
</script>
@endsection
