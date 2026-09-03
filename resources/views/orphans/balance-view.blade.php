<x-main-layout title="1000 أمل لأبناء الشهداء">

    @push('styles')

        <style>
            .color-text{
                color:rgba(36, 36, 36, 0.6);
            }

        </style>

    @endpush


    <div class="header fw-bold" style="color: var(--primary-color);font-size:18px;  border-top-right-radius: 6px;border-top-left-radius:6px; padding:10px;background:linear-gradient(to left , #c6fdda , #edfaf1)">
        مرحبًا بك في لوحة الأيتام
        <p class="color-text fw-normal mt-2" style="font-size: 15px">
            من خلال هذه الصفحة يمكنكم عرض إيصال الدفع الخاص بالكفالة، وتحديد المبلغ والمدة .
        </p>
    </div>

    <section class="family-information mt-5">

        {{--    --}}


        <div class="rounded">

            <section class="family-information mt-2 mb-4">

                {{-- section header component --}}
                <x-header title="  بيانات  الكافل " />

                <div class="border border-1 rounded" style="border-top-color:#f0fff4 !important">

                    <div class="m-2 row">

                        <div class="col-12 col-sm-6 col-md-6 mb-3">
                            <span class="fw-bold"> اسم الكافل: </span>
                            <span class="value"> {{$sponsor->name}} </span>
                        </div>

                        <div class="col-12 col-sm-6 col-md-6 mb-3">
                            <span class="fw-bold">  الدولة: </span>
                            <span class="value"> {{$sponsor->country}} </span>
                        </div>

                    </div>
                </div>

            </section>

            <div class="d-flex justify-content-between mb-3">
                <p class="fs-5 fw-semibold"> قائمة المبالغ المدفوعة </p>
                <p class="text-center p-3  fw-semibold " style="background-color: #cbfcdc; font-size:18px"> {{__('الرصيد الكلي')}} :<span class="fs-5 fw-bold" style="color: var(--primary-color)">{{ $expenseAmount }}</span> </p>

            </div>


            <div class="table-responsive">
                <table  class=" border-0 w-100 text-center" style="border-collapse: collapse;">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th> مدة الكفالة </th>
                            <th>مبلغ الكفالة</th>
                            <th>تاريخ بدأ الكفالة</th>
                            <th> إيصال الدفع </th>
                            {{-- <th>  رسالة شكر  </th> --}}
                            <th> صورة تسليم الكفالة </th>
                            <th>  رسالة شكر  </th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($expenses as $expense)

                            <tr>
                                <td> <span class="value"> {{$expense->id}}           </span> </td>
                                <td><span class="value">  {{$expense->duration}}    </span></td>
                                <td><span class="value">  {{$expense->bail_amount}}        </span></td>
                                <td><span class="value">  {{$expense->created_at->format('y-m-d')}}    </span></td>

                                <td>
                                    <span class="value">
                                        <a href="{{route('orphan.primary.image' , ['file' => encrypt($expense->payment_received)])}}" type="button" class="text-decoration-none file-image p-2">
                                            <img src="{{asset('images/elements.png')}}" alt="" width="22px" height="22px" >
                                            إيصال الدفع
                                        </a>
                                    </span>
                                </td>

                                {{-- <td>
                                    <span class="value">
                                        <a href="{{route('orphan.primary.video' , ['file' => encrypt($expense->thank_letter_video)])}}" type="button" class="text-decoration-none file-image p-2">
                                            <img src="{{asset('images/video.png')}}" alt="" width="22px" height="22px" >
                                            رسالة شكر
                                        </a>
                                    </span>
                                </td> --}}

                                <td>
                                    <span class="value">
                                        <a href="{{route('orphan.primary.image' , ['file' => encrypt($expense->delivery_bail)])}}" type="button" class="text-decoration-none file-image p-2">
                                            <img src="{{asset('images/elements.png')}}" alt="" width="22px" height="22px" >
                                            صورة تسليم الكفالة
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



                            </tr>

                        @empty

                             <tr>
                                <td colspan="5" class="text-center fs-5 rounded text-danger">
                                    {{__('لا يوجد مبالغ مدفوعة لليتيم')}}
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>



        </div>

    </section>

    {{$expenses->withQueryString()->links()}}
</x-main-layout>
