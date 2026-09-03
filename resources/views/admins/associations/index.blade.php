<x-main-layout title="1000 أمل لأبناء الشهداء">

    @push('styles')



    @endpush

    <x-alert name="success" />
    <x-alert name="danger" />


    <div class="header fw-bold" style="color: var(--primary-color);font-size:18px;  border-top-right-radius: 6px;border-top-left-radius:6px; padding:10px;background:linear-gradient(to left , #c6fdda , #edfaf1)">
        مرحبًا بك في لوحة الأدمن
        <p class="fw-normal mt-2" style="font-size: 15px; color:var(--text-color)" >
            يعرض هذا القسم الجمعيات المسجلة وبياناتها، مع إمكانية تحديثها ومتابعة الجمعيات الفرعية التابعة لها لتسهيل الإشراف والإدارة.
        </p>
    </div>

    <section class="family-information mt-5">

        {{--    --}}


        <div class="rounded">

            <div class="mt-5">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <p class="fs-5 fw-semibold custom-sm-style mb-1" style="color: var(--primary-color)"> قائمة الجمعيات في النظام </p>

                    @can('show-admin')
                    <a href="{{route('admin.association.create')}}" class="submit-btn text-decoration-none ">+ إضافة جمعية </a>
                    @endcan
                </div>

                <form action="{{route('admin.association.index')}}" method="GET" class="search custom-sm-style w-100">

                    @csrf
                    <div class="input-group flex-nowrap mb-4">

                        <input type="text" name="search" class="form-control" placeholder="{{__('البحث عن جمعية')}}"  aria-describedby="addon-wrapping">
                        <button type="submit" class="input-group-text" id="addon-wrapping" >
                            <svg xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 512 512"><path fill="#1e9448" d="M384 208A176 176 0 1 0 32 208a176 176 0 1 0 352 0zM343.3 366C307 397.2 259.7 416 208 416C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208c0 51.7-18.8 99-50 135.3L507.3 484.7c6.2 6.2 6.2 16.4 0 22.6s-16.4 6.2-22.6 0L343.3 366z"/></svg>
                        </button>
                    </div>

                </form>

            </div>

            <div class="table-responsive">
                <table  class=" border-0 w-100 text-center" style="border-collapse: collapse;">

                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>العنوان</th>
                            <th>الشخص المسؤول</th>
                            <th>  الايميل </th>
                            @can('show-admin')
                            <th> الاجراءات </th>
                            @endcan
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($associations as $association)

                            <tr>
                                <td><span class="value">  {{$association->id}}                 </span></td>
                                <td> <span class="value"> {{$association->name}}                  </span> </td>
                                <td><span class="value">  {{$association->address}}               </span></td>
                                <td><span class="value">  {{$association->responsible_person}}    </span></td>
                                <td><span class="value">  {{$association->email}}                 </span></td>

                                @can('show-admin')
                                <td style="position: relative;">

                                    <img class="show-action" src="{{asset('images/Group 8.svg')}}" alt="">

                                    <div class="action" >
                                        <a href="{{route('admin.association.show' , $association->id)}}" class="text-decoration-none mb-1">
                                            <img src="{{asset('images/Show.svg')}}" alt="">
                                            <span style="color: var(--text-color);">{{__('عرض التفاصيل')}}</span>
                                        </a>


                                        <a href="{{route('admin.association.edit' , $association->id)}}" class="text-decoration-none mb-1" style="gap: 10px">
                                            <img src="{{asset('images/Edit Square.svg')}}" alt="">
                                            <span style="color: var(--text-color);">{{__(' تعديل البيانات')}}</span>
                                        </a>

                                        <button  class="submit border-0 p-0 bg-transparent d-flex btn-delete" style="gap:7px">
                                            <img src="{{asset('images/Delete.svg')}}" alt="">
                                            <span style="color: var(--text-color);">{{__(' حذف الجمعية ')}}</span>
                                        </button>

                                        <form  action="{{route('admin.association.destroy' , $association->id)}}" method="post" style="display: none">
                                            @csrf
                                            @method('delete')

                                        </form>
                                    </div>

                                </td>
                                @endcan
                            </tr>

                        @empty

                             <tr>
                                <td colspan="6" class="text-center fs-5 rounded" style="color:var(--primary-color)">
                                    {{__('لا يوجد جمعيات مسجلة في النظام')}}
                                </td>
                            </tr>

                        @endforelse






                    </tbody>
                </table>
            </div>



        </div>

    </section>

    {{$associations->withQueryString()->links()}}


</x-main-layout>
