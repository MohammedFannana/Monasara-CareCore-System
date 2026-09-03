<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.rtl.min.css" integrity="sha384-q8+l9TmX3RaSz3HKGBmqP2u5MkgeN7HrfOJBLcTgZsQsbrx8WqqxdA5PuwUV9WIx" crossorigin="anonymous">
    <title>تسجيل اليتيم</title>
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <style>
        nav{
            background-image: url('../images/background-nav.png');
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            background-color: #d0efda;
        }

        .radio-input{
            width: 23px;
            height:23px
        }


        .radio-input:checked{
            background-color: var('--primary-color');
            border-color: var('--primary-color');
        }



        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

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

        table input {
            padding: 5px;
            width: 90%;
            border: 1px solid #ddd;

        }


    </style>

</head>
<body dir="rtl" style="background-color: #f0fff4">

    <div style="ps-4 pe-4">

        <nav class="d-flex align-items-center p-2">

            <div class="d-flex gap-2 align-items-center ps-3">
                <img src="{{asset('images/logo.png')}}" alt="" width="51px" height="70px">
                <img src="{{asset('images/logo2.png')}}" alt="" width="64px" height="56px">
            </div>

            <div class="fw-bold m-auto" style="color: var(--primary-color); font-size:20px">
                استمارة تسجيل بيانات اليتيم
            </div>

        </nav>

        <div class="mt-1 bg-white" style="margin-right:2rem; margin-left:2rem;border-radius:15px">

            <main style="margin-right: 2rem;margin-left:2rem;padding-top:25px;padding-bottom:25px">

                <x-alert name="success"/>
                <x-alert name="danger"/>

                <form action="{{route('orphan.store')}}" method="post" enctype="multipart/form-data">
                    @csrf

                    {{-- basic information section --}}
                    <section class="basic-information">

                        {{-- section header component --}}
                        <x-header title=" البيانات الأساسية لليتيم" />

                        <div class="border border-1 rounded" style="border-top-color:#f0fff4 !important">

                            <div class="m-4">

                                {{-- orphan-image --}}
                                <div class="col-12 col-md-6 mb-4">
                                    <label class="mb-2 fw-bold">{{__(' صورة اليتيم ')}}</label> <br>
                                    <label for="image" class="custom-file-upload text-center"> <img src="{{asset('images/image.png')}}" alt="" width="50px" height="50px"> </label>
                                    <x-form.input name="image" class="hidden-file-style" type="file" id="image" style="display: none;"/>
                                </div>

                                <div class="row mb-3">


                                    <!-- الجمعية مخفية -->
                                    <input type="hidden" name="association_id" value="{{ $association->id }}">
                                    <input type="hidden" name="token" value="{{ encrypt($association->id) }}">


                                    {{-- orphan-name --}}
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <x-form.input name="name" class="" type="text" id="name" label="اسم اليتيم كامل" placeholder="ادخل الاسم" />
                                    </div>

                                    {{-- birth-date --}}
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <x-form.input name="birth_date"  type="date" id="birth-date" label="تاريخ الميلاد" />
                                    </div>

                                    {{-- birth-place --}}
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <x-form.input name="birth_place"  type="text" id="birth-place" label="مكان الميلاد" placeholder="ادخل مكان الميلاد"/>
                                    </div>

                                    {{-- country --}}
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <x-form.input name="country" class="" type="text" id="country" label=" بلد اليتيم " placeholder="ادخل بلد اليتيم" />
                                    </div>

                                    {{-- city --}}
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <x-form.input name="city"  type="text" id="city" label=" المنطقة / المدينة" placeholder="ادخل المدينة" />
                                    </div>

                                    {{-- landmark --}}
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <x-form.input name="landmark"  type="text" id="landmark" label=" أقرب معلم " placeholder="ادخل أقرب معلم"/>
                                    </div>


                                    {{-- id_number --}}
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <x-form.input name="id_number" type="text" id="id_number" label=" رقم هوية اليتيم " placeholder="ادخل رقم هوية اليتيم" />
                                    </div>

                                    {{-- orphan-status --}}
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <label class="mb-2 fw-bold"> حالة اليتيم </label>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex  gap-1">
                                                <input class="radio-input p-0" name="orphan_status" type="radio" id="motherless"  value="يتيم الأم"/>
                                                <label class="form-check-label" for="motherless" style="color: rgba(36, 36, 36, 0.6)">يتيم الأم</label>
                                            </div>

                                            <div class="d-flex  gap-1">
                                                <input class="radio-input p-0" name="orphan_status" type="radio" id="fatherless"  value="يتيم الأب" checked/>
                                                <label class="form-check-label" for="fatherless" style="color: rgba(36, 36, 36, 0.6)">يتيم الأب</label>
                                            </div>

                                            <div class="d-flex gap-1">
                                                <input class="radio-input p-0" name="orphan_status"  type="radio" id="orphan"  value="يتيم الأبوين"/>
                                                <label class="form-check-label" for="orphan" style="color: rgba(36, 36, 36, 0.6)">يتيم الأبوين</label>
                                            </div>
                                        </div>
                                        @error('orphan_status')
                                            <div class="text-danger">
                                                {{$message}}
                                            </div>
                                        @enderror
                                    </div>


                                    {{-- gender --}}
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <label class="mb-2 fw-bold"> الجنس </label>
                                        <div class="d-flex align-items-center gap-5">
                                            <div class="d-flex  gap-1">
                                                <input class="radio-input p-0" name="gender" type="radio" id="male"  value="ذكر"/>
                                                <label class="form-check-label" for="male" style="color: rgba(36, 36, 36, 0.6)"> ذكر </label>
                                            </div>

                                            <div class="d-flex  gap-1">
                                                <input class="radio-input p-0" name="gender" type="radio" id="female"  value="أنثى"/>
                                                <label class="form-check-label" for="female" style="color: rgba(36, 36, 36, 0.6)"> أنثى </label>
                                            </div>

                                            @error('gender')
                                                <div class="text-danger">
                                                    {{$message}}
                                                </div>
                                            @enderror

                                        </div>
                                    </div>

                                    {{-- nominating_authority --}}
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <x-form.input name="nominating_authority" type="text" id="nominating_authority" label=" جهة الترشيح " placeholder="ادخل جهة الترشيح" />
                                    </div>

                                </div>


                            </div>

                        </div>


                    </section>


                    {{-- family information section --}}
                    <section class="family-information mt-5">

                        {{-- section header component --}}
                        <x-header title=" بيانات أسرة اليتيم " />

                        <div class="border border-1 rounded" style="border-top-color:#f0fff4 !important">

                            <div class="m-4">


                                <div id="parent_death" style="display: none">
                                    <div class="row mb-3">

                                        {{-- mother-name --}}
                                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                                            <x-form.input name="mother_name" class="" type="text" id="mother_name" label="اسم الأم" placeholder="ادخل اسم الأم" />
                                        </div>

                                        {{-- death_mother_date --}}
                                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                                            <x-form.input name="death_mother_date"  type="date" id="death_mother_date" label="تاريخ الوفاة" />
                                        </div>

                                        {{-- cause_death --}}
                                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                                            <x-form.input name="cause_mother_death"  type="text" id="cause_mother_death" label="سبب الوفاة" placeholder="ادخل سبب الوفاة"/>
                                        </div>

                                        {{-- father-name --}}
                                        <div class="col-12 col-md-6 col-lg-4  mb-3">
                                            <x-form.input name="father_name" class="" type="text" id="father_name" label="اسم الأب" placeholder="ادخل اسم الأب" />
                                        </div>

                                        {{-- death_father_date --}}
                                        <div class="col-12 col-md-6 col-lg-4  mb-3">
                                            <x-form.input name="death_father_date"  type="date" id="death_father_date" label="تاريخ الوفاة" />
                                        </div>

                                        {{-- cause_death --}}
                                        <div class="col-12 col-md-6 col-lg-4  mb-3">
                                            <x-form.input name="cause_father_death"  type="text" id="cause_father_death" label="سبب الوفاة" placeholder="ادخل سبب الوفاة"/>
                                        </div>

                                    </div>
                                </div>


                                <div id="mother_death" style="display: none">
                                    <div class="row mb-3">
                                        {{-- mother-name --}}
                                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                                            <x-form.input name="mother_name" class="" type="text" label="اسم الأم" placeholder="ادخل اسم الأم" />
                                        </div>

                                        {{-- death_mother_date --}}
                                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                                            <x-form.input name="death_mother_date"  type="date"  label="تاريخ الوفاة" />
                                        </div>

                                        {{-- cause_death --}}
                                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                                            <x-form.input name="cause_mother_death"  type="text"  label="سبب الوفاة" placeholder="ادخل سبب الوفاة"/>
                                        </div>

                                        {{-- father-name --}}
                                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                                            <x-form.input name="father_name" class="" type="text"  label="اسم الأب" placeholder="ادخل اسم الأب" />
                                        </div>

                                        {{-- father_id_number --}}
                                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                                            <x-form.input name="father_id_number"  type="text"  label=" رقم هوية الأب "  placeholder="ادخل رقم هوية الأب "/>
                                        </div>

                                        {{-- cause_death --}}
                                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                                            <label class="mb-2 fw-bold"> الحالة الاجتماعية للأب </label>
                                            <div class="d-flex align-items-center gap-5">
                                                <div class="d-flex  gap-1">
                                                    <input class="radio-input p-0" name="father_marital_status" type="radio" id="Width"  value="أرمل"/>
                                                    <label class="form-check-label" for="Width" style="color: rgba(36, 36, 36, 0.6)"> أرمل </label>
                                                </div>

                                                <div class="d-flex gap-1">
                                                    <input class="radio-input p-0" name="father_marital_status" type="radio" id="married"  value="متزوج"/>
                                                    <label class="form-check-label" for="married" style="color: rgba(36, 36, 36, 0.6)"> متزوج </label>
                                                </div>

                                                @error('father_marital_status')
                                                    <div class="text-danger">
                                                        {{$message}}
                                                    </div>
                                                @enderror

                                            </div>
                                        </div>

                                        {{-- father_phone --}}
                                        <div class="col-12 col-md-6 col-lg-4 mt-3">
                                            <x-form.input name="father_phone"  type="text"  label=" رقم جوال الأب " placeholder="ادخل رقم جوال الأب " />
                                        </div>


                                    </div>

                                </div>


                                <div id="father_death">
                                    <div class="row mb-3">
                                        {{-- father-name --}}
                                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                                            <x-form.input name="father_name" class="" type="text"  label="اسم الأب" placeholder="ادخل اسم الأب" />
                                        </div>

                                        {{-- death_father_date --}}
                                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                                            <x-form.input name="death_father_date"  type="date"  label="تاريخ الوفاة" />
                                        </div>

                                        {{-- cause_death --}}
                                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                                            <x-form.input name="cause_father_death"  type="text"  label="سبب الوفاة" placeholder="ادخل سبب الوفاة"/>
                                        </div>

                                        {{-- mother_name --}}
                                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                                            <x-form.input name="mother_name" class="" type="text" label="اسم الأم" placeholder="ادخل اسم الأم" />
                                        </div>

                                        {{-- mother_id_number --}}
                                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                                            <x-form.input name="mother_id_number"  type="text"  label=" رقم هوية الأم " placeholder="ادخل رقم هوية الأم" />
                                        </div>

                                        {{-- mother_marital_status --}}
                                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                                            <label class="mb-2 fw-bold"> الحالة الاجتماعية للأم </label>
                                            <div class="d-flex align-items-center gap-5">
                                                <div class="d-flex  gap-1">
                                                    <input class="radio-input p-0" name="mother_marital_status" type="radio" id="mother_Width"  value="أرملة"/>
                                                    <label class="form-check-label" for="mother_Width" style="color: rgba(36, 36, 36, 0.6)"> أرملة </label>
                                                </div>

                                                <div class="d-flex  gap-1">
                                                    <input class="radio-input p-0" name="mother_marital_status" type="radio" id="mother_married"  value="متزوجة"/>
                                                    <label class="form-check-label" for="mother_married" style="color: rgba(36, 36, 36, 0.6)"> متزوجة </label>
                                                </div>

                                                @error('mother_marital_status')
                                                    <div class="text-danger">
                                                        {{$message}}
                                                    </div>
                                                @enderror

                                            </div>
                                        </div>

                                        {{-- mother_phone --}}
                                        <div class="col-12 col-md-6 col-lg-4 mt-3">
                                            <x-form.input name="mother_phone"  type="text"  label=" رقم جوال الأم " placeholder="ادخل رقم جوال الأم"/>
                                        </div>


                                    </div>

                                </div>

                                {{-- income --}}
                                <div class="col-12 col-md-6 col-lg-4 mb-3">
                                    <label class="mb-2 fw-bold"> مصادر دخل أسرة اليتيم </label>
                                    <div class="d-flex align-items-center gap-5">
                                        <div class="d-flex  gap-1">
                                            <input class="radio-input p-0" name="income" type="radio" id="no_income"  value="بدون دخل"/>
                                            <label class="form-check-label" for="no_income" style="color: rgba(36, 36, 36, 0.6)"> بدون دخل </label>
                                        </div>

                                        <div class="d-flex  gap-1">
                                            <input class="radio-input p-0" name="income" type="radio" id="fixed_income"  value="دخل ثايت"/>
                                            <label class="form-check-label" for="fixed_income" style="color: rgba(36, 36, 36, 0.6)"> دخل ثابت </label>
                                        </div>

                                        @error('income')
                                            <div class="text-danger">
                                                {{$message}}
                                            </div>
                                        @enderror

                                    </div>
                                </div>


                                <p style="color: var(--primary-color)" class="mb-3"> إذا كان  الدخل ثابت </p>

                                {{-- income_value & income_source--}}
                                <div class="row mb-3">
                                    {{-- income_value --}}
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <x-form.input name="income_value" class="" type="text" id="income_value" label=" القيمة المالية للدخل " placeholder="ادخل  القيمة المالية للدخل " />
                                    </div>

                                    {{-- income_source --}}
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <x-form.input name="income_source"  type="text" id="income_source" label=" مصدر الدخل " placeholder="ادخل مصدر الدخل " />
                                    </div>

                                    {{-- Father's death certificate --}}
                                    <div class="col-12 col-md-6 mb-4">
                                        <div class="w-100 mb-3">
                                            <label class="mb-2 fw-bold">  شهادة وفاة الأب  <span style="color:#777a78;"> (ان وجد) </span></label> <br>
                                            <label for="father_death_certificate" class="custom-file-upload text-center" style="width: 90%;color:#777a78;">
                                                <img src="{{asset('images/file.png')}}" alt="" width="50px" height="50px"> ,<br>
                                                اسحب الملف هنا أو اضغط لاختياره
                                            </label>
                                            <x-form.input name="father_death_certificate" class="hidden-file-style" type="file" id="father_death_certificate" style="display: none;"/>
                                        </div>

                                        <div style="width:90%">
                                            <div class="w-100">
                                                <x-form.input name="not_available_father_death" type="text" id="not_available_father_death" label=" في حالة عدم توفر الملف, يرجى ادخال السبب " placeholder="ادخل الرسالة" />
                                            </div>
                                        </div>
                                    </div>


                                    {{-- mother_death_certificate --}}
                                    <div class="col-12 col-md-6 mb-4">
                                        <div class="w-100 mb-3">
                                            <label class="mb-2 fw-bold"> شهادة وفاة الأم <span style="color:#777a78;"> (ان وجد) </span> </label> <br>
                                            <label for="mother_death_certificate" class="custom-file-upload text-center" style="width: 90%;color:#777a78;">
                                                <img src="{{asset('images/file.png')}}" alt="" width="50px" height="50px"> <br>
                                                اسحب الملف هنا أو اضغط لاختياره
                                            </label>
                                            <x-form.input name="mother_death_certificate" class="hidden-file-style" type="file" id="mother_death_certificate" style="display: none;"/>
                                        </div>


                                        <div style="width:90%">
                                            <div class="w-100">
                                                <x-form.input name="not_available_mother_death" type="text" id="not_available_mother_death" label=" في حالة عدم توفر الملف, يرجى ادخال السبب " placeholder="ادخل الرسالة" />
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>


                    </section>


                    {{-- Guardian's data --}}
                    <section class="guardian-information mt-5">

                        {{-- section header component --}}
                        <x-header title=" بيانات  الوصي " />

                        <div class="border border-1 rounded" style="border-top-color:#f0fff4 !important">

                            <div class="m-4">
                                {{-- guardian_name & guardian_relation & guardian_jop --}}
                                <div class="row mb-3">
                                    {{-- guardian_name --}}
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <x-form.input name="guardian_name" class="" type="text" id="guardian_name" label=" اسم الوصي على اليتيم " placeholder="ادخل اسم الوصي على اليتيم" />
                                    </div>

                                    {{-- guardian_relation --}}
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <x-form.input name="guardian_relation"  type="text" id="guardian_relation" label=" صلة القرابة " placeholder="ادخل صلة القرابة" />
                                    </div>

                                    {{-- guardian_jop --}}
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <x-form.input name="guardian_jop"  type="text" id="guardian_jop" label=" الوظيفة " placeholder="ادخل الوظيفة "/>
                                    </div>


                                    {{-- guardian_id_number --}}
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <x-form.input name="guardian_id_number" class="" type="text" id="guardian_id_number" label=" رقم هوية الوصي " placeholder="ادخل رقم هوية الوصي " />
                                    </div>

                                    {{-- guardian_housing --}}
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <x-form.input name="guardian_housing"  type="text" id="guardian_housing" label=" السكن " placeholder="ادخل السكن" />
                                    </div>

                                    {{-- guardian_whats_phone --}}
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <x-form.input name="guardian_whats_phone"  type="text" id="guardian_whats_phone" label=" رقم الواتساب " placeholder="ادخل رقم الواتساب  "/>
                                    </div>
                                </div>


                                {{-- guardian_first_phone & guardian_secound_phone & guardian_email --}}
                                <div class="row mb-3">
                                    {{-- guardian_first_phone --}}
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <x-form.input name="guardian_first_phone" class="" type="text" id="guardian_first_phone" label=" رقم الجوال الأول " placeholder="ادخل رقم الجوال الأول " />
                                    </div>

                                    {{-- guardian_first_phone --}}
                                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <x-form.input name="guardian_secound_phone"  type="text" id="guardian_secound_phone" label=" رقم الجوال الثاني " placeholder="ادخل رقم الجوال الثاني " />
                                    </div>

                                    {{-- guardian_email --}}
                                    {{-- <div class="col-12 col-md-6 col-lg-4 mb-3">
                                        <x-form.input name="guardian_email"  type="text" id="guardian_email" label=" البريد الالكتروني " placeholder="ادخل البريد الالكتروني  "/>
                                    </div> --}}
                                </div>

                            </div>
                        </div>

                    </section>


                    {{-- health  status--}}
                    <section class="health-information mt-5">

                        {{-- section header component --}}
                        <x-header title="  الحالة الصحية لليتيم" />

                        <div class="border border-1 rounded" style="border-top-color:#f0fff4 !important">

                            <div class="m-4">
                                {{-- health-status--}}
                                <div class="row mb-3">
                                    {{-- health-status --}}
                                    <div class="col-12 mb-3">
                                        <label class="mb-2 fw-bold"> الحالة الصحية </label>
                                        <div class="d-flex align-items-center gap-5">
                                            <div class="d-flex  gap-1">
                                                <input class="radio-input p-0" name="health_status" type="radio" id="health-good"  value="جيد"/>
                                                <label class="form-check-label" for="health-good" style="color: rgba(36, 36, 36, 0.6)"> جيد </label>
                                            </div>

                                            <div class="d-flex  gap-1">
                                                <input class="radio-input p-0" name="health_status" type="radio" id="health-sick"  value="مريض"/>
                                                <label class="form-check-label" for="health-sick" style="color: rgba(36, 36, 36, 0.6)"> مريض </label>
                                            </div>

                                            @error('health_status')
                                                <div class="text-danger">
                                                    {{$message}}
                                                </div>
                                            @enderror

                                        </div>
                                    </div>


                                    {{-- disease_type--}}
                                    <div class="col-12" id="disease_type">
                                            <label class="mb-2 fw-bold"> نوع المرض <span style="color:#777a78;"> (ان وجد) </span> </label>
                                            <div class="d-flex align-items-center gap-5">
                                                <div class="d-flex  gap-1">
                                                    <input class="radio-input p-0" name="disease_type" type="radio" id="good"  value="مرض عادي"/>
                                                    <label class="form-check-label" for="good" style="color: rgba(36, 36, 36, 0.6)"> مرض عادي </label>
                                                </div>

                                                <div class="d-flex  gap-1">
                                                    <input class="radio-input p-0" name="disease_type" type="radio" id="chronic_disease"  value="مرض مزمن"/>
                                                    <label class="form-check-label" for="chronic_disease" style="color: rgba(36, 36, 36, 0.6)"> مرض مزمن </label>
                                                </div>

                                                <div class="d-flex  gap-1">
                                                    <input class="radio-input p-0" name="disease_type" type="radio" id="incurable_disease"  value="مرض عضال"/>
                                                    <label class="form-check-label" for="incurable_disease" style="color: rgba(36, 36, 36, 0.6)"> مرض عضال </label>
                                                </div>

                                                @error('disease_type')
                                                    <div class="text-danger">
                                                        {{$message}}
                                                    </div>
                                                @enderror

                                            </div>
                                    </div>
                                </div>


                                <div class="row">

                                    {{-- Father's death certificate --}}
                                    <div class="col-12 col-md-6 mb-4">

                                            <label class="mb-2 fw-bold">   التقرير الطبي  <span style="color:#777a78;"> (ان وجد) </span></label> <br>
                                            <label for="medical_report" class="custom-file-upload text-center" style="width: 90%;color:#777a78;">
                                                <img src="{{asset('images/file.png')}}" alt="" width="50px" height="50px"> ,<br>
                                                اسحب الملف هنا أو اضغط لاختياره
                                            </label>
                                            <x-form.input name="medical_report" class="hidden-file-style" type="file" id="medical_report" style="display: none;"/>
                                    </div>

                                    <div class="col-12">
                                        <div class="w-100">
                                            <x-form.input name="not_available_medical_report" type="text" id="not_available_medical_report" label=" في حالة عدم توفر الملف, يرجى ادخال السبب " placeholder="ادخل الرسالة" />
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </section>


                    {{-- educational status--}}
                    <section class="educational-information mt-5">

                        {{-- section header component --}}
                        <x-header title="  الحالة التعليمية لليتيم " />

                        <div class="border border-1 rounded" style="border-top-color:#f0fff4 !important">

                            <div class="m-4">

                                {{--educational_status  & academic_stage--}}
                                <div class="row mb-3">
                                    {{-- educational_status--}}
                                    <div class="col-12 mb-3">
                                        <label class="mb-2 fw-bold"> الوضع التعليمي  </label>
                                        <div class="d-flex align-items-center gap-5">
                                            <div class="d-flex  gap-1">
                                                <input class="radio-input p-0" name="educational_status" type="radio" id="underschool"  value="دون سن الدراسة"/>
                                                <label class="form-check-label" for="underschool" style="color: rgba(36, 36, 36, 0.6)">  دون سن الدراسة </label>
                                            </div>

                                            <div class="d-flex gap-1">
                                                <input class="radio-input p-0" name="educational_status" type="radio" id="study"  value="يدرس"/>
                                                <label class="form-check-label" for="study" style="color: rgba(36, 36, 36, 0.6)"> يدرس </label>
                                            </div>

                                            <div class="d-flex  gap-1">
                                                <input class="radio-input p-0" name="educational_status" type="radio" id="no_study"  value="لا يدرس"/>
                                                <label class="form-check-label" for="no_study" style="color: rgba(36, 36, 36, 0.6)"> لا يدرس </label>
                                            </div>

                                            @error('educational_status')
                                                <div class="text-danger">
                                                    {{$message}}
                                                </div>
                                            @enderror

                                        </div>
                                    </div>

                                    {{-- academic_stage --}}
                                    <div class="col-12" id="academic_stage">
                                        <label class="mb-2 fw-bold"> المرحلة الدراسية </label>
                                        <div class="d-flex align-items-center gap-5">
                                            <div class="d-flex  gap-1">
                                                <input class="radio-input p-0" name="academic_stage" type="radio" id="primary"  value="ابتدائي"/>
                                                <label class="form-check-label" for="primary" style="color: rgba(36, 36, 36, 0.6)"> ابتدائي </label>
                                            </div>

                                            <div class="d-flex  gap-1">
                                                <input class="radio-input p-0" name="academic_stage" type="radio" id="preparatory"  value="اعدادي"/>
                                                <label class="form-check-label" for="preparatory" style="color: rgba(36, 36, 36, 0.6)"> اعدادي </label>
                                            </div>

                                            @error('academic_stage')
                                                <div class="text-danger">
                                                    {{$message}}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12 mt-3" id="average1">
                                        <div class="w-100">
                                            <x-form.input name="average" type="text" id="average" label=" المعدل (ان وجد ) " placeholder="ادخل المعدل" />
                                        </div>
                                    </div>

                                </div>


                                {{-- educational_certificate --}}
                                <div class="row">

                                    {{-- educational_ certificate --}}
                                    <div class="col-12 col-md-6 col-lg-4 mb-4">

                                            <label class="mb-2 fw-bold">  اخر شعادة تعليمية  <span style="color:#777a78;"> (ان وجد) </span></label> <br>
                                            <label for="educational_certificate" class="custom-file-upload text-center" style="width: 90%;color:#777a78;">
                                                <img src="{{asset('images/file.png')}}" alt="" width="50px" height="50px"> ,<br>
                                                اسحب الملف هنا أو اضغط لاختياره
                                            </label>
                                            <x-form.input name="educational_certificate" class="hidden-file-style" type="file" id="educational_certificate" style="display: none;"/>
                                    </div>

                                    <div class="col-12">
                                        <div class="w-100">
                                            <x-form.input name="not_available_educational_certificate" type="text" id="not_available_educational_certificate" label=" في حالة عدم توفر الملف, يرجى ادخال السبب " placeholder="ادخل الرسالة" />
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

                    </section>


                    {{-- بيانات إخوة اليتيم --}}
                    <section class="family-information mt-5">

                        {{-- section header component --}}
                        <x-header title="  بيانات إخوة اليتيم " />

                        <div class="border border-1 rounded" style="border-top-color:#f0fff4 !important">

                            <div class="m-4 row">

                                <div class="col-12 col-md-6  mb-3">
                                    <x-form.input name="male_number" min="0" type="number" id="male_number" label=" عدد الأخوة الذكور "  />
                                </div>

                                <div class="col-12 col-md-6 mb-3">
                                    <x-form.input name="female_number" min="0" type="number" id="female_number" label=" عدد الأخوة الإناث " />
                                </div>

                                {{-- <div class="table-responsive">
                                    <table id="siblingsTable" class=" border-0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>الاسم</th>
                                                <th>الجنس</th>
                                                <th>العمر</th>
                                                <th>الحالة الاجتماعية</th>
                                                <th>المهنة</th>
                                                <th>رقم الهوية</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>
                                                    <input name="brother_name[]" type="text" placeholder="أدخل اسم الأخ/الأخت" class="rounded form-control" />
                                                </td>
                                                <td>
                                                    <select name="brother_gender[]" class="form-control rounded form-select">
                                                        <option value="ذكر">ذكر</option>
                                                        <option value="أنثى">أنثى</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input name="brother_age[]" type="text" placeholder="أدخل العمر" class="rounded form-control" />
                                                </td>

                                                <td>
                                                    <select name="brother_marital_status[]" class="form-control rounded form-select">
                                                        <option value="أعزب">أعزب</option>
                                                        <option value="متزوج">متزوج</option>
                                                        <option value="أرمل">أرمل</option>
                                                        <option value="مطلق">مطلق</option>
                                                        <option value="مهجورة">مهجورة</option>
                                                    </select>
                                                </td>

                                                <td>
                                                    <input name="brother_jop[]" type="text" placeholder="المهنة" class="rounded form-control" />
                                                </td>
                                                <td>
                                                    <input name="brother_id_number[]" type="text" placeholder="رقم الهوية" class="rounded form-control" />
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <button class="submit-btn mt-4" type="button" onclick="addRow()">إضافة أخ / أخت آخر +</button> --}}

                            </div>
                        </div>

                    </section>


                    {{-- How to receive sponsorship--}}
                    <section class="sponsorship-information mt-5">

                        {{-- section header component --}}
                        <x-header title=" طريقة استلام الكفالة " />

                        <div class="border border-1 rounded" style="border-top-color:#f0fff4 !important">

                            <div class="m-4">

                                {{-- --}}
                                <div class="row mb-3">

                                    <p style="color: var(--primary-color)">
                                        <img src="{{asset('images/alert-circle.png')}}" alt="" >
                                        يمكنك اختيار واحدة من الطرق التالية لاستلام الكفالة
                                    </p>

                                    <div class="col-12 col-md-6 col-lg-4 mb-3 ms-3">
                                        {{-- <label class="mb-2 fw-bold">  </label> --}}
                                        <div class="d-flex align-items-center gap-5">
                                            <div class="d-flex  gap-1">
                                                <input class="radio-input p-0" name="receive_guarantee" type="radio" id="bank"  value="bank"  onchange="toggleReceiveDivs()"/>
                                                <label class="form-check-label" for="bank" style="color: rgba(36, 36, 36, 0.6)"> البنك </label>
                                            </div>

                                            <div class="d-flex  gap-1">
                                                <input class="radio-input p-0" name="receive_guarantee" type="radio" id="wallet"  value="wallet"  onchange="toggleReceiveDivs()"/>
                                                <label class="form-check-label" for="wallet" style="color: rgba(36, 36, 36, 0.6)"> محفظة بال باي </label>
                                            </div>

                                            @error('receive_guarantee')
                                                <div class="text-danger">
                                                    {{$message}}
                                                </div>
                                            @enderror

                                        </div>
                                    </div>



                                    {{-- استلام الكفالة عن طريق حساب البنك: --}}

                                    <div id="receive-bank" style="display: none">
                                        <label class="mb-2 fw-bold" style="color: var(--primary-color)">  استلام الكفالة عن طريق حساب البنك:  </label>
                                        <div class="d-flex row align-items-center mb-3">

                                            <div class="col-12 col-md-6 col-lg-4 mb-3">
                                                <x-form.input name="account_number" class="" type="text" id="account_number" label=" رقم الحساب " placeholder="ادخل رقم الحساب" />
                                            </div>

                                            <div class="col-12 col-md-6 col-lg-4 mb-3">
                                                <x-form.input name="bank" class="" type="text" id="bank" label=" البنك " placeholder="ادخل اسم البنك " />
                                            </div>

                                            <div class="col-12 col-md-6 col-lg-4 mb-3">
                                                <x-form.input name="phone_number_linked_account" class="" type="text" id="phone_number_linked_account" label=" رقم الجوال المربوط بالحساب" placeholder="ادخل رقم الجوال المربوط بالحساب" />
                                            </div>

                                        </div>
                                    </div>

                                    {{-- استلام الكفالة عن طريق محفظة بال باي: --}}

                                    <div id="receive-wallet" style="display: none">
                                        <label class="mb-2 fw-bold" style="color: var(--primary-color)">  استلام الكفالة عن طريق محفظة بال باي:  </label>
                                        <div class="d-flex align-items-center row mb-3">

                                            <div class="col-12 col-md-6 col-lg-4 mb-3">
                                                <x-form.input name="wallet_number" class="" type="text" id="wallet_number" label=" رقم المحفظة " placeholder="ادخل رقم المحفظة " />
                                            </div>

                                            <div class="col-12 col-md-6 col-lg-4 mb-3">
                                                <x-form.input name="wallet_owner" class="" type="text" id="wallet_owner" label=" اسم صاحب المحفظة " placeholder="ادخل اسم صاحب المحفظة  " />
                                            </div>

                                            <div class="col-12 col-md-6 col-lg-4 mb-3">
                                                <x-form.input name="wallet_owner_id_number" class="" type="text" id="wallet_owner_id_number" label=" رقم هوية صاحب المحفظة " placeholder="ادخل رقم هوية صاحب المحفظة" />
                                            </div>

                                        </div>

                                        <p style="color: #777a78">
                                            <img src="{{asset('images/alert-circle.png')}}" alt="">
                                            يفضل أن تكون المحفظة باسم الوصي على اليتيم
                                        </p>

                                        <div class="col-12 col-md-5 mb-4">
                                            <label class="mb-2 fw-bold">  هوية صاحب المحفظة  <span style="color:#777a78;"> (ان وجد) </span></label> <br>
                                            <label for="wallet_owner_id_number_image" class="custom-file-upload text-center" style="width: 90%;color:#777a78;">
                                                <img src="{{asset('images/file.png')}}" alt="" width="50px" height="50px"> ,<br>
                                                اسحب الملف هنا أو اضغط لاختياره
                                            </label>
                                            <x-form.input name="wallet_owner_id_number_image" class="hidden-file-style" type="file" id="wallet_owner_id_number_image" style="display: none;"/>
                                        </div>

                                        <div class="col-12">
                                            <div class="w-100">
                                                <x-form.input name="not_available_wallet_owner_id_number_image" type="text" id="not_available_educational_certificate" label=" في حالة عدم توفر الملف, يرجى ادخال السبب " placeholder="ادخل الرسالة" />
                                            </div>
                                        </div>
                                    </div>

                                </div>



                            </div>
                        </div>

                    </section>

                    <button type="submit" class="submit-btn mt-4 text-center d-flex justify-content-center m-auto w-25"> تسجيل اليتيم </button>

                </form>

            </main>

        </div>


    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <script src="{{asset('js/script.js')}}"></script>
    {{--  --}}
    <script>
        function addRow() {
            const table = document.getElementById("siblingsTable").getElementsByTagName('tbody')[0];
            const rowCount = table.rows.length + 1;
            const row = table.insertRow();
            row.innerHTML = `
                <td>${rowCount}</td>
                <td>
                    <input name="brother_name[]" type="text" placeholder="أدخل اسم الأخ/الأخت" class="rounded form-control" />
                </td>
                <td>
                    <select name="brother_gender[]" class="form-control rounded form-select">
                        <option value="ذكر">ذكر</option>
                        <option value="أنثى">أنثى</option>
                    </select>
                </td>
                <td>
                    <input name="brother_age[]" type="text" placeholder="أدخل العمر" class="rounded form-control" />
                </td>
                <td>
                    <select name="brother_marital_status[]" class="form-control rounded form-select">
                        <option value="أعزب">أعزب</option>
                        <option value="متزوج">متزوج</option>
                        <option value="أرمل">أرمل</option>
                        <option value="مطلق">مطلق</option>
                        <option value="مهجورة">مهجورة</option>
                    </select>
                </td>
                <td>
                    <input name="brother_jop[]" type="text" placeholder="المهنة" class="rounded form-control" />
                </td>
                <td>
                    <input name="brother_id_number[]" type="text" placeholder="رقم الهوية" class="rounded form-control" />
                </td>
            `;
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const radios = document.querySelectorAll('input[name="orphan_status"]');

            const parentDeathDiv = document.getElementById('parent_death');
            const motherDeathDiv = document.getElementById('mother_death');
            const fatherDeathDiv = document.getElementById('father_death');

            // إزالة name من الحقول داخل عنصر
            function disableFields(container) {
                const inputs = container.querySelectorAll('[name]');
                inputs.forEach(input => {
                    input.dataset.name = input.name; // حفظ الاسم في data-name
                    input.removeAttribute('name');   // إزالة الاسم
                });
            }

            // إعادة name إلى الحقول من data-name
            function enableFields(container) {
                const inputs = container.querySelectorAll('[data-name]');
                inputs.forEach(input => {
                    input.setAttribute('name', input.dataset.name);
                });
            }

            function updateSections() {
                const selected = document.querySelector('input[name="orphan_status"]:checked');
                if (!selected) return;

                // إخفاء الكل + إزالة أسماء الحقول
                [parentDeathDiv, motherDeathDiv, fatherDeathDiv].forEach(div => {
                    div.style.display = 'none';
                    disableFields(div);
                });

                // إظهار القسم المطلوب + إعادة أسماء الحقول
                switch (selected.value.trim()) {
                    case 'يتيم الأبوين':
                        parentDeathDiv.style.display = 'block';
                        enableFields(parentDeathDiv);
                        break;
                    case 'يتيم الأم':
                        motherDeathDiv.style.display = 'block';
                        enableFields(motherDeathDiv);
                        break;
                    case 'يتيم الأب':
                        fatherDeathDiv.style.display = 'block';
                        enableFields(fatherDeathDiv);
                        break;
                }
            }

            radios.forEach(radio => {
                radio.addEventListener('change', updateSections);
            });

            updateSections(); // عند التحميل الأولي
        });


    </script>

    {{-- script for bank and wallet --}}
    <script>
        function toggleReceiveDivs() {
            const bankDiv = document.getElementById('receive-bank');
            const walletDiv = document.getElementById('receive-wallet');

            const selected = document.querySelector('input[name="receive_guarantee"]:checked')?.value;

            if (selected === 'bank') {
                bankDiv.style.display = 'block';
                walletDiv.style.display = 'none';
            } else if (selected === 'wallet') {
                bankDiv.style.display = 'none';
                walletDiv.style.display = 'block';
            } else {
                bankDiv.style.display = 'none';
                walletDiv.style.display = 'none';
            }
        }
    </script>




</body>
</html>
