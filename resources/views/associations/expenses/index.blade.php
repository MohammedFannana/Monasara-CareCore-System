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
            من خلال هذه الصفحة يمكنكم عرض إيصال الدفع الخاص بالكفالة، وتحديد المبلغ والمدة، بالإضافة إلى إرفاق رسالة شكر مصورة .
        </p>
    </div>

    <x-alert name="success" />
    <x-alert name="danger" />

    <section class="family-information mt-5">

        {{--    --}}


        <div class="rounded">

            <div class="intro flex-wrap justify-content-between mb-3" style="display: flex">
                <p class="fs-5 fw-semibold"> قائمة المبالغ المدفوعة </p>
                <a href="{{route('association.expenses.create')}}" class="submit-btn text-decoration-none"> إيصال الكفالة </a>
            </div>

            <div class="intro2  justify-content-between flex0-wrap align-items-center" style="display: none">

                {{-- title and count --}}
                <div class="">
                    <p class="fs-5 checkbox-count mt-2"> عنصر</p>
                </div>


                {{-- button --}}
                <div class="d-flex gap-2 align-items-start">

                    {{-- this button use to send orphan_ids[] input to {{route('association.expenses.active')}} --}}
                    {{-- id="export_pdf" --}}
                    <a href="{{route('association.expenses.active')}}" type="button"  class="submit-btn text-decoration-none"  id="submit_two_form" style="border-radius: 5px"> ارسال للكافل </a>

                    <button id="reset_button" class="btn btn-danger" style="padding: 9px 24px"> {{__('الغاء')}} </button>
                </div>

            </div>

            <form action="{{route('association.expenses.index')}}" method="GET" class="search custom-sm-style w-100">
                @csrf
                <div class="input-group flex-nowrap mb-4">

                    <input type="text" name="search" class="form-control" placeholder="{{__('البحث عن يتيم...')}}"  aria-describedby="addon-wrapping">
                    <button type="submit" class="input-group-text" id="addon-wrapping" >
                        <svg xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 512 512"><path fill="#1e9448" d="M384 208A176 176 0 1 0 32 208a176 176 0 1 0 352 0zM343.3 366C307 397.2 259.7 416 208 416C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208c0 51.7-18.8 99-50 135.3L507.3 484.7c6.2 6.2 6.2 16.4 0 22.6s-16.4 6.2-22.6 0L343.3 366z"/></svg>
                    </button>
                </div>

            </form>

            <div class="table-responsive">
                <table  class=" border-0 w-100 text-center" style="border-collapse: collapse;">

                    <thead>
                        <tr>
                            <th></th>
                            <th>#</th>
                            <th>اسم اليتيم</th>
                            <th> مدة الكفالة </th>
                            <th>مبلغ الكفالة</th>
                            <th> إيصال الدفع </th>
                            <th>  رسالة شكر  </th>
                            <th> صورة تسليم الكفالة </th>
                            <th> الإجراءات </th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($expenses as $expense)

                            <tr>
                                <td>
                                    @if ($expense->status == "pending")
                                        <input type="checkbox" name="expense_ids[]" value="{{ $expense->id }}" class="convert_waiting_checkbox" id="expense_{{ $expense->id }}">
                                    @else
                                        -
                                    @endif
                                </td>
                                <td> <span class="value"> {{$expense->orphan_id}}           </span> </td>
                                <td><span class="value">  {{$expense->orphan->name}}         </span></td>
                                <td><span class="value">  {{$expense->duration}}    </span></td>
                                <td><span class="value">  {{$expense->bail_amount}}        </span></td>
                                <td>
                                    <span class="value">
                                        <a href="{{route('orphan.primary.image' , ['file' => encrypt($expense->payment_received)])}}" type="button" class="text-decoration-none file-image p-2">
                                            <img src="{{asset('images/elements.png')}}" alt="" width="22px" height="22px" >
                                            إيصال الدفع
                                        </a>
                                    </span>
                                </td>

                                @if ($expense->thank_letter_audio || $expense->thank_letter_video)

                                    <td>

                                        @if ($expense->thank_letter_video)
                                            <span class="value d-inline-block mb-1">
                                                <a href="{{ $expense->thank_letter_video }}" target="_blank" type="button" class="text-decoration-none file-image p-2">
                                                    <img src="{{asset('images/video.png')}}" alt="" width="22px" height="22px" >
                                                    رسالة شكر فيديو
                                                </a>
                                            </span>
                                        @endif


                                        {{-- @if ($expense->thank_letter_video)
                                            <span class="value d-inline-block mb-1">
                                                <a href="{{ route('orphan.primary.video', ['url' => urlencode($expense->thank_letter_video)]) }}"
                                                class="text-decoration-none file-image p-2">

                                                    <img src="{{ asset('images/video.png') }}" alt="" width="22" height="22">
                                                    رسالة شكر فيديو
                                                </a>
                                            </span>
                                        @endif --}}

                                        @if ($expense->thank_letter_audio)
                                            <span class="value">
                                                <a href="{{route('orphan.primary.audio' , ['file' => encrypt($expense->thank_letter_audio)])}}" type="button" class="text-decoration-none file-image p-2">
                                                    <img src="{{asset('images/audio.png')}}" alt="" width="22px" height="22px" >
                                                    رسالة شكر صوتية
                                                </a>
                                            </span>
                                        @endif



                                    </td>
                                @endif
                                
                                <td>
                                    <span class="value">
                                        <a href="{{route('orphan.primary.image' , ['file' => encrypt($expense->delivery_bail)])}}" type="button" class="text-decoration-none file-image p-2">
                                            <img src="{{asset('images/elements.png')}}" alt="" width="22px" height="22px" >
                                            صورة تسليم الكفالة
                                        </a>
                                    </span>
                                </td>

                                <td class="d-flex gap-2">


                                    <div>
                                        <button  class="submit border-0 p-0 bg-transparent d-flex btn-delete" style="gap:7px">
                                            <img src="{{asset('images/Delete.svg')}}" alt="">
                                        </button>

                                        <form  action="{{route('association.expenses.destroy' , $expense->id)}}" method="post" style="display: none">
                                            @csrf
                                            @method('delete')

                                        </form>

                                    </div>

                                    <a href="{{route('association.expenses.edit' , $expense->id)}}">
                                        <img src="{{asset('images/Edit Square.svg')}}" alt="">
                                    </a>



                                </td>

                            </tr>

                        @empty

                             <tr>
                                <td colspan="8" class="text-center fs-5 rounded text-danger">
                                    {{__('لا يوجد مبالغ مدفوعة في النظام')}}
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>



        </div>

    </section>

    {{$expenses->withQueryString()->links()}}

    @push('scripts')

        {{-- to show and hide the intro and intro2 --}}
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function () {
                // عند تغيير حالة أي checkbox
                $('.convert_waiting_checkbox').change(function () {
                    // حساب عدد الـ checkbox المحددة
                    var selectedCount = $('.convert_waiting_checkbox:checked').length;

                    if (selectedCount > 0) {
                        // إذا تم اختيار أي checkbox
                        $('.intro').hide();
                        $('.intro2').show().addClass('d-flex'); // إضافة d-flex لـ intro2
                        $('.intro2 .checkbox-count').text(selectedCount + ' {{ __("حالة") }}'); // تحديث العدد
                    } else {
                        // إذا لم يتم اختيار أي checkbox
                        $('.intro').show();
                        $('.intro2').hide().removeClass('d-flex'); // إزالة d-flex من intro2
                    }
                });
            });
        </script>

        {{-- to show and hide the associations_list and make reset button work --}}
        <script>
            $(document).ready(function () {

                // عند النقر على reset_button
                $('#reset_button').click(function (e) {
                    e.preventDefault(); // منع السلوك الافتراضي للزر

                    // إخفاء associations_list
                    $('#associations_list').hide();

                    // إعادة تعيين جميع الـ checkbox في orphan_checkbox
                    $('.convert_waiting_checkbox').prop('checked', false);

                    // إعادة عرض intro وإخفاء intro2
                    $('.intro').show();
                    $('.intro2').hide().removeClass('d-flex');
                });

                // عند النقر في أي مكان خارج associations_list
                $(document).mouseup(function (e) {
                    var container = $('#associations_list');
                    if (!container.is(e.target) && container.has(e.target).length === 0) {
                         container.hide(); // إخفاء associations_list
                    }
                });
            });
        </script>

        {{-- when click in submit_two_form button send associations_list and checkbox input--}}

        <script>
            document.getElementById('submit_two_form').addEventListener('click', function(event) {
                event.preventDefault(); // منع السلوك الافتراضي للرابط

                let orphanCheckboxes = document.querySelectorAll('.convert_waiting_checkbox:checked'); // الحصول على الأيتام المختارين
                if (orphanCheckboxes.length === 0) {
                    alert("يرجى اختيار يتيم واحد على الأقل.");
                    return;
                }

                let orphanIds = Array.from(orphanCheckboxes).map(checkbox => checkbox.value).join(','); // جمع المعرفات بفاصلة

                // توجيه المستخدم إلى الـ route مع المعرفات المحددة
                window.location.href = `{{ route('association.expenses.active') }}?orphan_ids=${orphanIds}`;
            });
        </script>


    @endpush
</x-main-layout>
