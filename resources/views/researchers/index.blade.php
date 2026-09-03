<x-main-layout title="1000 أمل لأبناء الشهداء">

    @push('styles')

        <style>
            .color-text{
                color:rgba(36, 36, 36, 0.6);
            }

        </style>

    @endpush


    <div class="header fw-bold" style="color: var(--primary-color);font-size:18px;  border-top-right-radius: 6px;border-top-left-radius:6px; padding:10px;background:linear-gradient(to left , #c6fdda , #edfaf1)">
            مرحبًا بك في لوحة الباحثين
        <p class="fw-normal mt-2" style="font-size: 15px;color:var(--text-color)">
            يعرض هذا القسم الأيتام المرشحين في النظام، حيث يمكنك الاطلاع على ملفاتهم الشخصية، مراجعة بياناتهم، وتحديثها عند الحاجة.  مما يسهل عملية الإشراف والإدارة بشكل شامل ومنظم.
        </p>
    </div>


    <x-alert name="success" class="mt-2"/>
    <x-alert name="danger" class="mt-2"/>

    <section class="mt-5">

        <div class="rounded">

            <div class="mt-4">
                <div class="d-flex row flex-wrap justify-content-between mb-3">
                    <p class="fs-5 fw-semibold col-12 col-sm-4"> قائمة الأيتام المرشحين </p>

                    <form action="{{route('researcher.orphan.index')}}" method="GET" class="search custom-sm-style col-12 col-sm-8">

                        <div class="input-group flex-nowrap mb-4">

                            <input type="text" name="search" class="form-control" placeholder="{{__('البحث عن يتيم...')}}"  aria-describedby="addon-wrapping">
                            <button type="submit" class="input-group-text" id="addon-wrapping" >
                                <svg xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 512 512"><path fill="#1e9448" d="M384 208A176 176 0 1 0 32 208a176 176 0 1 0 352 0zM343.3 366C307 397.2 259.7 416 208 416C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208c0 51.7-18.8 99-50 135.3L507.3 484.7c6.2 6.2 6.2 16.4 0 22.6s-16.4 6.2-22.6 0L343.3 366z"/></svg>
                            </button>
                        </div>

                    </form>

                </div>
            </div>

            <div class="table-responsive">
                <table  class=" border-0 w-100 text-center" style="border-collapse: collapse;">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم اليتيم</th>
                            <th>  الجنس </th>
                            <th>  الدولة </th>
                            <th>  اسم الجمعية </th>
                            <th> حالة اليتيم </th>
                            <th>التدقيق</th>
                            <th> الاجراءات </th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($orphans as $orphan)

                            <tr>
                                <td> <span class="value"> {{$orphan->id}}         </span> </td>
                                <td> <span class="value"> {{$orphan->name}}       </span> </td>
                                <td><span class="value">  {{$orphan->gender}}     </span></td>
                                <td><span class="value">  {{$orphan->country}}       </span></td>
                                <td><span class="value">  {{$orphan->association->name}}  </span></td>
                                <td><span class="value">  {{$orphan->orphan_status}} </span></td>
                                <td><span class="value"> @if ($orphan->isAuditor()) تم التدقيق  @elseif($orphan->isCandidate())  لم يُدقَّق @endif </span></td>

                                <td style="position: relative;">

                                    <img class="show-action" src="{{asset('images/Group 8.svg')}}" alt="">

                                    <div class="action" >
                                        <a href="{{route('researcher.orphan.view' , $orphan->id)}}" class="text-decoration-none mb-1">
                                            <img src="{{asset('images/Show.svg')}}" alt="">
                                            <span style="color: var(--text-color);">{{__('عرض التفاصيل')}}</span>
                                        </a>

                                        <a href="{{route('researcher.orphans.edit' , $orphan->id)}}" class="text-decoration-none mb-1" style="gap: 10px">
                                            <img src="{{asset('images/Edit Square.svg')}}" alt="">
                                            <span style="color: var(--text-color);">{{__(' تعديل البيانات')}}</span>
                                        </a>

                                        @if (Gate::denies('complete-orphan-data', $orphan) && $orphan->isCandidate())

                                            <a href="{{route('researcher.orphan.review' , $orphan->id)}}" class="text-decoration-none mb-1" style="gap: 10px">
                                                <img src="{{asset('images/Edit Square.svg')}}" alt="">
                                                <span style="color: var(--text-color);">{{__(' مراجعة الحالة ')}}</span>
                                            </a>

                                        @endif

                                    </div>

                                </td>
                            </tr>

                        @empty

                             <tr>
                                <td colspan="8" class="text-center fs-5" style="color:var(--primary-color)">
                                    لا يوجد أيتام  مسجلين في النظام
                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>



        </div>

    </section>

    {{$orphans->withQueryString()->links()}}


</x-main-layout>
