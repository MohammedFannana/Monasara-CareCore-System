<x-main-layout title="1000 أمل لأبناء الشهداء">

    @push('styles')

        <style>
            th {
            background-color: #dff5e1;
            padding: 10px;
            }

            td {
                padding: 10px;
            }

            tbody tr{
                background-color: #fbfbfb
            }
        </style>

    @endpush

    {{-- orphan information --}}

    <div class="row background border rounded p-3">

        <div class="col-sm-12 col-md-4 col-lg-3 d-flex justify-content-between">
            <img src="{{ $orphan->image_url }}" alt="" width="149px" height="149px">
        </div>

        <div class="col-sm-12 col-md-8 col-lg-9 ps-2">
            <p class="fw-bold fs-5"> {{$orphan->name}} </p>

            <div class="row">
                <div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-3">
                    <span class="fw-bold"> رقم الهوية: </span>
                    <span class="value"> {{$orphan->id_number}} </span>
                </div>

                @if($orphan->orphan_status)
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-3">
                        <span class="fw-bold"> حالة اليتيم: </span>
                        <span class="value"> {{ $orphan->orphan_status }} </span>
                    </div>
                @endif

                @if($orphan->city)
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-3">
                        <span class="fw-bold"> المنطقة / المدينة : </span>
                        <span class="value"> {{ $orphan->city }} </span>
                    </div>
                @endif

                @if($orphan->birth_date)
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-3">
                        <span class="fw-bold"> تاريخ الميلاد : </span>
                        <span class="value"> {{ $orphan->birth_date }} </span>
                    </div>
                @endif

                @if($orphan->gender)
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-3">
                        <span class="fw-bold">   الجنس: </span>
                        <span class="value"> {{ $orphan->gender }}</span>
                    </div>
                @endif

                @if($orphan->landmark)
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-3">
                        <span class="fw-bold">  أقرب معلم : </span>
                        <span class="value"> {{ $orphan->landmark }} </span>
                    </div>
                @endif


                @if($orphan->birth_place)
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-3">
                        <span class="fw-bold">  مكان الميلاد : </span>
                        <span class="value"> {{ $orphan->birth_place }} </span>
                    </div>
                @endif

                @if($orphan->country)
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-3">
                        <span class="fw-bold">  الدولة : </span>
                        <span class="value"> {{ $orphan->country }}</span>
                    </div>
                @endif

                @if ($orphan->nominating_authority)
                    <div class="col-12 col-sm-6 col-md-6 col-lg-4 mb-3">
                        <span class="fw-bold">  جهة الترشيح : </span>
                        <span class="value"> {{ $orphan->nominating_authority }}</span>
                    </div>
                @endif



            </div>


        </div>

    </div>

    {{-- family information section --}}

    @if($orphan->mother_name)
        <section class="family-information mt-5">

        {{-- section header component --}}
        <x-header title=" بيانات الأسرة " />

        <div class="border border-1 rounded" style="border-top-color:#f0fff4 !important">

            <div class="m-4 row">


                @if ($orphan->orphan_status == "يتيم الأبوين")

                    <div id="parent_death">
                        <div class="row mb-3">

                            {{-- mother-name --}}
                            @if($orphan->mother_name)
                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <span class="fw-bold"> اسم الأم :</span>
                                    <span class="value"> {{ $orphan->mother_name }} </span>
                                </div>
                            @endif

                            {{-- death_mother_date --}}
                            @if($orphan->death_mother_date)
                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <span class="fw-bold"> تاريخ الوفاة  :</span>
                                    <span class="value"> {{ $orphan->death_mother_date }} </span>
                                </div>
                            @endif

                            {{-- cause_death --}}
                            @if($orphan->cause_mother_death)
                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <span class="fw-bold"> سبب الوفاة  :</span>
                                    <span class="value"> {{ $orphan->cause_mother_death }} </span>
                                </div>
                            @endif

                            {{-- mother_death_certificate or not_available_mother_death --}}
                            @if ($orphan->mother_death_certificate)

                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <a href="{{route('orphan.primary.image' , ['file' => encrypt($orphan->mother_death_certificate)])}}" type="button" class="text-decoration-none file-image p-2">
                                        <img src="{{asset('images/elements.png')}}" alt="" width="22px" height="22px" >
                                        شهادة وفاة الأم
                                    </a>
                                </div>

                            @elseif($orphan->not_available_mother_death)

                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <span class="fw-bold"> سبب عدم توفر شهادة الوفاة  :</span>
                                    <span class="value"> {{ $orphan->not_available_mother_death }} </span>
                                </div>

                            @endif


                            <hr>

                            {{-- father-name --}}
                            @if($orphan->father_name)
                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <span class="fw-bold"> اسم الأب :</span>
                                    <span class="value"> {{$orphan->father_name}} </span>
                                </div>
                            @endif

                            {{-- death_father_date --}}
                            @if($orphan->death_father_date)
                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <span class="fw-bold"> تاريخ الوفاة  :</span>
                                    <span class="value"> {{$orphan->death_father_date}} </span>
                                </div>
                            @endif

                            {{-- cause_death --}}
                            @if($orphan->cause_father_death)
                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <span class="fw-bold"> سبب الوفاة :</span>
                                    <span class="value"> {{$orphan->cause_father_death}} </span>
                                </div>
                            @endif

                            {{-- father_death_certificate or not_available_father_death --}}
                            @if ($orphan->father_death_certificate)

                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <a href="{{route('orphan.primary.image' , ['file' => encrypt($orphan->father_death_certificate)])}}" type="button" class="text-decoration-none file-image p-2">
                                        <img src="{{asset('images/elements.png')}}" alt="" width="22px" height="22px" >
                                        شهادة وفاة الأب
                                    </a>
                                </div>

                            @elseif($orphan->not_available_father_death)

                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <span class="fw-bold"> سبب عدم توفر شهادة الوفاة  :</span>
                                    <span class="value"> {{ $orphan->not_available_father_death }} </span>
                                </div>

                            @endif

                            <hr>

                        </div>
                    </div>

                @elseif ($orphan->orphan_status == "يتيم الأم")

                    <div id="mother_death">

                        <div class="row mb-3">
                            {{-- mother-name --}}
                            @if($orphan->mother_name)
                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <span class="fw-bold"> اسم الأم :</span>
                                    <span class="value"> {{$orphan->mother_name}} </span>
                                </div>
                            @endif

                            {{-- death_mother_date --}}

                            @if($orphan->death_mother_date)
                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <span class="fw-bold"> تاريخ الوفاة  :</span>
                                    <span class="value"> {{$orphan->death_mother_date}} </span>
                                </div>
                            @endif

                            {{-- cause_death --}}
                            @if($orphan->cause_mother_death)
                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <span class="fw-bold"> سبب الوفاة  :</span>
                                    <span class="value"> {{$orphan->cause_mother_death}} </span>
                                </div>
                            @endif

                            {{-- mother_death_certificate or not_available_mother_death --}}
                            @if ($orphan->mother_death_certificate)

                                <div class="col-12 col-md-6 col-lg-3 mb-3">

                                    <a href="{{route('orphan.primary.image' , ['file' => encrypt($orphan->mother_death_certificate)])}}" type="button" class="text-decoration-none file-image p-2">
                                        <img src="{{asset('images/elements.png')}}" alt="" width="22px" height="22px" >
                                        شهادة وفاة الأم
                                    </a>
                                </div>

                            @elseif($orphan->not_available_mother_death)

                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <span class="fw-bold"> سبب عدم توفر شهادة الوفاة  :</span>
                                    <span class="value"> {{ $orphan->not_available_mother_death }} </span>
                                </div>

                            @endif

                            <hr>

                            {{-- father-name --}}
                            @if($orphan->father_name)
                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <span class="fw-bold"> اسم الأب :</span>
                                    <span class="value"> {{$orphan->father_name}} </span>
                                </div>
                            @endif

                            {{-- father_id_number --}}
                            @if($orphan->father_id_number)
                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <span class="fw-bold">رقم  هوية الأب :</span>
                                    <span class="value"> {{$orphan->father_id_number}} </span>
                                </div>
                            @endif

                            {{-- father_phone --}}
                            @if($orphan->father_phone)
                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <span class="fw-bold"> رقم جوال الأب : </span>
                                    <span class="value"> {{$orphan->father_phone}} </span>
                                </div>
                            @endif

                            {{-- cause_death --}}
                            @if($orphan->father_marital_status)
                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <span class="fw-bold"> الحالة الاجتماعية للأب </span>
                                    <span class="value"> {{$orphan->father_marital_status}} </span>
                                </div>
                             @endif

                            <hr>

                        </div>

                    </div>

                @elseif ($orphan->orphan_status == "يتيم الأب")

                    <div id="father_death">
                        <div class="row mb-3">
                            {{-- father-name --}}
                            @if($orphan->father_name)
                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <span class="fw-bold"> اسم الأب : </span>
                                    <span class="value"> {{$orphan->father_name}} </span>
                                </div>
                             @endif

                            {{-- death_father_date --}}
                            @if($orphan->death_father_date)
                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <span class="fw-bold"> تاريخ الوفاة : </span>
                                    <span class="value"> {{$orphan->death_father_date}} </span>
                                </div>
                             @endif

                            {{-- cause_death --}}
                            @if($orphan->cause_father_death)
                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <span class="fw-bold"> سبب الوفاة :</span>
                                    <span class="value"> {{$orphan->cause_father_death}} </span>
                                </div>
                            @endif

                            {{-- father_death_certificate or not_available_father_death --}}
                            @if ($orphan->father_death_certificate)

                                <div class="col-12 col-md-6 col-lg-3 mb-3">

                                    <a href="{{route('orphan.primary.image' , ['file' => encrypt($orphan->father_death_certificate)])}}" type="button" class="text-decoration-none file-image p-2">
                                        <img src="{{asset('images/elements.png')}}" alt="" width="22px" height="22px" >
                                        شهادة وفاة الأب
                                    </a>
                                </div>

                            @elseif($orphan->not_available_father_death)

                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <span class="fw-bold"> سبب عدم توفر شهادة الوفاة  :</span>
                                    <span class="value"> {{ $orphan->not_available_father_death }} </span>
                                </div>

                            @endif

                            <hr>

                            {{-- mother_name --}}
                            @if($orphan->mother_name)
                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <span class="fw-bold"> اسم الأم : </span>
                                    <span class="value"> {{$orphan->mother_name}} </span>
                                </div>
                            @endif

                            {{-- mother_id_number --}}
                            @if($orphan->mother_id_number)
                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <span class="fw-bold"> رقم هوية الأم : </span>
                                    <span class="value"> {{$orphan->mother_id_number}} </span>
                                </div>
                            @endif

                            {{-- mother_phone --}}
                            @if($orphan->mother_phone)
                            <div class="col-12 col-md-6 col-lg-3 mb-3">
                                <span class="fw-bold"> رقم جوال الأم : </span>
                                <span class="value"> {{$orphan->mother_phone}} </span>
                            </div>
                            @endif


                            {{-- mother_marital_status --}}
                            @if($orphan->mother_marital_status)
                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <span class="fw-bold"> الحالة الاجتماعية للأم : </span>
                                    <span class="value"> {{$orphan->mother_marital_status}} </span>
                                </div>
                            @endif

                            <hr>

                        </div>

                    </div>

                @endif

                {{-- income --}}
                @if($orphan->income)
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <span class="fw-bold"> مصادر دخل أسرة اليتيم :</span>
                        <span class="value"> {{$orphan->income}} </span>
                    </div>
                @endif


                {{-- income_value --}}
                @if($orphan->income_value)
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <span class="fw-bold"> القيمة المالية للدخل : </span>
                        <span class="value"> {{$orphan->income_value}} </span>
                   </div>
               @endif

                {{-- income_source --}}
                @if($orphan->income_source)
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <span class="fw-bold"> مصدر الدخل  :</span>
                        <span class="value"> {{$orphan->income_source}} </span>
                    </div>
                @endif


            </div>

        </div>

    </section>
    @endif



    {{-- educational statu & health  status & Guardian's data--}}
    <div class="row">

        <div class="col-12  col-lg-6">

            {{-- Guardian information section --}}
            @if($orphan->guardian_name)
                <section class="family-information mt-5">

                {{-- section header component --}}
                <x-header title=" بيانات الوصي " />

                <div class="border border-1 rounded" style="border-top-color:#f0fff4 !important">

                    <div class="m-4 row">

                        {{-- guardian_name --}}
                        @if($orphan->guardian_name)
                        <div class="col-12 col-lg-6  mb-3">
                            <span class="fw-bold">  اسم الوصي :</span>
                            <span class="value"> {{$orphan->guardian_name}} </span>
                        </div>
                        @endif


                        {{-- guardian_relation --}}
                        @if($orphan->guardian_relation)
                        <div class="col-12 col-lg-6  mb-3">
                            <span class="fw-bold">  صلة القرابة : </span>
                            <span class="value"> {{$orphan->guardian_relation}}  </span>
                        </div>
                        @endif


                        <hr>

                        {{-- guardian_id_number --}}
                        @if($orphan->profile && $orphan->profile->guardian_id_number)
                        <div class="col-12 col-lg-6  mb-3">
                            <span class="fw-bold"> رقم هوية الوصي :</span>
                            <span class="value"> {{$orphan->profile->guardian_id_number}}  </span>
                        </div>
                        @endif

                        {{-- guardian_jop --}}
                        @if($orphan->guardian_jop)
                        <div class="col-12 col-lg-6  mb-3">
                            <span class="fw-bold"> الوظيفة : </span>
                            <span class="value"> {{$orphan->guardian_jop}}  </span>
                        </div>
                        @endif

                        <hr>

                        {{-- guardian_housing --}}
                        @if($orphan->profile && $orphan->profile->guardian_housing)
                        <div class="col-12 col-lg-6  mb-3">
                            <span class="fw-bold"> السكن : </span>
                            <span class="value"> {{$orphan->profile->guardian_housing}}  </span>
                        </div>
                        @endif

                        {{-- guardian_whats_phone --}}
                        @if($orphan->profile && $orphan->profile->guardian_whats_phone)
                        <div class="col-12 col-lg-6  mb-3">
                            <span class="fw-bold"> رقم الواتس : </span>
                            <span class="value"> {{$orphan->profile->guardian_whats_phone}}  </span>
                        </div>
                        @endif

                        <hr>

                        {{-- guardian_first_phone --}}
                        @if($orphan->profile && $orphan->profile->guardian_first_phone)
                        <div class="col-12 col-lg-6  mb-3">
                            <span class="fw-bold"> رقم الجوال 1 : </span>
                            <span class="value"> {{$orphan->profile->guardian_first_phone}}  </span>
                        </div>
                        @endif

                        {{-- guardian_first_phone --}}
                        @if($orphan->profile && $orphan->profile->guardian_secound_phone)
                        <div class="col-12 col-lg-6  mb-3">
                            <span class="fw-bold"> رقم الجوال 2 : </span>
                            <span class="value"> {{$orphan->profile->guardian_secound_phone}}  </span>
                        </div>
                        @endif

                        <hr>

                        {{-- guardian_email --}}
                        {{-- @if($orphan->profile && $orphan->profile->guardian_email)
                        <div class="col-12  mb-3">
                            <span class="fw-bold"> البريد الالكتروني : </span>
                            <span class="value"> {{$orphan->profile->guardian_email}}  </span>
                        </div>
                        @endif --}}

                    </div>

                </div>

            </section>
            @endif

        </div>

        <div class="col-12  col-lg-6">

            <div class="col-12">
                {{-- health  information section --}}
                @if($orphan->profile && $orphan->profile->health_status)
                    <section class="family-information mt-5">

                    {{-- section header component --}}
                    <x-header title="  الحالة الصحية " />

                    <div class="border border-1 rounded" style="border-top-color:#f0fff4 !important">

                        <div class="m-4 mb-1 row">

                            {{-- guardian_name --}}
                            @if($orphan->profile && $orphan->profile->health_status)
                                <div class="col-12 col-lg-6  mb-3">
                                    <span class="fw-bold"> الحالة الصحية :</span>
                                    <span class="value"> {{$orphan->profile->health_status}}  </span>
                                </div>
                            @endif


                            {{-- guardian_relation --}}
                            @if($orphan->profile && $orphan->profile->disease_type)
                                <div class="col-12 col-lg-6  mb-3">
                                    <span class="fw-bold">  نوع المرض : </span>
                                    <span class="value"> {{$orphan->profile->disease_type}}  </span>
                                </div>
                            @endif

                            <hr>

                            @if ($orphan->profile && $orphan->profile->medical_report)

                                <div class="col-12 col-lg-6 mb-3">

                                    <a href="{{route('orphan.primary.image' , ['file' => encrypt($orphan->profile->medical_report)])}}" type="button" class="text-decoration-none file-image p-2">
                                        <img src="{{asset('images/elements.png')}}" alt="" width="22px" height="22px" >
                                        التقرير الطبي
                                    </a>
                                </div>

                            @elseif($orphan->profile && $orphan->profile->not_available_medical_report)

                                <div class="col-12 col-lg-8 mb-3">
                                    <span class="fw-bold"> سبب عدم توفر التقرير الطبي   :</span>
                                    <span class="value"> {{ $orphan->profile->not_available_medical_report }} </span>
                                </div>

                            @endif


                        </div>

                    </div>

                </section>
                @endif
            </div>

            <div class="col-12">
                {{-- educational  information section --}}
                @if($orphan->profile && $orphan->profile->educational_status)
                    <section class="family-information mt-3">

                    {{-- section header component --}}
                    <x-header title="  الحالة التعليمية " />

                    <div class="border border-1 rounded" style="border-top-color:#f0fff4 !important">

                        <div class="m-4 mb-1 row">

                            {{-- educational_status --}}
                            @if($orphan->profile && $orphan->profile->educational_status)
                                <div class="col-12 col-lg-6  mb-3">
                                    <span class="fw-bold">  الوضع التعليمي :</span>
                                    <span class="value"> {{$orphan->profile->educational_status}}  </span>
                                </div>
                            @endif


                            {{-- academic_stage --}}
                            @if($orphan->profile && $orphan->profile->academic_stage)
                                <div class="col-12 col-lg-6  mb-3">
                                    <span class="fw-bold">  المرحلة الدراسية : </span>
                                    <span class="value"> {{$orphan->profile->academic_stage}}  </span>
                                </div>
                            @endif

                            <hr>

                            {{-- average --}}
                            @if($orphan->profile && $orphan->profile->average)
                                <div class="col-12 col-lg-6  mb-3">
                                    <span class="fw-bold"> المعدل : </span>
                                    <span class="value"> {{$orphan->profile->average}}  </span>
                                </div>
                            @endif

                            @if ($orphan->profile && $orphan->profile->educational_certificate)

                                <div class="col-12 col-lg-6 mb-3">

                                    <a href="{{route('orphan.primary.image' , ['file' => encrypt($orphan->profile->educational_certificate)])}}" type="button" class="text-decoration-none file-image p-2">
                                        <img src="{{asset('images/elements.png')}}" alt="" width="22px" height="22px" >
                                       آخر شهادة تعليمية
                                    </a>
                                </div>

                            @elseif($orphan->profile && $orphan->profile->not_available_educational_certificate)

                                <div class="col-12 col-md-6 col-lg-3 mb-3">
                                    <span class="fw-bold"> سبب عدم توفر الشهادة  :</span>
                                    <span class="value"> {{ $orphan->profile->not_available_educational_certificate }} </span>
                                </div>

                            @endif



                        </div>

                    </div>

                </section>
                @endif
            </div>

        </div>



    </div>


    {{-- بيانات إخوة اليتيم --}}
    @isset($orphan->sibling)
        <section class="family-information mt-5">

            {{-- section header component --}}
            <x-header title="  بيانات إخوة اليتيم " />

            <div class="border border-1 rounded" style="border-top-color:#f0fff4 !important">

                <div class="m-4">

                    <div class="row mb-3">

                        @if($orphan->sibling && $orphan->sibling->male_number)
                            <div class="col-12 col-md-6  mb-3">
                                <span class="fw-bold"> عدد الأخوة الذكور </span>
                                <span class="value"> {{$orphan->sibling->male_number}}  </span>
                            </div>
                        @endif

                        @if($orphan->sibling && $orphan->sibling->female_number)
                            <div class="col-12 col-md-6  mb-3">
                                <span class="fw-bold"> عدد الأخوة الإناث </span>
                                <span class="value"> {{$orphan->sibling->female_number}}  </span>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

        </section>
    @endisset

    {{-- How to receive sponsorship--}}
    @if ($orphan->profile && $orphan->profile->receive_guarantee)
        <section class="sponsorship-information mt-5">

        {{-- section header component --}}
        <x-header title=" طريقة استلام الكفالة " />

        <div class="border border-1 rounded" style="border-top-color:#f0fff4 !important">

            <div class="m-4">

                {{-- --}}
                <div class="row mb-3">


                    @if ($orphan->profile && $orphan->profile->receive_guarantee == 'bank')

                    {{-- استلام الكفالة عن طريق حساب البنك: --}}

                        <div class="">
                            <p class="mb-3 fw-bold fs-5"> يتم استلام الكفالة عن طريق حساب البنك:  </p>
                            <div class="d-flex row align-items-center mb-3">

                                 @if ($orphan->profile && $orphan->profile->account_number)
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <span class="fw-bold"> رقم الحساب : </span>
                                        <span class="value">{{$orphan->profile->account_number}}</span>
                                    </div>
                                @endif

                                @if ($orphan->profile && $orphan->profile->bank)
                                <div class="col-12 col-md-6 col-lg-4">
                                    <span class="fw-bold"> البنك :</span>
                                    <span class="value"> {{$orphan->profile->bank}} </span>
                                </div>
                                @endif

                                @if ($orphan->profile && $orphan->profile->phone_number_linked_account)
                                <div class="col-12 col-md-6 col-lg-4">
                                    <span class="fw-bold"> رقم الجوال المربوط بالحساب </span>
                                    <span class="value"> {{$orphan->profile->phone_number_linked_account}} </span>
                                </div>
                                @endif

                            </div>
                        </div>

                    @elseif ($orphan->profile && $orphan->profile->receive_guarantee == 'wallet')

                        {{-- استلام الكفالة عن طريق محفظة بال باي: --}}

                        <div class="">
                            <p class="mb-3 fw-bold fs-5"> يتم استلام الكفالة عن طريق محفظة بال باي:  </p>

                            <div class="d-flex align-items-center row">

                                @if ($orphan->profile && $orphan->profile->wallet_number)
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <span class="fw-bold"> رقم المحفظة : </span>
                                        <span class="value">{{$orphan->profile->wallet_number}}</span>
                                    </div>
                                @endif

                                 @if ($orphan->profile && $orphan->profile->wallet_owner)

                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <span class="fw-bold"> اسم صاحب المحفظة : </span>
                                        <span class="value">{{$orphan->profile->wallet_owner}}</span>
                                    </div>
                                @endif

                                 @if ($orphan->profile && $orphan->profile->wallet_owner_id_number)
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <span class="fw-bold"> رقم هوية صاحب المحفظة :</span>
                                        <span class="value"> {{$orphan->profile->wallet_owner_id_number}} </span>
                                    </div>
                                @endif

                                @if ($orphan->profile && $orphan->profile->wallet_owner_id_number_image)

                                    <div class="col-12 col-md-5 col-lg-4 mb-3">

                                        <a href="{{route('orphan.primary.image' , ['file' => encrypt($orphan->profile->wallet_owner_id_number_image)])}}" type="button" class="text-decoration-none file-image p-2">
                                            <img src="{{asset('images/elements.png')}}" alt="" width="22px" height="22px" >
                                            هوية صاحب المحفظة
                                        </a>
                                    </div>

                                @elseif($orphan->profile && $orphan->profile->not_available_wallet_owner_id_number_image)

                                    <div class="col-12 col-md-6 col-lg-3 mb-3">
                                        <span class="fw-bold"> سبب عدم توفر هوية صاحب المحفظة :</span>
                                        <span class="value"> {{ $orphan->profile->not_available_wallet_owner_id_number_image }} </span>
                                    </div>

                                @endif



                            </div>

                        </div>


                    @endif





                </div>



            </div>
        </div>

    </section>
    @endif


</x-main-layout>
