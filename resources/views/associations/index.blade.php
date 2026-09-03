<x-main-layout title="1000 أمل لأبناء الشهداء">

    @push('styles')

        <style>
            .color-text{
                color:rgba(36, 36, 36, 0.6);
            }

        </style>

    @endpush


    <div class="header fw-bold" style="color: var(--primary-color);font-size:18px;  border-top-right-radius: 6px;border-top-left-radius:6px; padding:10px;background:linear-gradient(to left , #c6fdda , #edfaf1)">
        مرحبًا بك في لوحة الجمعيات
        <p class="color-text fw-normal mt-2" style="font-size: 15px">
            يعرض هذا القسم الأيتام الذين هم في حالة ترشيح ، ويمكن الاطلاع على ملفاتهم الشخصية وتحديث بياناتهم قبل اتخاذ القرار.
        </p>
    </div>

    <section class="family-information mt-5">

        {{--    --}}


        <div class="rounded">

            <div class="d-flex justify-content-between mb-3">
                <p class="fs-5 fw-semibold"> قائمة الأيتام الذين تم ترشحهم </p>
                <a href="{{route('association.orphan.create')}}" class="submit-btn text-decoration-none"> إضافة يتيم </a>
            </div>

            <div class="table-responsive">
                <table  class=" border-0 w-100 text-center" style="border-collapse: collapse;">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>الجنس</th>
                            <th>العمر</th>
                            <th> حالة اليتيم </th>
                            <th>بلد اليتيم</th>
                            <th> الاجراءات </th>
                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <td> <span class="value"> </span> </td>
                            <td><span class="value"></span></td>
                            <td><span class="value"></span></td>
                            <td><span class="value"></span></td>
                            <td><span class="value"></span></td>
                            <td><span class="value"></span></td>
                            <td><span class="value"></span></td>
                        </tr>

                    </tbody>
                </table>
            </div>



        </div>

    </section>

</x-main-layout>
