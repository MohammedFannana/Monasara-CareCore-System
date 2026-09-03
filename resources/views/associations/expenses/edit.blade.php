<x-main-layout title="1000 أمل لأبناء الشهداء">

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
    .select2-container .select2-selection--single {
        height: 40px; /* مثل Bootstrap input */
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 40px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px;
    }
</style>

    @endpush

    <section class="mt-1">

        <x-alert name="success" />
        <x-alert name="danger" />



        {{-- section header component --}}
        <x-header1 title=" رفع إثبات الدفع ورسالة الشكر" description=" من خلال هذه الصفحة يمكنكم رفع إيصال الدفع الخاص بالكفالة، وتحديد المبلغ والمدة، بالإضافة إلى إرفاق رسالة شكر مصورة . "/>



        <div class="rounded mt-3" style="border-top-color:#f0fff4 !important">

            <div class="m-4 row">


                <form action="{{route('association.expenses.update' , $expense->id)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    {{-- <input type="hidden" name="orphan_id" value="{{$orphan->id}}"> --}}

                    <div class="row">

                        <div class="col-12 col-md-7  mb-3">
                            <label class="mb-2 fw-bold"> اسم اليتيم </label>
                            <div>
                                {{--  id="orphan-select" --}}
                                <select name="orphan_id"  class="form-control form-select"  disabled>
                                    <option value=""> اختر اسم اليتيم </option>
                                    @foreach ($orphans as $orphan)
                                        <option value="{{$orphan->id}}" @selected($orphan->id ==  $expense->orphan_id)> {{$orphan->name}}</option>
                                    @endforeach
                                </select>


                                @error('orphan_id')
                                    <div class="text-danger">
                                        {{$message}}
                                    </div>
                                @enderror

                            </div>
                        </div>

                        <div class="col-12 col-md-7 mb-4">
                            <x-form.input name="bail_amount" value="{{$expense->bail_amount}}" type="number" id="bail_amount" label=" المبلغ المدفوع " placeholder="ادخل مبلغ الكفالة" min="1"/>
                        </div>

                        <div class="col-12 col-md-7  mb-4">
                            <x-form.input name="duration" value="{{$expense->duration}}" type="number" id="duration" label=" مدة الكفالة (بالأشهر) " placeholder="ادخل مدة الكفالة" min="1" />
                        </div>

                        <div class="col-12 col-md-8 mb-4">
                            <label class="mb-2 fw-bold"> إرفاق إيصال الدفع </label> <br>

                            <span class="value">
                                <a href="{{route('orphan.primary.image' , ['file' => encrypt($expense->payment_received)])}}" type="button" class="text-decoration-none file-image p-2">
                                    <img src="{{asset('images/elements.png')}}" alt="" width="22px" height="22px" >
                                    إيصال الدفع
                                </a>
                            </span>

                            <label for="payment_received" class="custom-file-upload mt-2 text-center" style="width: 90%;color:#777a78;">
                                <img src="{{asset('images/file.png')}}" alt="" width="40px"> <br>
                                اسحب الملف هنا أو اضغط لاختياره
                            </label>
                            <x-form.input name="payment_received" accept="image/*" class="hidden-file-style" type="file" id="payment_received" style="display: none;"/>

                        </div>

                        <div class="col-12 col-md-7  mb-4">

                                @if ($expense->thank_letter_video)
                                    <span class="value d-inline-block mb-1">
                                        <a href="{{ $expense->thank_letter_video }}" target="_blank" type="button" class="text-decoration-none file-image p-2">
                                            <img src="{{asset('images/video.png')}}" alt="" width="22px" height="22px" >
                                            رسالة شكر فيديو
                                        </a>
                                    </span>
                                @endif

                                <label class="mb-2 fw-bold"> إرفاق رابط رسالة الشكر بالفيديو</label> <br>
                                {{-- <label for="thank_letter_video" class="custom-file-upload text-center" style="width: 90%;color:#777a78;">
                                    <img src="{{asset('images/video.png')}}" alt="" width="40px"> <br>
                                    <div class="file-preview mt-2"></div>

                                    اسحب الملف هنا أو اضغط لاختياره
                                </label> --}}
                                <x-form.input name="thank_letter_video"  type="url" id="thank_letter_video" class="w-100" placeholder="ادخل رابط رسالة الشكر بالفيديو"/>
                        </div>

                        {{-- <div class="col-12 col-md-8 mb-4">

                            <label class="mb-2 fw-bold"> إرفاق رسالة الشكر بالفيديو</label> <br>

                            @if ($expense->thank_letter_video)
                                <span class="value d-inline-block mb-1">
                                    <a href="{{route('orphan.primary.video' , ['file' => encrypt($expense->thank_letter_video)])}}" type="button" class="text-decoration-none file-image p-2">
                                        <img src="{{asset('images/video.png')}}" alt="" width="22px" height="22px" >
                                        رسالة شكر فيديو
                                    </a>
                                </span>
                            @endif




                            <label for="thank_letter_video" class="custom-file-upload mt-2 text-center" style="width: 90%;color:#777a78;">
                                <img src="{{asset('images/video.png')}}" alt="" width="40px"> <br>
                                اسحب الملف هنا أو اضغط لاختياره
                            </label>
                            <x-form.input name="thank_letter_video" accept="video/*" class="hidden-file-style" type="file" id="thank_letter_video" style="display: none;"/>
                        </div> --}}

                        <div class="col-12 col-md-8 mb-4">

                            <label class="mb-2 fw-bold"> إرفاق رسالة الشكر بالصوت</label> <br>

                            @if ($expense->thank_letter_audio)
                                <span class="value">
                                    <a href="{{route('orphan.primary.audio' , ['file' => encrypt($expense->thank_letter_audio)])}}" type="button" class="text-decoration-none file-image p-2">
                                        <img src="{{asset('images/audio.png')}}" alt="" width="22px" height="22px" >
                                        <div class="file-preview mt-2"></div>

                                        رسالة شكر صوتية
                                    </a>
                                </span>
                            @endif


                            <label for="thank_letter_audio" class="custom-file-upload mt-2 text-center" style="width: 90%;color:#777a78;">
                                <img src="{{asset('images/audio.png')}}" alt="" width="40px"> <br>
                                اسحب الملف هنا أو اضغط لاختياره
                            </label>
                            <x-form.input name="thank_letter_audio" accept="audio/*" class="hidden-file-style" type="file" id="thank_letter_audio" style="display: none;"/>
                        </div>


                        <div class="col-12 col-md-8 mb-4">

                            <label class="mb-2 fw-bold"> إرفاق صورة تسليم الكفالة لليتيم</label> <br>

                            <span class="value">
                                <a href="{{route('orphan.primary.image' , ['file' => encrypt($expense->delivery_bail)])}}" type="button" class="text-decoration-none file-image p-2">
                                    <img src="{{asset('images/elements.png')}}" alt="" width="22px" height="22px" >
                                    صورة تسليم الكفالة
                                </a>
                            </span>

                            <label for="delivery_bail" class="custom-file-upload mt-2 text-center" style="width: 90%;color:#777a78;">
                                <img src="{{asset('images/file.png')}}" width="40px" alt=""> <br>
                                اسحب الملف هنا أو اضغط لاختياره
                            </label>
                            <x-form.input name="delivery_bail"  accept="image/*" class="hidden-file-style" type="file" id="delivery_bail" style="display: none;"/>
                        </div>

                    </div>

                    <div class="d-flex justify-content-center">
                        <button type="submit" class="submit-btn w-25"> تحديث البيانات </button>
                    </div>

                </form>

            </div>

        </div>

    </section>

    @push('scripts')
        <!-- JS -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#orphan-select').select2({
                    placeholder: 'اختر اسم اليتيم',
                    allowClear: true,
                    width: '100%' // اجعله يعرض بعرض الحاوية

                });
            });
        </script>

    @endpush
</x-main-layout>
