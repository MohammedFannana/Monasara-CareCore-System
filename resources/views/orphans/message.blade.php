<x-main-layout title="1000 أمل لأبناء الشهداء">

    @push('styles')
        <style>
            .message{
                background: linear-gradient(to left, #c6fdda, #f7fdf9); /* تدرج أخضر خفيف */
                border: 1px solid #d6e9c6;
                border-radius: 10px;
            }
        </style>


    @endpush

    <div class="header fw-bold" style="color: var(--primary-color);font-size:18px; border-right: 6px solid var(--primary-color); border-top-right-radius: 6px;border-top-left-radius:6px; padding:10px;background:linear-gradient(to left , #c6fdda , #edfaf1)">
            نُرحب برسائلكم ومشاعركم. تتيح لكم هذه الصفحة كتابة رسالة لإدارة فريق 'ألف أمل' ورسالة خاصة لكافلكم الكريم.
            <div style="color: rgba(36, 36, 36, 0.6) ; font-size:15px" class="fw-semibold mt-2">
                <img src="{{asset('images/alert-circle.png')}}" alt="">
                <span> علما أن رسائلكم ستمر عبر إدارة فريق ألف أمل لتصل إلى أصحابها. </span>
            </div>
    </div>

    <x-alert name="success" class="mt-2"/>
    <x-alert name="danger" class="mt-2"/>


    <div class="message mt-4 p-4">

        <form action="{{route('orphan.amal.message')}}" method="post" enctype="multipart/form-data">
            @csrf
            <x-form.textarea name="message" type="text" id="message" label=" ما هي رسالتكم لإدارة فريق ألف أمل؟ " placeholder=" اكتب كلماتك، ملاحظاتك، أو أي شيء تود مشاركته مع الفريق. " />

            <div class="d-flex justify-content-end">
                <button type="submit" class="submit-btn w-25 mt-3"> إرسال  </button>
            </div>

        </form>

    </div>

    @if (auth('orphan')->check() && auth('orphan')->user()->isSponsored())

        <div class="message mt-4 p-4">

            <form action="{{route('orphan.sponsor.message')}}" method="post" enctype="multipart/form-data">
                @csrf
                <x-form.textarea name="message" type="text" id="message" label=" ما هي رسالتكم للكافل؟ " placeholder=" عبّر عن شكرك، تطلعاتك، أو كلماتك الخاصة لمَنْ يدعمك. " />

                <div class="d-flex justify-content-end">
                    <button type="submit" class="submit-btn w-25 mt-3"> إرسال  </button>
                </div>
            </form>

        </div>

    @endif




</x-main-layout>
