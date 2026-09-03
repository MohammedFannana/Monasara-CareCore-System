<x-main-layout title="1000 أمل لأبناء الشهداء">

    @push('styles')

        <style>
            .color-text{
                color:rgba(36, 36, 36, 0.6);
            }

        </style>

    @endpush


    <div class="header fw-bold" style="color: var(--primary-color);font-size:18px;  border-top-right-radius: 6px;border-top-left-radius:6px; padding:10px;background:linear-gradient(to left , #c6fdda , #edfaf1)">
        مرحبًا بك في لوحة الادمن
        <p class="color-text fw-normal mt-2" style="font-size: 15px">
            توفر لوحة تحكم الأدمن نظرة شاملة ومباشرة على جميع بيانات النظام، حيث يمكن من خلالها إدارة المستخدمين، متابعة الطلبات، مراجعة ملفات الأيتام، والإشراف على الأنشطة والإحصائيات، مما يساعد على اتخاذ قرارات فعّالة بسرعة وكفاءة
        </p>
    </div>

    <section class="family-information mt-5">

        {{--    --}}


        <div class="rounded">

            <div class="d-flex justify-content-between mb-3">
                <p class="fs-5 fw-semibold"> قائمة  الذين تم ترشحهم </p>
                <a href="{{route('admin.orphan.create')}}" class="submit-btn text-decoration-none"> إضافة يتيم </a>
            </div>





        </div>

    </section>

</x-main-layout>
