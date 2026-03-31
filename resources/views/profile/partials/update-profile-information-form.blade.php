<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">{{ __('Hồ sơ cá nhân') }}</h2>
        <p class="mt-1 text-sm text-gray-600">{{ __("Cập nhật thông tin và ảnh đại diện của bạn.") }}</p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div x-data="{ photoName: null, photoPreview: null }">
            <x-input-label for="avatar" :value="__('Ảnh đại diện')" />

            <div class="mt-2 flex items-center space-x-5">
                <div x-show="! photoPreview">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" class="w-20 h-20 rounded-3xl object-cover border-2 border-slate-100 shadow-sm">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366f1&color=fff&size=80" class="w-20 h-20 rounded-3xl object-cover border-2 border-slate-100 shadow-sm">
                    @endif
                </div>
                <div x-show="photoPreview" style="display: none;">
                    <span class="block w-20 h-20 rounded-3xl object-cover border-2 border-slate-100 shadow-sm" x-bind:style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + photoPreview + '\');'"></span>
                </div>

                <input type="file" name="avatar" id="avatar" class="hidden" accept="image/*" x-ref="avatar" x-on:change="
                    photoName = $refs.avatar.files[0].name;
                    const reader = new FileReader();
                    reader.onload = (e) => { photoPreview = e.target.result; };
                    reader.readAsDataURL($refs.avatar.files[0]);
                ">

                <x-secondary-button type="button" x-on:click.prevent="$refs.avatar.click()">{{ __('Chọn ảnh mới') }}</x-secondary-button>
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
        </div>

        <div>
            <x-input-label for="name" :value="__('Họ tên')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Lưu thay đổi') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 font-bold uppercase tracking-widest">{{ __('Đã lưu!') }}</p>
            @endif
        </div>
    </form>
</section>
