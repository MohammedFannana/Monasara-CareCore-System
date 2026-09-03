<x-main-layout title="1000 أمل لأبناء الشهداء">

    <section class="mt-1">

        <x-alert name="success" />
        <x-alert name="danger" />



        {{-- section header component --}}
        {{-- description=" في هذا القسم يمكنك عرض بيانات جمعية ({{$association->name}}) في النظام . " --}}
        <x-header1 title=" عرض تفاصيل اليتيم " description=" في هذا القسم يمكنك عرض تفاصيل اليتيم ({{$orphan->name}}) في النظام . "/>

        <div class="rounded mt-3 ms-2 me-2" style="border-top-color:#f0fff4 !important">

            <div class="row background border rounded p-3">

                <div class="col-sm-12 col-md-4 col-lg-3 d-flex justify-content-between">
                    @if ($orphan->gender === "ذكر")

                        <img src="{{asset('images/boy.jpg')}}" alt="" width="149px" height="149px">

                    @elseif ($orphan->gender === "أنثى")

                        <img src="{{asset('images/girl.png')}}" alt="" width="149px" height="149px">

                    @endif
                </div>

                <div class="col-sm-12 col-md-8 col-lg-9 ps-2">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <p class="fw-bold fs-5"> {{ collect(explode(' ', $orphan->name))->take(2)->implode(' ') }} </p>
                        <form action="{{ route('sponsor.orphan.create') }}" method="POST" class="text-right">
                            @csrf
                            <input type="hidden" name="orphans_ids[]" value="{{ $orphan->id }}">
                            <button type="submit" class="btn submit-btn m-0 p-0 text-decoration-none mb-1" style="padding: 5px 15px !important">
                                <img src="{{asset('images/Edit Square.svg')}}" alt="">
                                {{ __(' اكفل الآن ') }}
                            </button>
                        </form>
                    </div>

                    <div class="row">

                        <div class="col-12 col-sm-6 col-md-6  mb-3">
                            <span class="fw-bold"> حالة اليتيم: </span>
                            <span class="value"> {{ $orphan->orphan_status }} </span>
                        </div>


                        <div class="col-12 col-sm-6 col-md-6  mb-3">
                            <span class="fw-bold"> تاريخ الميلاد : </span>
                            <span class="value"> {{ $orphan->birth_date }} </span>
                        </div>

                        @php

                            $birthDate =  \Carbon\Carbon::parse($orphan->birth_date);
                            $age = $birthDate->age;

                        @endphp

                        <div class="col-12 col-sm-6 col-md-6  mb-3">
                            <span class="fw-bold"> العمر : </span>
                            <span class="value"> {{ $age }} </span>
                        </div>

                        <div class="col-12 col-sm-6 col-md-6  mb-3">
                            <span class="fw-bold">   الجنس: </span>
                            <span class="value"> {{ $orphan->gender }}</span>
                        </div>

                        @if ($orphan->nominating_authority)
                            <div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-3">
                                <span class="fw-bold">  جهة الترشيح : </span>
                                <span class="value"> {{ $orphan->nominating_authority }}</span>
                            </div>
                        @endif



                        {{-- <div class="col-12 col-sm-6 col-md-6  mb-3">
                            <span class="fw-bold">  مكان الميلاد : </span>
                            <span class="value"> {{ $orphan->birth_place }} </span>
                        </div>

                        <div class="col-12 col-sm-6 col-md-6  mb-3">
                            <span class="fw-bold">  الدولة : </span>
                            <span class="value"> {{ $orphan->country }}</span>
                        </div> --}}



                    </div>


                </div>

            </div>

        </div>

    </section>

</x-main-layout>
