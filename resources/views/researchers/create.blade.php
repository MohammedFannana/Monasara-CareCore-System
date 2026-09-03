<x-main-layout title="1000 أمل لأبناء الشهداء">

    @push('styles')

        <style>
            .color-text{
                color:rgba(36, 36, 36, 0.6);
            }

        </style>

    @endpush


    <div class="header fw-bold mb-3" style="color: var(--primary-color);font-size:18px;  border-top-right-radius: 6px;border-top-left-radius:6px; padding:10px;background:linear-gradient(to left , #c6fdda , #edfaf1)">
            مرحبًا بك في لوحة الباحثين
        <p class="fw-normal mt-2" style="font-size: 15px;color:var(--text-color)">
            يتم في هذا القسم اضافة الأيتام إضافة أولية
        </p>
    </div>


    <x-alert name="success" class="mt-2"/>
    <x-alert name="danger" class="mt-2"/>

    <form action="{{route('researcher.orphans.first.store')}}" method="post" enctype="multipart/form-data">
        @csrf

        <section class="basic-information">

            {{-- section header component --}}
            <x-header title=" البيانات الأساسية لليتيم" />

            <div class="border border-1 rounded" style="border-top-color:#f0fff4 !important">

                <div class="mt-4 mb-4 ms-3 me-3">

                    <div class="row mb-3">

                        {{-- orphan-name --}}
                        <div class="col-12 col-md-6  mb-3">
                            <x-form.input name="name" class="" type="text" id="name" label="اسم اليتيم كامل" placeholder="ادخل الاسم" />
                        </div>

                        {{-- birth-date --}}
                        <div class="col-12 col-md-6  mb-3">
                            <x-form.input name="birth_date"  type="date" id="birth-date" label="تاريخ الميلاد" />
                        </div>


                        {{-- id_number --}}
                        <div class="col-12 col-md-6 mb-3">
                            <x-form.input name="id_number" type="text" id="id_number" label=" رقم هوية اليتيم " placeholder="ادخل رقم هوية اليتيم" />
                        </div>

                        <div class="col-12 col-md-6 mb-3">
                            <x-form.input name="nominating_authority" type="text" id="nominating_authority" label=" جهة الترشيح " placeholder="ادخل جهة الترشيح" />
                        </div>

                    </div>


                </div>

                <div class="d-flex justify-content-center gap-4 mt-4">
                    <button class="submit-btn mb-4"  type="submit"> حفظ البيانات </button>
                </div>
            </div>


        </section>

    </form>

</x-main-layout>
