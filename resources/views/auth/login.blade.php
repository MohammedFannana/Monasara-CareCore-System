<x-guest-layout>
    {{-- login --}}
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <p class="fw-bold d-flex justify-content-center gap-2 fs-5" style="color: var(--primary-color)">
        <img src="{{asset('images/hand.png')}}" alt="" width="24px" height="24px">
        ! أهلاً بك
    </p>

    <p class="text-center fw-bold" style="color: var(--text-color); font-size:17px">
        من فضلك، اختر الفئة التي تنتمي إليها
    </p>

    @error('guard')
        <div class="alert alert-danger">
            {{ $message }}
        </div>
    @enderror



    <form method="POST" action="{{ route('login') }}" dir="rtl" id="loginForm">
        @csrf


        <div class="flex justify-content-between mb-4 row gap-1">

            <button type="button" class="col-12 col-sm-5 col-md-4  guard-btn d-flex justify-content-center fw-semibold gap-2 background1 border rounded px-4 py-2" data-guard="researcher" style="color: var(--text-color)">
                باحث اجتماعي
                <img src="{{asset('images/sidebar/reschers.png')}}" alt="" width="24px" height="24px">
            </button>

            <button type="button" class="col-12 col-sm-5 col-md-2 text-center guard-btn d-flex justify-content-center fw-semibold gap-2 background1  border rounded px-4 py-2" data-guard="association"  style="color: var(--text-color)">
                جمعية
                <img src="{{asset('images/sidebar/elements.png')}}" alt="" width="24px" height="24px">
            </button>

            <button type="button" class="col-12 col-sm-5 col-md-2 text-center guard-btn d-flex justify-content-center fw-semibold gap-2  background1  border rounded px-4 py-2" data-guard="sponsor"  style="color: var(--text-color)">
                كافل
                 <img src="{{asset('images/sidebar/Vector.png')}}" alt="" width="24px" height="24px">
            </button>

            <button type="button" class="col-12 col-sm-5 col-md-2 text-center guard-btn d-flex justify-content-center fw-semibold gap-2  background1  border rounded px-4 py-2" data-guard="orphan"  style="color: var(--text-color)">
                يتيم
                <img src="{{asset('images/sidebar/face.png')}}" alt="" width="24px" height="24px">
            </button>

        </div>

        <input type="hidden" name="guard" id="guardInput"  value="">

        <div class="id-input" id="idInput" style="display: none">
            <x-input-label for="id_number" :value="__('رقم الهوية')" />
            <x-text-input id="id_number" class="block  mt-1 w-full" type="text" name="id_number" :value="old('id_number')"  autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('id_number')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="email-input" id="emailInput">
            <x-input-label for="email" :value="__('البريد الالكتروني')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"  autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('كلمة المرور')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->

        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">{{ __('تذكرني') }}</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                    <a id="resetLink" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-decoration-none" href="{{ route('password.request') }}" style="color:var(--primary-color);">
                        هل نسيت كلمة المرور؟
                    </a>
                @endif

                {{-- <x-primary-button class="ms-3">
                    {{ __('Log in') }}
                </x-primary-button> --}}
            </div>
        </div>

        <button type="submit" class="submit-btn w-100 text-center"> تسجيل الدخول </button>

        <a href="{{ route('sponsor.register') }}" style="display: none ; color:var(--primary-color)" id="register" class="text-md text-center mt-2   focus:outline-none  text-decoration-none">
            إنشاء حساب جديد
        </a>

    </form>

    @push('script')

    <script>
            document.addEventListener('DOMContentLoaded', function () {
                const guardInput = document.getElementById('guardInput');
                const guardButtons = document.querySelectorAll('.guard-btn');

                const emailInputDiv = document.getElementById('emailInput');
                const idInputDiv = document.getElementById('idInput');
                const emailInput = document.getElementById('email');
                const idNumberInput = document.getElementById('id_number');
                const registerLink = document.getElementById('register');

                let selectedGuard = localStorage.getItem('selectedGuard') || '';

                // تحديث ظهور الحقول وحالة التفعيل بناء على نوع guard
                function updateInputVisibility(guard) {
                    if (guard === 'orphan') {
                        // عرض رقم الهوية وتفعيله
                        idInputDiv.style.display = 'block';
                        idNumberInput.disabled = false;

                        // إخفاء البريد وتعطيله
                        emailInputDiv.style.display = 'none';
                        emailInput.disabled = true;
                        registerLink.style.display = 'none';
                    }else if (guard === 'sponsor') {
                        // عرض البريد وتفعيله
                        emailInputDiv.style.display = 'block';
                        emailInput.disabled = false;

                        // إخفاء رقم الهوية وتعطيله
                        idInputDiv.style.display = 'none';
                        idNumberInput.disabled = true;
                        registerLink.style.display = 'block';
                    } else {
                        // عرض البريد وتفعيله
                        emailInputDiv.style.display = 'block';
                        emailInput.disabled = false;

                        // إخفاء رقم الهوية وتعطيله
                        idInputDiv.style.display = 'none';
                        idNumberInput.disabled = true;
                        registerLink.style.display = 'none'
                    }
                }

                // تعيين القيمة الافتراضية للحارس
                if (guardInput) {
                    guardInput.value = selectedGuard;
                }

                // تلوين الأزرار وتحديث الحقول بناء على الحارس المختار
                guardButtons.forEach(btn => {
                    const guard = btn.dataset.guard;

                    if (guard === selectedGuard) {
                        btn.classList.add('bg-selected');
                        btn.classList.remove('background1');
                        updateInputVisibility(guard);
                    } else {
                        btn.classList.remove('bg-selected');
                        btn.classList.add('background1');
                    }

                    btn.addEventListener('click', function () {
                        selectedGuard = guard;
                        guardInput.value = guard;
                        localStorage.setItem('selectedGuard', guard);

                        guardButtons.forEach(b => {
                            b.classList.remove('bg-selected');
                            b.classList.add('background1');
                        });

                        this.classList.add('bg-selected');
                        this.classList.remove('background1');

                        updateInputVisibility(guard);
                    });
                });

                // في حالة وجود اختيار محفوظ مسبقاً عند تحميل الصفحة
                if (selectedGuard) {
                    updateInputVisibility(selectedGuard);
                }
            });
            </script>

    </script>

    <script>
        document.getElementById('resetLink').addEventListener('click', function (e) {
            e.preventDefault();

            // اقرأ آخر guard مختار مباشرة
            const guard = localStorage.getItem('selectedGuard');

            if (!guard) {
                alert('الرجاء اختيار نوع الحساب أولاً');
                return;
            }

            // انتقل للرابط مع guard
            window.location.href = "{{ route('password.request') }}" + "?type=" + guard;
        });
</script>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function () {
            const guardInput = document.getElementById('guardInput');
            const guardButtons = document.querySelectorAll('.guard-btn');

            let selectedGuard = localStorage.getItem('selectedGuard');

            // إذا لم يتم اختيار حارس مسبقًا، استخدم القيمة الافتراضية
            // if (!selectedGuard) {
            //     selectedGuard = 'web'; // ← غيّرها إذا أردت قيمة افتراضية مختلفة
            // }

            // عيّن القيمة في input
            if (guardInput) {
                guardInput.value = selectedGuard;
            }

            // تلوين الأزرار
            guardButtons.forEach(btn => {
                const guard = btn.dataset.guard;

                if (guard === 'orphan') {
                // توجيه مباشر لصفحة اليتيم
                    window.location.href = '/orphan/login';
                    // return;
                }

                // تمييز الزر المحدد
                if (guard === selectedGuard) {
                    btn.classList.add('bg-selected');
                    btn.classList.remove('background1');
                } else {
                    btn.classList.remove('bg-selected');
                    btn.classList.add('background1');
                }

                // عند النقر على الزر
                btn.addEventListener('click', function () {
                    // تحديد القيمة
                    selectedGuard = guard;
                    guardInput.value = guard;
                    localStorage.setItem('selectedGuard', guard);

                    // إعادة التلوين
                    guardButtons.forEach(b => {
                        b.classList.remove('bg-selected');
                        b.classList.add('background1');
                    });
                    this.classList.add('bg-selected');
                    this.classList.remove('background1');
                });
            });

            // اختياري: إزالة القيمة المخزنة بعد الاستخدام
            // localStorage.removeItem('selectedGuard');
        });
    </script> --}}


    {{-- <script>
        document.querySelectorAll('.guard-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const guard = this.dataset.guard;

                if (guard === 'orphan') {
                    // توجيه لصفحة تسجيل دخول اليتيم
                    window.location.href = '/orphan/login';
                } else {
                    // حفظ نوع المستخدم في LocalStorage (أو SessionStorage)
                    // localStorage.setItem('selectedGuard', guard);

                    // التوجيه لصفحة تسجيل الدخول العامة
                    window.location.href = '/login';
                }
            });
        });
    </script> --}}



{{--
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectedGuard = localStorage.getItem('selectedGuard');
            if (selectedGuard) {
                document.getElementById('guardInput').value = selectedGuard;

                // تفعيل الزر تلقائيًا حسب الاختيار
                document.querySelectorAll('.guard-btn').forEach(btn => {
                    if (btn.dataset.guard === selectedGuard) {
                        btn.classList.remove('background1');
                        btn.classList.add('bg-selected');
                    } else {
                        btn.classList.remove('bg-selected');
                        btn.classList.add('background1');
                    }
                });

                // حذف القيمة بعد التهيئة
                localStorage.removeItem('selectedGuard');
            }
        });
    </script> --}}


        {{-- <script>
            document.querySelectorAll('.guard-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const guard = this.dataset.guard;

                    // تحديث الحقول المعروضة حسب نوع الـ guard
                    const emailInput = document.getElementById('emailInput');
                    // const idInput = document.getElementById('idInput');

                    // if (guard === 'orphan') {
                    //     emailInput.style.display = 'none';
                    //     idInput.style.display = 'block';
                    // } else {
                    //     emailInput.style.display = 'block';
                    //     idInput.style.display = 'none';
                    // }

                    // تحديث قيمة الـ guard في الفورم
                    document.getElementById('guardInput').value = guard;

                    // إزالة ألوان التفعيل من كل الأزرار
                    document.querySelectorAll('.guard-btn').forEach(b =>
                    {
                        b.classList.remove('bg-selected');
                        b.classList.add('background1')

                     });

                    // تفعيل الزر المحدد
                    this.classList.remove('background1');
                    this.classList.add('bg-selected');

                    // إرسال الفورم فقط إذا لم يكن يتيم
                    if (guard !== 'orphan') {
                        document.getElementById('loginForm').submit();
                    }
                });
            });
        </script> --}}

        <script>
            window.addEventListener('DOMContentLoaded', () => {
                const oldGuard = document.getElementById('guardInput').value;

                if (oldGuard) {
                    const button = document.querySelector(`.guard-btn[data-guard="${oldGuard}"]`);
                    if (button) {
                        button.click(); // simulate click to update UI accordingly
                    }
                }
            });
        </script>


    @endpush
</x-guest-layout>
