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

        {{-- الدولة  and عنوان الجهة المشرفة--}}
        @foreach ($financials as $financial)




            <div style="width: 50%; float:right; overflow: hidden; margin-bottom: 12px;">
                <p style=" width: 100%;margin-right:3px" class="cell font"> اسم اليتيم </p>
                <p style="width: 90%;" class="cell border">
                    {{ $financial->orphan}}
                </p>
            </div>

            <div style="width: 50%; float:right; overflow: hidden; margin-bottom: 12px;">
                <p style=" width: 100%;margin-right:3px" class="cell font"> اسم الكافل </p>
                <p style="width: 90%;" class="cell border">
                    {{ $financial->owner}}
                </p>
            </div>

            <div style="width: 50%; float:right; overflow: hidden; margin-bottom: 12px;">
                <p style=" width: 100%;margin-right:3px" class="cell font"> النوع  </p>
                <p style="width: 90%;" class="cell border">
                    {{ $financial->type}}
                </p>
            </div>


            @if($financial->date)
                <div style="width: 50%; float:right; overflow: hidden; margin-bottom: 12px;">
                    <p style=" width: 100%;margin-right:3px" class="cell font"> تاريخ الهدية </p>
                    <p style="width: 90%;" class="cell border">
                        {{ $financial->date}}
                    </p>
                </div>
            @endif

            <div style="width: 50%; float:right; overflow: hidden; margin-bottom: 12px;">
                <p style=" width: 100%;margin-right:3px" class="cell font"> المبلغ الشهري </p>
                <p style="width: 90%;" class="cell border">
                    {{ $financial->amount}}
                </p>
            </div>

            <div style="width: 50%; float:right; overflow: hidden; margin-bottom: 12px;">
                <p style=" width: 100%;margin-right:3px" class="cell font"> المدة  </p>
                <p style="width: 90%;" class="cell border">
                    {{ $financial->duration}}
                </p>
            </div>

            @if($financial->total)
                <div style="width: 50%; float:right; overflow: hidden; margin-bottom: 12px;">
                    <p style=" width: 100%;margin-right:3px" class="cell font">  المبلغ الاجمالي </p>
                    <p style="width: 90%;" class="cell border">
                        {{ $financial->total}}
                    </p>
                </div>
            @endif








            <div style="clear: both;margin-bottom:100px"></div>


        @endforeach


    </div>

</body>

</html>
