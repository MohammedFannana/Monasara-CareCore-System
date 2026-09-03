<!DOCTYPE html>
<html lang="ar">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title></title>

    <style>
        body {
            font-family: 'arialarabic';
            direction: rtl;
            margin: auto !important;
            margin-top: 2px !important;
            padding: 0 !important;
            width: 210mm !important; /* عرض A4 */
            height: 297mm !important; /* ارتفاع A4 */
            box-sizing: border-box !important;
        }

        .container{
            width: 100%;
            height: 100%;
        }


        .cell{
            text-align: center;
            margin: 0;
            padding:5px 0 5px 5px;
        }

        /* .border{
            border:1px solid #BA3A37;
        } */

        .font{
            font-weight:bold;
            text-align:start;
            font-size: 19px;

        }

        .border {
            font-size: 18px;
            border: 1px solid #1e9448;
            box-shadow: 3px 3px 0px #ddfce8;
            direction: rtl;
            font-family: 'Arial', sans-serif;
        }


    </style>

</head>

<body>


    <div class="container">




        {{-- الدولة  and عنوان الجهة المشرفة--}}
        @foreach ($orphans as $orphan)

            <div style="width:100%">
            <!-- الشعار على اليمين -->
                <div style="float: right;margin-right:150px;width: 100%;overflow: hidden; margin-bottom: 10px;">
                    <div style="float: right; width:50%">
                        <img src="{{ public_path('images/logo.png') }}" alt="Logo" style="width:80px; height: 80px;">
                    </div>
                    <div class="float:left;width:50%">
                        <img src="{{ public_path('images/logo2.png') }}" alt="Logo" style="width:80px; height: 80px;">
                    </div>

                    <div class="font" style="padding:0px;margin:0px 110px;color:#1e9448;">
                        {{ $date->translatedFormat('Y-m-d') }}
                     </div>
                </div>



            </div>


            <div style="width: 50%; float:right; overflow: hidden; margin-bottom: 12px;">
                <p style=" width: 100%;margin-right:3px" class="cell font"> اسم اليتيم </p>
                <p style="width: 90%;" class="cell border">
                    {{ $orphan->name}}
                </p>
            </div>

            <div style="width: 50%; float:right; overflow: hidden; margin-bottom: 12px;">
                <p style=" width: 100%;margin-right:3px" class="cell font">  اسم الجمعية </p>
                <p style="width: 90%;" class="cell border">
                    {{ $orphan->association->name}}
                </p>
            </div>

            @if($orphan->birth_date)
                <div style="width: 50%; float:right; overflow: hidden; margin-bottom: 12px;">
                    <p style=" width: 100%;margin-right:3px" class="cell font"> تاريخ الميلاد </p>
                    <p style="width: 90%;" class="cell border">
                        {{$orphan->birth_date}}
                    </p>
                </div>
            @endif

            @if( $orphan->birth_place)
                <div style="width: 50%; float:right; overflow: hidden; margin-bottom: 12px;">
                    <p style=" width: 100%;margin-right:3px" class="cell font">  مكان الميلاد </p>
                    <p style="width: 90%;" class="cell border">
                        {{  $orphan->birth_place }}
                    </p>
                </div>
            @endif

            @if($orphan->country)
                <div style="width: 50%; float:right; overflow: hidden; margin-bottom: 12px;">
                    <p style=" width: 100%;margin-right:3px" class="cell font">  الدولة </p>
                    <p style="width: 90%;" class="cell border">
                        {{ $orphan->country }}
                    </p>
                </div>
            @endif

            @if($orphan->city)
                <div style="width: 50%; float:right; overflow: hidden; margin-bottom: 12px;">
                    <p style=" width: 100%;margin-right:3px" class="cell font">  المدينة </p>
                    <p style="width: 90%;" class="cell border">
                        {{ $orphan->city }}
                    </p>
                </div>
            @endif

            @if($orphan->landmark)
                <div style="width: 50%; float:right; overflow: hidden; margin-bottom: 12px;">
                    <p style=" width: 100%;margin-right:3px" class="cell font">  أقرب معلم </p>
                    <p style="width: 90%;" class="cell border">
                        {{ $orphan->landmark }}
                    </p>
                </div>
            @endif

            @if($orphan->id_number)
                <div style="width: 50%; float:right; overflow: hidden; margin-bottom: 12px;">
                    <p style=" width: 100%;margin-right:3px" class="cell font"> رقم الهوية </p>
                    <p style="width: 90%;" class="cell border">
                        {{ $orphan->id_number }}
                    </p>
                </div>
            @endif



            @if($orphan->orphan_status)
                <div style="width: 50%; float:right; overflow: hidden; margin-bottom: 12px;">
                    <p style=" width: 100%;margin-right:3px" class="cell font"> حالة اليتيم </p>
                    <p style="width: 90%;" class="cell border">
                        {{ $orphan->orphan_status }}
                    </p>
                </div>
            @endif

            @if($orphan->gender)
                <div style="width: 50%; float:right; overflow: hidden; margin-bottom: 12px;">
                    <p style=" width: 100%;margin-right:3px" class="cell font"> الجنس </p>
                    <p style="width: 90%;" class="cell border">
                        {{ $orphan->gender }}
                    </p>
                </div>
            @endif

            @if($orphan->profile && $orphan->profile->health_status)
                <div style="width: 50%; float:right; overflow: hidden; margin-bottom: 12px;">
                    <p style=" width: 100%;margin-right:3px" class="cell font"> الحالة الصحية </p>
                    <p style="width: 90%;" class="cell border">
                        {{ $orphan->profile->health_status }}
                    </p>
                </div>
            @endif

            @if($orphan->profile && $orphan->profile->educational_status)
                <div style="width: 50%; float:right; overflow: hidden; margin-bottom: 12px;">
                    <p style=" width: 100%;margin-right:3px" class="cell font"> الحالة التعليمية </p>
                    <p style="width: 90%;" class="cell border">
                        {{ $orphan->profile->educational_status }}
                    </p>
                </div>
            @endif

            @if($orphan->guardian_name)
                <div style="width: 50%; float:right; overflow: hidden; margin-bottom: 12px;">
                    <p style=" width: 100%;margin-right:3px" class="cell font"> اسم الوصي </p>
                    <p style="width: 90%;" class="cell border">
                        {{ $orphan->guardian_name }}
                    </p>
                </div>
            @endif

            @if($orphan->profile && $orphan->profile->guardian_id_number)
                <div style="width: 50%; float:right; overflow: hidden; margin-bottom: 12px;">
                    <p style=" width: 100%;margin-right:3px" class="cell font">  رقم هوية الوصي </p>
                    <p style="width: 90%;" class="cell border">
                        {{ $orphan->profile->guardian_id_number }}
                    </p>
                </div>
            @endif

            @if($orphan->profile && $orphan->profile->guardian_first_phone)
                <div style="width: 50%; float:right; overflow: hidden; margin-bottom: 12px;">
                    <p style=" width: 100%;margin-right:3px" class="cell font">  رقم هاتف الوصي </p>
                    <p style="width: 90%;" class="cell border">
                        {{ $orphan->profile->guardian_first_phone }}
                    </p>
                </div>
            @endif

            @if($orphan->profile && $orphan->profile->account_number)
                <div style="width: 50%; float:right; overflow: hidden; margin-bottom: 12px;">
                    <p style=" width: 100%;margin-right:3px" class="cell font">  رقم الحساب البنكي </p>
                    <p style="width: 90%;" class="cell border">
                        {{ $orphan->profile->account_number }}
                    </p>
                </div>
            @endif

            @if($orphan->profile && $orphan->profile->wallet_number)
                <div style="width: 50%; float:right; overflow: hidden; margin-bottom: 12px;">
                    <p style=" width: 100%;margin-right:3px" class="cell font">  رقم المحفظة </p>
                    <p style="width: 90%;" class="cell border">
                        {{ $orphan->profile->wallet_number }}
                    </p>
                </div>
            @endif


            @if($orphan->activeSponsorships && $orphan->activeSponsorships->sponsor->name)
                <div style="width: 50%; float:right; overflow: hidden; margin-bottom: 12px;">
                    <p style=" width: 100%;margin-right:3px" class="cell font"> اسم الكافل </p>
                    <p style="width: 90%;" class="cell border">
                        {{ $orphan->activeSponsorships->sponsor->name }}
                    </p>
                </div>
            @endif


           @if($orphan->activeSponsorships && $orphan->activeSponsorships->sponsorship_date)
                <div style="width: 50%; float:right; overflow: hidden; margin-bottom: 12px;">
                    <p style=" width: 100%;margin-right:3px" class="cell font"> تاريخ بدأ الكفالة </p>
                    <p style="width: 90%;" class="cell border">
                        {{ $orphan->activeSponsorships->sponsorship_date }}
                    </p>
                </div>
            @endif


            <div style="clear: both;margin-bottom:100px"></div>


        @endforeach


    </div>

</body>

</html>
