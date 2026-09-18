<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$title}}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- JQVMap -->
    <link rel="stylesheet" href="{{asset('plugins/jqvmap/jqvmap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('dist/css/adminlte.min.css')}}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{asset('plugins/summernote/summernote-bs4.min.css')}}">

    <link rel="stylesheet" href="{{asset('css/style.css')}}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.rtl.min.css" integrity="sha384-q8+l9TmX3RaSz3HKGBmqP2u5MkgeN7HrfOJBLcTgZsQsbrx8WqqxdA5PuwUV9WIx" crossorigin="anonymous">

    <style>

        .nav-item{
            width: 100%;
        }

        .nav-link{
            padding-right: 5px;
            width: 100% !important;
        }

        .li-active{
            background:linear-gradient(to left , #c6fdda , #edfaf1)
        }

        .link-active{
            color: var(--primary-color) !important;
        }

        @media (max-width: 475px) {
            .welcome,
            .profile-image{
                display: none !important;
            }
        }


    </style>

    @stack('styles')

</head>

<body class="hold-transition sidebar-mini layout-fixed" dir="rtl">

    <div class="wrapper">

        <!-- Preloader -->
        <div class="preloader flex-column justify-content-center align-items-center">
            <img class="animation__shake" src="{{asset('images/logo.png')}}" alt="1000 أمل لأبناء الشهداء" height="60" width="60">
        </div>

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light" style="border-bottom:none">

            <ul class="navbar-nav ml-auto gap-4 f-flex align-items-center">

                <a class="" data-widget="pushmenu" href="#" role="button" style="color:var(--primary-color)"><i class="fas fa-bars"></i></a>

                <div style="color:var(--primary-color); font-size: 18px;" class=" d-flex align-items-center gap-2 fw-bold">

                    @php
                        $guards = ['web', 'researcher', 'association', 'sponsor', 'orphan'];
                        $user = null;
                        $guard = null;

                        foreach ($guards as $g) {
                            if (Auth::guard($g)->check()) {
                                $user = Auth::guard($g)->user();
                                $guard = $g;
                                break;
                            }
                        }

                        $nameParts = explode(' ', $user->name ?? '');
                        $displayName = '';

                        if (in_array($guard, ['sponsor', 'researcher', 'orphan'])) {
                            $displayName = $nameParts[0] ?? '';
                        } elseif (in_array($guard, ['web', 'association'])) {
                            $displayName = ($nameParts[0] ?? '') . ' ' . ($nameParts[1] ?? '');
                        }
                    @endphp


                    مرحبًا بعودتك, <span class="welcome">{{ $displayName }}</span>


                    <img src="{{asset('images/hand.png')}}" alt="">
                </div>

            </ul>

            <!-- Left navbar links -->
            <ul class="navbar-nav">

                <img src="{{asset('images/profile.png')}}" alt="" class="profile-image">

                <div class="dropdown d-flex align-items-center gap-1" >
                    <button class="btn  dropdown-toggle border-0 d-flex align-items-center gap-2 fw-bold"data-bs-toggle="dropdown" aria-expanded="false" style="color: var(--primary-color)">
                        {{$displayName}}
                    </button>
                    <ul class="dropdown-menu" style="transform: translateX(70px);">
                        <li><a class="dropdown-item" @if (Auth::guard('orphan')->check()) href="{{route('orphan.primary.index')}}" @else  href="{{route('profile.show')}}" @endif >الصفحة الشخصية</a></li>
                        <li><a class="dropdown-item" href="{{route('profile.edit')}}" > تغيير كلمة المرور </a></li>
                    </ul>
                  </div>
            </ul>

        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary  p-3" style="right:0px !important; left: initial !important; background-color:#f8fcf9">
            <!-- Brand Logo -->
            <div class="mt-4 mb-5 d-flex justify-content-between">
                <img src="{{asset('images/logo.png')}}" alt="">
                <div style="width: 1px; background-color:#ddd"></div>
                <img src="{{asset('images/logo2.png')}}" alt="">
            </div>

            <!-- Sidebar -->
            <div class="sidebar">

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <!-- Add icons to the links using the .nav-icon class
                            with font-awesome or any other icon font library -->
                        {{-- <li class="nav-item menu-open">
                            <a href="#" class="nav-link active">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                Dashboard
                                <i class="right fas fa-angle-left"></i>
                            </p>
                            </a>
                            <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="./index.html" class="nav-link active">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dashboard v1</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="./index2.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dashboard v2</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="./index3.html" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Dashboard v3</p>
                                </a>
                            </li>
                            </ul>
                        </li> --}}

                        @auth('researcher')

                            <li class="nav-item rounded {{Route::is('researcher.orphans.first.create')?'li-active':''}} ">
                                <a href="{{route('researcher.orphans.first.create')}}" class="nav-link  d-flex gap-2 {{Route::is('researcher.orphans.first.create')?'link-active':''}}">
                                <img src="{{asset('images/sidebar/add-user.png')}}" alt="">
                                    <p> إضافة يتيم بشكل أولي</p>
                                </a>
                            </li>


                            <li class="nav-item rounded {{Route::is('researcher.orphans.create')?'li-active':''}}">
                                <a href="{{route('researcher.orphans.create')}}" class="nav-link d-flex gap-2 {{Route::is('researcher.orphans.create')?'link-active':''}}">
                                <img src="{{asset('images/sidebar/add-user.png')}}" alt="">
                                    <p> إضافة يتيم </p>
                                </a>
                            </li>

                            <li class="nav-item rounded {{Route::is('researcher.registered')?'li-active':''}}"> <!-- li-active -->
                                <a href="{{route('researcher.registered')}}" class="nav-link d-flex gap-2 {{Route::is('researcher.registered')?'link-active':''}}">  <!-- link-active -->
                                    <img src="{{asset('images/sidebar/happy.png')}}" alt="">
                                    <p> الأيتام المسجَّلون أوليًا</p>
                                </a>
                            </li>

                            <li class="nav-item rounded {{Route::is('researcher.orphan.index')?'li-active':''}}"> <!-- li-active -->
                                <a href="{{route('researcher.orphan.index')}}" class="nav-link d-flex gap-2 {{Route::is('researcher.orphan.index')?'link-active':''}}" style="color:black">  <!-- link-active -->
                                    <img src="{{asset('images/sidebar/happy.png')}}" alt="">
                                    <p> الأيتام المرشحين </p>
                                </a>
                            </li>

                            <li class="nav-item  rounded {{Route::is('researcher.expenses.create')?'li-active':''}}">
                                <a href="{{route('researcher.expenses.create')}}" class="nav-link d-flex gap-2 {{Route::is('researcher.expenses.create')?'link-active':''}}">
                                    <img src="{{asset('images/transfers.svg')}}" alt="">
                                    <p> المبالغ المدفوعة </p>
                                </a>
                            </li>

                            <li class="nav-item rounded {{Route::is('researcher.orphan.media.index')?'li-active':''}}"> <!-- li-active -->
                                <a href="{{route('researcher.orphan.media.index')}}" class="nav-link d-flex gap-2 {{Route::is('researcher.orphan.media.index')?'link-active':''}}" style="color:black">  <!-- link-active -->
                                    <img src="{{asset('images/sidebar/happy.png')}}" alt="">
                                    <p> شاهد يتيمك (صور، فيديو) </p>
                                </a>
                            </li>

                            {{-- <li class="nav-item  rounded {{Route::is('orphan.message.create')?'li-active':''}}">
                                <a href="{{route('orphan.message.create')}}" class="nav-link d-flex gap-2 {{Route::is('orphan.message.create')?'link-active':''}}">
                                    <img src="{{asset('images/sidebar/message.png')}}" alt="">
                                    <p> الرسائل </p>
                                </a>
                            </li> --}}

                        @endauth

                        @auth('orphan')

                            <li class="nav-item rounded {{Route::is('orphan.primary.index')?'li-active':''}}"> <!-- li-active -->
                                <a href="{{route('orphan.primary.index')}}" class="nav-link d-flex gap-2 {{Route::is('orphan.primary.index')?'link-active':''}}">  <!-- link-active -->
                                    <img src="{{asset('images/sidebar/home.png')}}" alt="">
                                     الرئيسية
                                </a>
                            </li>



                            <li class="nav-item rounded {{Route::is('orphan.primary.balance')?'li-active':''}}"> <!-- li-active -->
                                <a href="{{route('orphan.primary.balance')}}" class="nav-link d-flex gap-2 {{Route::is('orphan.primary.balance')?'link-active':''}}">  <!-- link-active -->
                                    <img src="{{asset('images/transfers.svg')}}" alt="">
                                     الرصيد المالي
                                </a>
                            </li>

                            <li class="nav-item  rounded {{Route::is('orphan.message.create')?'li-active':''}}">
                                <a href="{{route('orphan.message.create')}}" class="nav-link d-flex gap-2 {{Route::is('orphan.message.create')?'link-active':''}}">
                                    <img src="{{asset('images/sidebar/messenger.png')}}" alt="">
                                     الرسائل
                                </a>
                            </li>

                            <li class="nav-item  rounded {{Route::is('orphan.notification')?'li-active':''}}">
                                <a href="{{route('orphan.notification')}}" class="nav-link d-flex justify-content-between gap-2 {{Route::is('orphan.notification')?'link-active':''}}">

                                    <div>
                                        <img src="{{asset('images/sidebar/bell.png')}}" alt="">
                                        الإشعارات
                                    </div>
                                    {{-- @dd($unreadCountNotification) --}}
                                    @if($unreadCountNotification > 0)
                                        <span class="badge" style="background-color: #d5fbe3; color:var(--primary-color)"> {{$unreadCountNotification}} </span>
                                    @endif
                                </a>
                            </li>

                        @endauth


                        @auth('association')




                                    <li class="nav-item rounded ">
                                        <a href="#" class="nav-link {{Route::is('association.orphan.*')?'link-active':''}}  {{Route::is('association.orphan.*')?'li-active':''}}">
                                            <img src="{{asset('images/sidebar/orphan (1).png')}}" alt="">
                                             <p >الأيتام</p>
                                            <i class="right fas fa-angle-left" style="transform:translateX(-140px)"></i>
                                        </a>

                                        <ul class="nav nav-treeview">


                                            <li class="nav-item rounded">
                                                <a href="{{route('association.orphan.create')}}" class="nav-link  ms-2">
                                                <img src="{{asset('images/sidebar/add-user.png')}}" alt="">
                                                    <p> إضافة يتيم </p>
                                                </a>
                                            </li>

                                             <li class="nav-item rounded">
                                                <a href="{{route('association.orphan.register')}}" class="nav-link  ms-2">
                                                <img src="{{asset('images/sidebar/candidate.png')}}" alt="">
                                                    <p> الأيتام المسجَّلون أوليًا </p>
                                                </a>
                                            </li>

                                            <li class="nav-item rounded">
                                                <a href="{{route('association.orphan.candidate')}}" class="nav-link  ms-2">
                                                <img src="{{asset('images/sidebar/candidate.png')}}" alt="">
                                                    <p> الأيتام المرشحون </p>
                                                </a>
                                            </li>
                                            <li class="nav-item rounded">
                                                <a href="{{route('association.orphan.auditor')}}" class="nav-link ms-2">
                                                <img src="{{asset('images/sidebar/auditor.png')}}" alt="">
                                                <p> الأيتام المدققون </p>
                                                </a>
                                            </li>
                                            <li class="nav-item rounded">
                                                <a href="{{route('association.orphan.certified')}}" class="nav-link ms-2">
                                                <img src="{{asset('images/sidebar/certified.png')}}" alt="">
                                                <p> الأيتام المعتمدون </p>
                                                </a>
                                            </li>

                                            <li class="nav-item rounded">
                                                <a href="{{route('association.orphan.waiting')}}" class="nav-link ms-2">
                                                <img src="{{asset('images/sidebar/clock.png')}}" alt="">
                                                <p> الأيتام قيد الانتظار </p>
                                                </a>
                                            </li>

                                            <li class="nav-item rounded">
                                                <a href="{{route('association.orphan.sponsored')}}" class="nav-link ms-2">
                                                <img src="{{asset('images/sidebar/charity.png')}}" alt="">
                                                <p> الأيتام المكفولون </p>
                                                </a>
                                            </li>
                                            <li class="nav-item rounded">
                                                <a href="{{route('association.orphan.archived')}}" class="nav-link ms-2 {{Route::is('association.orphan.archived')?'link-active':''}}">
                                                <img src="{{asset('images/sidebar/people.png')}}" alt="">
                                                <p> الأيتام المؤرشفون </p>
                                                </a>
                                            </li>


                                        </ul>
                                    </li>

                                    <li class="nav-item  rounded {{Route::is('association.amountsPaid.*')?'li-active':''}}">
                                        <a href="{{route('association.researcher.index')}}" class="nav-link d-flex gap-2 {{Route::is('association.amountsPaid.*')?'link-active':''}}">
                                            <img src="{{asset('images/sidebar/research.png')}}" alt="">
                                            <p> الباحثون </p>
                                        </a>
                                    </li>


                                    <li class="nav-item  rounded {{Route::is('association.expenses.*')?'li-active':''}}">
                                        <a href="{{route('association.expenses.index')}}" class="nav-link d-flex gap-2 {{Route::is('association.expenses.*')?'link-active':''}}">
                                            <img src="{{asset('images/transfers.svg')}}" alt="">
                                            <p> المبالغ المدفوعة </p>
                                        </a>
                                    </li>

                                    <li class="nav-item rounded {{Route::is('association.sponsorship-delivery.*')?'li-active':''}}">
                                        <a href="{{route('association.sponsorship-delivery.create')}}" class="nav-link d-flex gap-2 {{Route::is('association.sponsorship-delivery.*')?'link-active':''}}">
                                            <img src="{{asset('images/sidebar/certified.png')}}" alt="">
                                            <p> تسليم الكفالات </p>
                                        </a>
                                    </li>



                                    <li class="nav-item rounded">
                                        <a href="" class="nav-link {{Route::is('admin.report.*')?'li-active':''}} {{Route::is('admin.report.*')?'link-active':''}}"  >
                                            <img src="{{asset('images/sidebar/orphan (1).png')}}" alt="">

                                            <p >
                                                التقارير
                                            </p>
                                            <i class="right fas fa-angle-left" style="transform:translateX(-140px)"></i>

                                        </a>
                                        <ul class="nav nav-treeview">


                                            @can('view-all-reports')
                                                <li class="nav-item rounded">
                                                    <a href="{{route('admin.report.sponsorship')}}" class="nav-link ms-2 {{Route::is('admin.report.sponsorship')?'link-active':''}}">
                                                        <img src="{{asset('images/sidebar/certified.png')}}" alt="">
                                                        تقارير الكفالات
                                                    </a>
                                                </li>
                                            @endcan

                                            <li class="nav-item rounded">
                                                <a href="{{route('admin.report.orphan')}}" class="nav-link ms-2 {{Route::is('admin.report.orphan')?'link-active':''}}">
                                                    <img src="{{asset('images/sidebar/clock.png')}}" alt="">
                                                    تقارير الأيتام
                                                </a>
                                            </li>

                                            @can('view-all-reports')
                                                <li class="nav-item rounded">
                                                    <a href="{{route('admin.report.gift')}}" class="nav-link ms-2 {{Route::is('admin.report.gift')?'link-active':''}}">
                                                        <img src="{{asset('images/sidebar/certified.png')}}" alt="">
                                                        تقارير الهدايا
                                                    </a>
                                                </li>
                                            @endcan

                                        </ul>
                                    </li>

                                    <li class="nav-item  rounded {{Route::is('association.message.*')?'li-active':''}}">
                                        <a href="{{route('association.message.index')}}" class="nav-link d-flex gap-2 {{Route::is('association.message.*')?'link-active':''}}">
                                            <div>
                                                <img src="{{asset('images/sidebar/messenger.png')}}" alt="">
                                                <p> رسائل الأيتام </p>
                                            </div>
                                            @if($unreadSponsorCount > 0)
                                                <span class="badge" style="background-color: #d5fbe3; color:var(--primary-color)"> {{$unreadSponsorCount}} </span>
                                            @endif
                                        </a>
                                    </li>

                                    <li class="nav-item  rounded {{Route::is('association.notification')?'li-active':''}}">
                                        <a href="{{route('association.notification')}}" class="nav-link d-flex justify-content-between gap-2 {{Route::is('association.notification')?'link-active':''}}">

                                            <div>
                                                <img src="{{asset('images/sidebar/bell.png')}}" alt="">
                                                الإشعارات
                                            </div>
                                            @if($unreadCountNotification > 0)
                                                <span class="badge" style="background-color: #d5fbe3; color:var(--primary-color)"> {{$unreadCountNotification}} </span>
                                            @endif
                                        </a>
                                    </li>





                        @endauth


                        @auth('sponsor')

                            <li class="nav-item rounded">
                                <a href="#" class="nav-link {{Route::is('sponsor.orphan.*')?'link-active':''}}  {{Route::is('sponsor.orphan.*')?'li-active':''}}">
                                    <img src="{{asset('images/sidebar/orphan (1).png')}}" alt="">
                                     <p >الأيتام</p>
                                    <i class="right fas fa-angle-left" style="transform:translateX(-140px)"></i>
                                </a>

                                <ul class="nav nav-treeview">

                                    <li class="nav-item rounded">
                                        <a href="{{route('sponsor.orphan.waiting.index')}}" class="nav-link ms-2">
                                        <img src="{{asset('images/sidebar/clock.png')}}" alt="">
                                        <p> الأيتام قيد الانتظار </p>
                                        </a>
                                    </li>

                                    <li class="nav-item rounded">
                                        <a href="{{route('sponsor.orphan.sponsor.index')}}" class="nav-link ms-2">
                                        <img src="{{asset('images/sidebar/charity.png')}}" alt="">
                                        <p> الأيتام المكفولون </p>
                                        </a>
                                    </li>

                                </ul>
                            </li>

                            <li class="nav-item  rounded {{Route::is('sponsor.message.view')?'li-active':''}}">
                                <a href="{{route('sponsor.message.view')}}" class="nav-link d-flex gap-2 justify-content-between {{Route::is('sponsor.message.view')?'link-active':''}}">
                                    <div>
                                        <img src="{{asset('images/sidebar/messenger.png')}}" alt="">
                                        <p> رسائل الأيتام </p>
                                    </div>
                                    @if($unreadSponsorCount > 0)
                                        <span class="badge" style="background-color: #d5fbe3; color:var(--primary-color)"> {{$unreadSponsorCount}} </span>
                                    @endif
                                </a>
                            </li>

                            <li class="nav-item  rounded {{Route::is('sponsor.notification')?'li-active':''}}">
                                <a href="{{route('sponsor.notification')}}" class="nav-link d-flex justify-content-between gap-2 {{Route::is('sponsor.notification')?'link-active':''}}">
                                    <div>
                                        <img src="{{asset('images/sidebar/bell.png')}}" alt="">
                                        الإشعارات
                                    </div>
                                    @if($unreadCountNotification > 0)
                                        <span class="badge" style="background-color: #d5fbe3; color:var(--primary-color)"> {{$unreadCountNotification}} </span>
                                    @endif
                                </a>
                            </li>

                        @endauth


                        @auth('web')

                            @can('show-admin')

                            <li class="nav-item rounded "> <!-- li-active -->
                                <a href="" class="nav-link d-flex gap-2 ">  <!-- link-active -->
                                    <img src="{{asset('images/sidebar/home.png')}}" alt="">
                                    <p style="color: var(--primary-color)"> الرئيسية </p>
                                </a>
                            </li>

                            <li class="nav-item  rounded {{Route::is('admin.association.*')?'li-active':''}}">
                                <a href="{{route('admin.association.index')}}" class="nav-link d-flex gap-2 {{Route::is('admin.association.*')?'link-active':''}}">
                                    <img src="{{asset('images/sidebar/professional.png')}}" alt="">
                                    <p> الجمعيات </p>
                                </a>
                            </li>


                            <li class="nav-item  rounded {{Route::is('admin.sponsor.*')?'li-active':''}}">
                                <a href="{{route('admin.sponsor.index')}}" class="nav-link d-flex gap-2 {{Route::is('admin.sponsor.*')?'link-active':''}}">
                                    <img src="{{asset('images/sidebar/deal.png')}}" alt="">
                                    <p> الكفلاء </p>
                                </a>
                            </li>

                            <li class="nav-item rounded">
                                <a href="" class="nav-link {{Route::is('admin.orphan.*')?'li-active':''}} {{Route::is('admin.orphan.*')?'link-active':''}}"  >
                                    <img src="{{asset('images/sidebar/orphan (1).png')}}" alt="">

                                    <p >
                                        الأيتام
                                    </p>
                                    <i class="right fas fa-angle-left" style="transform:translateX(-140px)"></i>

                                </a>
                                <ul class="nav nav-treeview">

                                    <li class="nav-item rounded">
                                        <a href="{{route('admin.orphan.index')}}" class="nav-link ms-2 {{Route::is('admin.orphan.index')?'link-active':''}}">
                                        <img src="{{asset('images/sidebar/people.png')}}" alt="">
                                            جميع الأيتام
                                        </a>
                                    </li>

                                    <li class="nav-item rounded">
                                        <a href="{{route('admin.orphan.CertifiedOrphan')}}" class="nav-link ms-2 {{Route::is('admin.orphan.CertifiedOrphan')?'link-active':''}}">
                                            <img src="{{asset('images/sidebar/certified.png')}}" alt="">

                                            الأيتام المعتمدون
                                        </a>
                                    </li>

                                    <li class="nav-item rounded">
                                        <a href="{{route('admin.orphan.UnsponsoredOrphan')}}" class="nav-link ms-2">
                                            <img src="{{asset('images/sidebar/clock.png')}}" alt="">

                                            <p> الأيتام المرشحون للكفالة </p>
                                        </a>
                                    </li>

                                    <li class="nav-item rounded">
                                        <a href="{{route('admin.orphan.SponsoredOrphan')}}" class="nav-link ms-2 {{Route::is('admin.orphan.SponsoredOrphan')?'link-active':''}}">
                                        <img src="{{asset('images/sidebar/charity.png')}}" alt="">

                                            الأيتام المكفولين
                                        </a>
                                    </li>
                                    <li class="nav-item rounded">
                                        <a href="{{route('admin.orphan.ArchivedOrphan')}}" class="nav-link ms-2 {{Route::is('admin.orphan.ArchivedOrphan')?'link-active':''}}">
                                        <img src="{{asset('images/sidebar/people.png')}}" alt="">

                                            الأيتام المؤرشفون
                                        </a>
                                    </li>

                                </ul>
                            </li>

                            @endcan

                            @can('view-reports')
                            <li class="nav-item rounded">
                                <a href="" class="nav-link {{Route::is('admin.report.*')?'li-active':''}} {{Route::is('admin.report.*')?'link-active':''}}"  >
                                    <img src="{{asset('images/sidebar/orphan (1).png')}}" alt="">

                                    <p >
                                        التقارير
                                    </p>
                                    <i class="right fas fa-angle-left" style="transform:translateX(-140px)"></i>

                                </a>
                                <ul class="nav nav-treeview">

                                    @can('view-all-reports')
                                        <li class="nav-item rounded">
                                            <a href="{{route('admin.report.sponsor')}}" class="nav-link ms-2 {{Route::is('admin.report.sponsor')?'link-active':''}}">
                                            <img src="{{asset('images/sidebar/people.png')}}" alt="">
                                                تقارير الكفلاء
                                            </a>
                                        </li>
                                    @endcan

                                    @can('view-all-reports')
                                        <li class="nav-item rounded">
                                            <a href="{{route('admin.report.sponsorship')}}" class="nav-link ms-2 {{Route::is('admin.report.sponsorship')?'link-active':''}}">
                                                <img src="{{asset('images/sidebar/certified.png')}}" alt="">
                                                تقارير الكفالات
                                            </a>
                                        </li>
                                    @endcan

                                    <li class="nav-item rounded">
                                        <a href="{{route('admin.report.orphan')}}" class="nav-link ms-2 {{Route::is('admin.report.orphan')?'link-active':''}}">
                                            <img src="{{asset('images/sidebar/clock.png')}}" alt="">
                                            تقارير الأيتام
                                        </a>
                                    </li>

                                    @can('view-all-reports')
                                        <li class="nav-item rounded">
                                            <a href="{{route('admin.report.gift')}}" class="nav-link ms-2 {{Route::is('admin.report.gift')?'link-active':''}}">
                                                <img src="{{asset('images/sidebar/certified.png')}}" alt="">
                                                تقارير الهدايا
                                            </a>
                                        </li>
                                    @endcan

                                    @can('view-all-reports')
                                        <li class="nav-item rounded">
                                            <a href="{{route('admin.report.financial')}}" class="nav-link ms-2 {{Route::is('admin.report.financial')?'link-active':''}}">
                                                <img src="{{asset('images/sidebar/certified.png')}}" alt="">
                                                التقارير المالية
                                            </a>
                                        </li>
                                    @endcan

                                    @can('show-admin')
                                        <li class="nav-item rounded">
                                            <a href="{{route('admin.sponsorship-delivery.index')}}" class="nav-link ms-2 {{Route::is('admin.sponsorship-delivery.index')?'link-active':''}}">
                                                <img src="{{asset('images/sidebar/certified.png')}}" alt="">
                                                عرض تسليم الكفالات
                                            </a>
                                        </li>
                                    @endcan

                                </ul>
                            </li>
                            @endcan

                            @can('show-admin')
                            <li class="nav-item  rounded {{Route::is('admin.ad.*')?'li-active':''}}">
                                <a href="{{route('admin.ad.index')}}" class="nav-link d-flex gap-2 {{Route::is('admin.ad.*')?'link-active':''}}">
                                    <img src="{{asset('images/sidebar/frequently-asked-questions.png')}}" alt="">
                                    <p> الإعلانات </p>
                                </a>
                            </li>

                            <li class="nav-item  rounded {{Route::is('admin.question.*')?'li-active':''}}">
                                <a href="{{route('admin.question.index')}}" class="nav-link d-flex gap-2 {{Route::is('admin.question.*')?'link-active':''}}">
                                    <img src="{{asset('images/sidebar/frequently-asked-questions.png')}}" alt="">
                                    <p> الأسئلة الشائعة </p>
                                </a>
                            </li>

                            <li class="nav-item  rounded {{Route::is('admin.notification')?'li-active':''}}">
                                <a href="{{route('admin.notification')}}" class="nav-link d-flex justify-content-between gap-2 {{Route::is('admin.notification')?'link-active':''}}">

                                    <div>
                                        <img src="{{asset('images/sidebar/bell.png')}}" alt="">
                                         الإشعارات
                                    </div>
                                    @if($unreadCountNotification > 0)
                                        <span class="badge" style="background-color: #d5fbe3; color:var(--primary-color)"> {{$unreadCountNotification}} </span>
                                    @endif
                                </a>
                            </li>

                            <li class="nav-item  rounded {{Route::is('admin.message.view')?'li-active':''}}">
                                <a href="{{route('admin.message.view')}}" class="nav-link d-flex gap-2 justify-content-between {{Route::is('admin.message.view')?'link-active':''}}">
                                    <div>
                                        <img src="{{asset('images/sidebar/messenger.png')}}" alt="">
                                        <p> رسائل الأيتام </p>
                                    </div>
                                    @if($unreadSponsorCount > 0)
                                        <span class="badge" style="background-color: #d5fbe3; color:var(--primary-color)"> {{$unreadSponsorCount}} </span>
                                    @endif
                                </a>
                            </li>

                            <li class="nav-item  rounded {{Route::is('admin.sponsors.email')?'li-active':''}}">
                                <a href="{{route('admin.sponsors.email')}}" class="nav-link d-flex gap-2 justify-content-between {{Route::is('admin.sponsors.email')?'link-active':''}}">
                                    <div>
                                        <img src="{{asset('images/sidebar/messenger.png')}}" alt="">
                                        <p> رسائل للكفلاء </p>
                                    </div>
                                </a>
                            </li>
                            @endcan


                        @endauth

                        <li class="nav-item  rounded " style="border:1px solid red">

                            {{-- <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log ') }}
                                </x-dropdown-link>
                            </form> --}}
                            <form action="{{route('logout')}}" method="post">
                                @csrf

                                <button type="submit" class="nav-link d-flex gap-2">
                                    <img src="{{asset('images/sidebar/logout.png')}}" alt="">
                                    <p class="text-danger"> تسجيل الخروج </p>
                                </button>

                            </form>

                        </li>

                    </ul>
                </nav>
            <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>


        <div class="content-wrapper mb-4" style="min-height: 232px; background-color: #f0fff4">
            <!-- Main content -->
            <section class="content">
              <div class="container-fluid">
                    <div class="bg-white" style="margin-right:1rem; margin-left:1rem;border-radius:15px;border-top:none">

                        <main style="margin-right: 2rem;margin-left:2rem;padding-top:25px;padding-bottom:25px">
                            {{$slot}}
                        </main>
                    </div>

              </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>


        <!-- /.content-wrapper -->
        <footer class="main-footer text-center p-1" style="background-color: #d7fae3">
            © 2025 جمعية 1000 أمل لأبناء الشهداء – جمعية مناصرة فلسطين. جميع الحقوق محفوظة.
        </footer>

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Control sidebar content goes here -->
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{asset('plugins/jquery-ui/jquery-ui.min.css')}}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <!-- ChartJS -->
    <script src="{{asset('plugins/chart.js/Chart.min.js')}}"></script>
    <!-- Sparkline -->
    <script src="{{asset('plugins/sparklines/sparkline.js')}}"></script>
    <!-- JQVMap -->
    <script src="{{asset('plugins/jqvmap/jquery.vmap.min.js')}}"></script>
    <script src="{{asset('plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{asset('plugins/jquery-knob/jquery.knob.min.js')}}"></script>
    <!-- daterangepicker -->
    <script src="{{asset('plugins/moment/moment.min.js')}}"></script>
    <script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{asset('plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
    <!-- Summernote -->
    <script src="{{asset('plugins/summernote/summernote-bs4.min.js')}}"></script>
    <!-- overlayScrollbars -->
    <script src="{{asset('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('dist/js/adminlte.js')}}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{asset('dist/js/demo.js')}}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{asset('dist/js/pages/dashboard.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>

    <script src="{{asset('js/script.js')}}"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"
        integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html>

