<x-main-layout title="1000 أمل لأبناء الشهداء">

    @push('styles')

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">


        <style>
            .color-text{
                color:rgba(36, 36, 36, 0.6);
            }

        </style>

    @endpush


    <div class="header fw-bold" style="color: var(--primary-color);font-size:18px;  border-top-right-radius: 6px;border-top-left-radius:6px; padding:10px;background:linear-gradient(to left , #c6fdda , #edfaf1)">
        مرحبًا بك في صفحة تغيير كلمة المرور
        <p class="color-text fw-normal mt-2" style="font-size: 15px">
            من خلال هذه الصفحة يمكنكم تغيير كلمة المرور الخاص بك  .
        </p>
    </div>

    <section class="family-information mt-5">


        <div class="rounded">


            <section>
                <header>
                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('تحديث كلمة المرور') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('تأكد من أن حسابك يستخدم كلمة مرور طويلة وعشوائية للبقاء آمنًا.') }}
                    </p>
                </header>

                <x-alert name="success"/>
                <x-alert name="danger"/>

                @if (session('status') === 'password-updated')

                    <div
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert" style="font-size: 1rem;"
                    >{{ __('تم تغيير كلمة المرور بنجاح.') }}</div>
                @endif

                <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                    @csrf
                    @method('put')

                    <div class="mb-3">
                        <label for="update_password_current_password">كلمة المرور الحالية</label>
                        <input class="form-control mt-1 block w-full" id="update_password_current_password" type="password" name="current_password" autocomplete="current-password">
                        <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                    </div>

                    <div class="mb-3">
                        <label for="update_password_password"> كلمة مرور جديدة </label>
                        <input class="form-control mt-1 block w-full" id="update_password_password" type="password" name="password" autocomplete="new-password">
                        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                    </div>

                    <div class="mb-3">
                        <label for="update_password_password_confirmation"> تأكيد كلمة المرور </label>
                        <input class="form-control mt-1 block w-full" id="update_password_password_confirmation" type="password" name="password_confirmation" autocomplete="new-password">
                        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="flex items-center gap-4">
                        {{-- <x-primary-button>{{ __('Save') }}</x-primary-button> --}}
                        <button type="submit" class="btn  btn-primary " style="width: 15%"> حفظ </button>
                    </div>
                </form>

            </section>




        </div>

    </section>


</x-main-layout>
