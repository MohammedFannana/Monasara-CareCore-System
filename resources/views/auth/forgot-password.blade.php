<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('نسيت كلمة مرورك؟ لا مشكلة. فقط أخبرنا بعنوان بريدك الإلكتروني وسنرسل لك رابط إعادة تعيين كلمة المرور لاختيار كلمة مرور جديدة.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <input type="hidden" name="guard" value="{{ request('type') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('البريد الالكتروني')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <button class="submit-btn">
                {{ __('رابط إعادة تعيين كلمة المرور للبريد الإلكتروني') }}
            </button>
        </div>
    </form>
</x-guest-layout>
