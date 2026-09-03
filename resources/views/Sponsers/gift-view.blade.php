<x-main-layout title="1000 أمل لأبناء الشهداء">

    @push('styles')

        <style>
            .color-text{
                color:rgba(36, 36, 36, 0.6);
            }

        </style>

    @endpush


    <div class="header fw-bold" style="color: var(--primary-color);font-size:18px;  border-top-right-radius: 6px;border-top-left-radius:6px; padding:10px;background:linear-gradient(to left , #c6fdda , #edfaf1)">
        مرحبًا بك في لوحة الكافلين
        <p class="color-text fw-normal mt-2" style="font-size: 15px">
            يعرض هذا القسم يعرض الهدايا المقدمة للأيتام المكفولين ، بما في ذلك بياناتهم الشخصية،          .
        </p>
    </div>

    <x-alert name="success" />
    <x-alert name="danger" />

    <section class="family-information mt-5">

        <div class="rounded">

            <div class="d-flex justify-content-between mb-3">
                <p class="fs-5 fw-semibold"> قائمة الهدايا الخاصة ب المكفولين </p>
            </div>



            <div class="table-responsive">
                <table  class=" border-0 w-100 text-center" style="border-collapse: collapse;">

                    <thead>
                        <tr>

                            <th>  المبلغ المدفوع</th>
                            <th>  تاريخ  الهدية </th>
                            <th> صورة الوصل  </th>

                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($gifts as $gift)

                            <tr>


                                <td><span class="value">  <b> {{$gift->amount}} </b>    @if($gift->amount) دينار بحريني @endif  </span></td>
                                <td><span class="value">  {{$gift->gift_date}} </span></td>
                                <td>
                                    <span class="value">
                                        @if ($gift->payment_received)
                                            <a href="{{route('orphan.primary.image' , ['file' => encrypt($gift->payment_received)])}}" type="button" class="text-decoration-none d-inline-block mb-2 file-image p-2">
                                                <img src="{{asset('images/elements.png')}}" alt="" width="22px" height="22px" >
                                                صورة الوصل
                                            </a>
                                        @else
                                        -
                                        @endif
                                    </span>
                                </td>

                            </tr>

                        @empty

                             <tr>
                                <td colspan="9" class="text-center fs-5 rounded text-danger">
                                    {{__('لا يوجد أيتام كفالات لهذا اليتيم في النظام')}}
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>



        </div>

    </section>

     {{$gifts->withQueryString()->links()}}
</x-main-layout>
