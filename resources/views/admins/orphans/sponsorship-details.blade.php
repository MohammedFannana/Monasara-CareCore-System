<x-main-layout title="1000 أمل لأبناء الشهداء">


    <div class="header fw-bold" style="color: var(--primary-color);font-size:18px;  border-top-right-radius: 6px;border-top-left-radius:6px; padding:10px;background:linear-gradient(to left , #c6fdda , #edfaf1)">
        مرحبًا بك في لوحة الآدمن
        <p class="fw-normal mt-2" style="font-size: 15px;color:var(--text-color)">
            يعرض هذا القسم تفاصيل الكفالات في النظام، حيث يمكنك الاطلاع على كفالات الأيتام الشخصية.  مما يسهل عملية الإشراف والإدارة بشكل شامل ومنظم.
        </p>
    </div>

    <x-alert name="success" class="mt-2"/>
    <x-alert name="danger" class="mt-2"/>

    <section class="family-information mt-5">

        <div class="rounded">

            <div class="mt-4">
                <div class="d-flex justify-content-between mb-3">
                    <p class="fs-5 fw-semibold"> تفاصيل الكفالات </p>
                    {{-- <a href="{{route('admin.orphan.create')}}" class="submit-btn text-decoration-none">+ إضافة يتيم </a> --}}
                </div>

            </div>

            <div class="table-responsive">
                <table  class=" border-0 w-100 text-center" style="border-collapse: collapse;">

                    <thead>
                        <tr>
                            {{-- <th>اسم اليتيم</th> --}}
                            <th> اسم الكافل  </th>
                            <th> تاريخ بدأ الكفالة  </th>
                            <th>  مدة الكفالة </th>
                            <th> التاريخ القادم للدفع </th>
                            <th> مبلغ الكفالة  </th>
                            <th> الإجمالي </th>
                            <th> حالة الكفالة </th>
                            <th> وصل الدفع  </th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($sponsorships as $sponsorship)

                            @php
                                $startDate = \Carbon\Carbon::parse($sponsorship->sponsorship_date);

                                // تأكد أن المدة رقمية
                                $duration = is_numeric($sponsorship->duration) ? (int)$sponsorship->duration : 0;

                                // انسخ التاريخ وأضف عدد الأشهر إذا المدة صحيحة
                                $endDate = $startDate->copy();
                                if ($duration > 0) {
                                    $endDate->addMonths($duration);
                                }
                            @endphp


                            <tr>
                                {{-- <td> <span class="value"> {{$orphan->id}}         </span> </td> --}}
                                {{-- <td> <span class="value"> {{$orphan->name}}       </span> </td> --}}
                                <td><span class="value">  {{$sponsorship->sponsor->name}}     </span></td>
                                <td><span class="value">  {{$sponsorship->sponsorship_date}}       </span></td>
                                <td><span class="value"> {{$sponsorship->duration}}   </span></td>
                                <td><span class="value">  {{ $endDate->format('Y-m-d') }}     </span></td>
                                <td><span class="value">  {{$sponsorship->bail_amount}}  </span></td>
                                <td><span class="value">  {{$sponsorship->total}}  </span></td>
                                <td><span class="value">  @if ($sponsorship->status == 'active') مفعلة @else منتهية @endif </span></td>
                                <td>
                                    <a href="{{route('orphan.primary.image' , ['file' => encrypt($sponsorship->payment_received)])}}" type="button" class="text-decoration-none file-image p-2">
                                        <img src="{{asset('images/elements.png')}}" alt="" width="22px" height="22px" >
                                        وصل الدفع
                                    </a>
                                </td>



                            </tr>

                        @empty

                             <tr>
                                <td colspan="7" class="text-center fs-5" style="color:var(--primary-color)">
                                    لا يوجد اي حالة كفالة  مسجلة في النظام
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>



        </div>

    </section>

    {{$sponsorships->withQueryString()->links()}}

</x-main-layout>
