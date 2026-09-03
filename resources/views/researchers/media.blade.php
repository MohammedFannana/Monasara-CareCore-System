<x-main-layout title="1000 أمل لأبناء الشهداء">

    @push('styles')

        <style>
            .color-text{
                color:rgba(36, 36, 36, 0.6);
            }

        </style>

        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />


    @endpush


    <div class="header fw-bold" style="color: var(--primary-color);font-size:18px;  border-top-right-radius: 6px;border-top-left-radius:6px; padding:10px;background:linear-gradient(to left , #c6fdda , #edfaf1)">
            مرحبًا بك في لوحة الباحثين
        <p class="fw-normal mt-2" style="font-size: 15px;color:var(--text-color)">
            يعرض هذا القسم الوسائط الخاصة بالأيتام التابعة للجمعية، حيث يتيح للباحث الاجتماعي إضافة الصور ومقاطع الفيديو التي توثق حالة اليتيم وتطور أوضاعه المعيشية والتعليمية
        </p>
    </div>


    <x-alert name="success" class="mt-2"/>
    <x-alert name="danger" class="mt-2"/>

    <section class="mt-5">

        <div class="rounded">


            <form method="POST" action="{{ route('researcher.orphan.media.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="w-75 mb-3">
                    <label for="orphan_id"> اختر اليتيم </label>
                    <select name="orphan_id" id="orphan_id" class="form-control" required>
                        <option value="">اختر اليتيم</option>
                        @foreach($orphans as $orphan)
                            <option value="{{ $orphan->id }}">
                                {{ $orphan->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <br>

                <div class="w-75 mb-3">
                    <label class="mb-2 fw-bold"> شاهد يتيمك (صور، فيديو) </label> <br>
                    <label for="media" class="custom-file-upload text-center" style="width: 100%;color:#777a78;">
                        <img src="{{asset('images/file.png')}}" alt="" width="50px" height="50px"> <br>
                        اسحب الملف هنا أو اضغط لاختياره
                    </label>
                    <x-form.input name="media" class="hidden-file-style" type="file" id="media" style="display: none;"/>
                </div>

                <br>

                <div class="w-75 mb-3">
                    <label for="note"> ملاحظة (اختياري) </label>
                    <textarea name="note" class="form-control" placeholder="ملاحظة (اختياري)"></textarea>
                </div>

                <button type="submit" class="btn btn-primary mt-2">رفع</button>
            </form>

        </div>

    </section>


    @push('scripts')

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script   script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            $(document).ready(function () {
                $('#orphan_id').select2({
                    placeholder: "ابحث عن اليتيم",
                    allowClear: true,
                    width: '100%'
                });
            });
        </script>


    @endpush

</x-main-layout>
