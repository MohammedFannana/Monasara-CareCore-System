<x-main-layout title="1000 أمل لأبناء الشهداء">

    <section class="mt-1">


        <x-alert name="success" />
        <x-alert name="danger" />



        {{-- section header component --}}
        <x-header1 title=" ارسال بريد الكتروني " description=" في هذا القسم يمكنك ارسال بريد الكتروني الى جميع الكفلاء في النظام من خلال تعبئة البيانات الأساسية . يرجى التأكد من صحة البيانات قبل الحفظ. "/>

        <div class="rounded mt-3" style="border-top-color:#f0fff4 !important">

            <div class="mt-4 mb-4 row">

                <form action="{{ route('admin.sponsors.email.send') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="row  ms-1 me-1">

                        <div class="col-12  mb-4">
                            <x-form.input name="subject"  type="text" id="subject" label="موضوع الرسالة " placeholder=" ادخل موضوع الرسالة " />
                        </div>



                        <div class="col-12 mb-4">
                            <label class="form-label">محتوى الرسالة</label>
                            <textarea name="message" rows="6" class="form-control" required></textarea>
                        </div>


                        <div class="d-flex justify-content-center gap-4 mt-4">
                            <button class="submit-btn mb-4"  type="submit"> ارسال إلى جميع الكفلاء </button>
                        </div>

                    </div>


                </form>

            </div>

        </div>

    </section>

</x-main-layout>
