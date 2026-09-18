<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset('css/style.css')}}">
    <title>أمل لأبناء الشهداء</title>
    {{-- <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet"> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.rtl.min.css" integrity="sha384-q8+l9TmX3RaSz3HKGBmqP2u5MkgeN7HrfOJBLcTgZsQsbrx8WqqxdA5PuwUV9WIx" crossorigin="anonymous">
    <style>

        .announcements-carousel-section {
            background-color: #f9fff9;
        }

        .carousel-inner img {
            max-height: 420px;
        }

        .custom-arrow .carousel-control-prev-icon,
        .custom-arrow .carousel-control-next-icon {
            background-color: var(--primary-color);
            border-radius: 50%;
        }

        .stats-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: transform 0.3s;
        }

        .stats-card:hover {
            transform: scale(1.05);
        }

        .stats-circle {
            width: 130px;
            height: 130px;
            border: 4px solid var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        .stats-number {
            font-size: 36px;
            font-weight: bold;
            color: var(--primary-color);
        }

        .stats-label {
            margin-top: 15px;
            font-size: 18px;
            font-weight: 600;
            color: #444;
        }



    </style>
</head>

<body>


    <nav class="navbar navbar-expand-lg">
        <div class="container" >
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="navbar-brand">
                <img src="{{asset('images/logo.png')}}" alt="" loading="eager">
                <img src="{{asset('images/logo2.png')}}" alt="" loading="eager">
            </div>

            <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="gap: 24px">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('home')}}">الرئيسية</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('about.us')}}">من نحن</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}#contact">تواصل معنا</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}#question"> الأسئلة الشائعة </a>
                    </li>
                </ul>

                <div class="d-flex gap-2">
                    <a class="submit-btn text-decoration-none" href="{{route('login')}}" type="button"> تسجيل الدخول </a>

                    <a class="submit-btn text-decoration-none" href="{{route('front.waiting.orphan')}}" type="button"> اكفل يتيم الآن! </a>
                </div>
            </div>
        </div>
    </nav>

    <header class="header py-5">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-5 col-md-6 mb-4 mb-lg-0">
                    <div class="hadith-box p-4 rounded-3">
                        <h2 class="hadith-text mb-3">
                            قال رسول الله ﷺ أنا وَكافلُ اليتيمِ في الجنَّةِ كَهاتين.
                        </h2>
                        <p class="hadith-subtitle">
                            وأشارَ بأصبُعَيْهِ يعني : السَّبَّابةَ والوسطى
                        </p>
                        <p class="description mt-4">
                            منصة إلكترونية موحدة، تجمع الجمعيات المعتمدة والأيتام المحتاجين للدعم، وتفتح المجال للكفلاء لتقديم طلبات كفالة مباشرة وبكل سهولة.
                        </p>
                        <a href="{{route('front.waiting.orphan')}}" class="submit-btn mt-3 text-decoration-none">اكفل يتيم الآن</a>
                    </div>
                </div>

                <div class="col-md-6">
                    {{-- <div class="image-collage position-relative"> --}}
                        {{-- <img src="{{ asset('images/child1.jpg') }}" alt="طفل يتيم" class="img-1 rounded-3">
                        <img src="{{ asset('images/child2.jpg') }}" alt="طفل مع كافل" class="img-2 rounded-3">
                        <img src="{{ asset('images/charity.jpg') }}" alt="جمعية خيرية" class="img-3 rounded-3">
                        <div class="tag tag-1">نقدر تكاليف إنسانية</div>
                        <div class="tag tag-2">جمعيات معتمدة</div>
                        <div class="tag tag-3">نعم كفيل أمل</div>
                        <div class="tag tag-4">عبادة لا توقف</div> --}}
                        <img src="{{asset('images/header.png')}}" alt="" width="100%" loading="eager">
                    {{-- </div> --}}
                </div>

            </div>
        </div>
    </header>


    {{--start about us section --}}

    <div class="about pt-5 pb-5" id="about">

        <div class="introduction text-center mb-5">
            <h2 class="hadith-text mb-3">
                لماذا نحــــــــــن؟
            </h2>

            <p style="color: var(--color)">
                منصة آمنة، منظّمة، ومبنية على الثقة
            </p>
        </div>

        <div class="container">
            <div class="row justify-content-center g-4">

                <div class="col-6 col-lg-3">
                    <div class="feature-card">
                        <div class="icon-wrapper">
                            <img src="{{ asset('images/elements.png') }}" alt="لوحة تحكم">
                        </div>
                        <h3>لوحة تحكم سهلة</h3>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="feature-card">
                        <div class="icon-wrapper">
                            <img src="{{ asset('images/protected.png') }}" alt="حماية">
                        </div>
                        <h3>حماية عالية للبيانات</h3>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="feature-card">
                        <div class="icon-wrapper">
                            <img src="{{ asset('images/order.png') }}" alt="متابعة">
                        </div>
                        <h3>متابعة حالة الطلبات</h3>
                    </div>
                </div>

                <div class="col-6 col-lg-3">
                    <div class="feature-card">
                        <div class="icon-wrapper">
                            <img src="{{ asset('images/people.png') }}" alt="دعم">
                        </div>
                        <h3>  دعم مستمر من فريق مناصرة فلسطين </h3>
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- End about us section --}}


    {{-- Start Call-to-Action Section --}}
    <div class="cta-section py-5 pb-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 text-center">
                    <h2 class="mb-4 title">ابدأ اليوم وكن سببا في صنع فرق  <span>حقيقي</span></h2>
                    <p class="mb-4"> اذا كنت ترغب في كفالة يتيم، منصتنا تخدمك بكل ثقة وسهولة </p>
                    <a class="submit-btn text-decoration-none" href="{{route('front.waiting.orphan')}}">اكفل يتيم الآن</a>
                </div>
            </div>
        </div>
    </div>
    {{-- End Call-to-Action Section --}}

    {{--  --}}

    <section class="announcements-carousel-section py-5">
        <div class="container">
            <h2 class="text-center mb-4 fw-bold" style="color: var(--primary-color)">إعلانات الجمعية</h2>

            <div id="multiAdCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                <div class="carousel-inner">

                    @foreach ($ads->chunk(1) as $index => $chunk)
                        <div class="carousel-item @if ($index == 0) active @endif">
                            <div class="d-flex justify-content-center gap-3">
                                @foreach ($chunk as $ad)
                                    <div style="width: 100%;">
                                        <img src="{{ asset('storage/' . $ad->ad) }}" class="d-block w-100 rounded shadow-sm" alt="إعلان" loading="lazy" decoding="async">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                </div>

                @if($ads->count() > 1)
                    <button class="carousel-control-prev custom-arrow" type="button" data-bs-target="#multiAdCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">السابق</span>
                    </button>
                    <button class="carousel-control-next custom-arrow" type="button" data-bs-target="#multiAdCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">التالي</span>
                    </button>
                @endif
            </div>

        </div>
    </section>

    <section class="py-5 text-center bg-light">
        <div class="container">
            <h2 class="mb-5 text-success">إحصائيات الجمعية</h2>
            <div class="row justify-content-center">

                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="stats-card">
                        <div class="stats-circle">
                            <span class="stats-number" data-count="{{ $orphansCount }}">0</span>
                        </div>
                        <p class="stats-label">عدد الأيتام</p>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="stats-card">
                        <div class="stats-circle">
                            <span class="stats-number" data-count="{{ $orphanSponsorCount }}">0</span>
                        </div>
                        <p class="stats-label">عدد الأيتام المكفولين</p>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="stats-card">
                        <div class="stats-circle">
                            <span class="stats-number" data-count="{{ $sponsorsCount }}">0</span>
                        </div>
                        <p class="stats-label">عدد الكفلاء</p>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-3 mb-4">
                    <div class="stats-card">
                        <div class="stats-circle">
                            <span class="stats-number" data-count="{{ $sponsorshipsCount }}">0</span>
                        </div>
                        <p class="stats-label">عدد الكفالات</p>
                    </div>
                </div>

            </div>
        </div>
    </section>



    {{--  --}}


    {{-- start contact section --}}

    <div class="contact-section py-5 pb-5" id="contact">
        <div class="container">
            <div class="row align-items-center justify-content-between">

                <div class="col-lg-5">
                    <div class="contact-info text-white">
                        <h2 class="mb-4"> تواصــــــــــــــــــــــــــــل معنا </h2>
                        <p class="mb-4">لديك استفسار؟ <br> فريقنا موجود لخدمتك والإجابة على كل أسئلتك.</p>

                        <div class="social">

                            <hr style="width: 90%">

                            <p class="fw-semibold mt-4">تابعنا على منصات التواصل الاجتماعي</p>
                            <div class="social-links">
                                <a href="#" class="me-3"><img src="{{ asset('images/linkedin-02.png') }}" alt="LinkedIn"></a>
                                <a href="#" class="me-3"><img src="{{ asset('images/facebook-02.png') }}" alt="Facebook"></a>
                                <a href="https://x.com/pss_bah" class="me-3"><img src="{{ asset('images/new-twitter.png') }}" alt="X"></a>
                                <a href="https://www.instagram.com/pss_bah/?igsh=bmRmcG1iamtod3c1#"><img src="{{ asset('images/instagram.png') }}" alt="Instagram"></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="contact-form p-4 rounded-3 bg-white">
                        <x-alert name="success" />
                        <x-alert name="danger" />
                        <form method="post" action="{{ route('contact.send') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">الاسم</label>
                                <input name="name" type="text" class="form-control" id="name" placeholder="ادخل الاسم">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">البريد الإلكتروني</label>
                                <input name="email" type="email" class="form-control" id="email" placeholder="ادخل بريدك الإلكتروني">
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">رسالتك</label>
                                <textarea name="message" class="form-control" id="message" rows="3" placeholder="ادخل رسالتك"></textarea>
                            </div>
                            <button type="submit" class="submit-btn w-100">إرسال</button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- End contact section --}}

    {{-- start question section --}}

    <div class="question" style="padding-top: 70px; padding-bottom:70px" id="question">
        <div class="container">

            <h2 class="mb-5 text-center" style="color: var(--primary-color)"> الأسئلة الشائعة </h2>

            <div class="faq-section">
                <div class="container">

                    @foreach ($questions as $question)

                        <div class="faq-item">
                            <div class="faq-question" onclick="toggleFaq('faq{{$question->id}}')">
                                <span class="faq-toggle">+</span> {{$question->question}}
                            </div>
                            <div class="faq-answer" id="faq{{$question->id}}">
                                {{$question->answer}}
                            </div>
                        </div>

                    @endforeach



                </div>
            </div>

        </div>
    </div>

    {{-- End question section --}}

    {{--Start footer --}}

    <footer style="background-color: var(--primary-color)">

        <div class="container">
            <div class="content d-flex justify-content-between align-items-center">

                <div>

                    <ul class="p-0 m-0">
                        <li class="list-inline-item">
                            <a href="{{route('about.us')}}" class="text-white text-decoration-none">من نحن</a>
                        </li>
                        <li class="list-inline-item text-white">|</li>
                        <li class="list-inline-item">
                            <a href="#" class="text-white text-decoration-none">سياسة الخصوصية</a>
                        </li>
                        <li class="list-inline-item text-white">|</li>
                        <li class="list-inline-item">
                            <a href="{{ route('home') }}#contact" class="text-white text-decoration-none">تواصل معنا</a>
                        </li>
                    </ul>

                </div>

                <div class="text-white pt-2 pb-2">
                    © 2025 جمعية مناصرة فلسطين
                </div>

            </div>
        </div>

    </footer>

    {{-- End Footer --}}



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <script src="{{asset('js/script.js')}}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const counters = document.querySelectorAll("[data-count]");
            counters.forEach(counter => {
                let target = +counter.getAttribute("data-count");
                let count = 0;
                let step = Math.ceil(target / 100);
                let interval = setInterval(() => {
                    count += step;
                    if (count >= target) {
                        count = target;
                        clearInterval(interval);
                    }
                    counter.textContent = count;
                }, 80); // كل 30 ملي ثانية
            });
        });
    </script>

</body>
</html>
