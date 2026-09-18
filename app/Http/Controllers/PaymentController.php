<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Http\Requests\PendingSponsorshipValidatedRequest;
use App\Models\Gift;
use App\Models\Orphan;
use App\Models\Sponsorship;
use App\Models\PendingSponsorship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class PaymentController extends Controller
{
    /**
     * 1) حفظ بيانات الكفالة مؤقتًا
     */
    public function tempStore(PendingSponsorshipValidatedRequest $request)
    {
        $orderId = "ORD" . rand(100000, 999999);

        try {
            $data = $request->validated();

            $pending = PendingSponsorship::create([
                'order_id'    => $orderId,
                'duration'    => (int)$data['duration'],
                'bail_amount' => (float)$data['bail_amount'],
                'type'        => $data['type'],
                'notes'       => $data['notes'] ?? null,
                // success_indicator سيتم تحديثه لاحقًا إذا رجع من MPGS
                'success_indicator' => null,
            ]);

            $pending->orphans()->attach($data['orphan_ids']);

            return response()->json([
                'ok' => true,
                'orderId' => $orderId,
            ]);
        } catch (\Throwable $e) {
            Log::error("TEMP STORE ERROR: " . $e->getMessage());

            return response()->json([
                'ok' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * 2) Create Session + Initiate Checkout
     * ✅ Tenant عندك لا يقبل interaction.* في INITIATE_CHECKOUT
     * ✅ نرجع redirectUrl للـ JS ونحول عليه
     */
//     public function createSession(Request $request)
// {
//     $orderId = $request->input('orderId');

//     // 1) جلب pending
//     $pending = DB::table('pending_sponsorships')->where('order_id', $orderId)->first();
//     if (!$pending) {
//         return response()->json([
//             'ok' => false,
//             'message' => 'Pending order not found'
//         ], 404);
//     }

//     // 2) عدّ الأيتام
//     $orphansCount = DB::table('pending_sponsorship_orphan')
//         ->where('pending_sponsorship_id', $pending->id)
//         ->count();
//     if ($orphansCount <= 0) $orphansCount = 1;

//     // 3) احسب الإجمالي
//     if ($pending->type === 'gift') {
//         $total = (float)$pending->bail_amount * (int)$orphansCount;
//     } else {
//         $total = (float)$pending->bail_amount * (int)$pending->duration * (int)$orphansCount;
//     }
//     $amount = number_format($total, 2, '.', '');

//     // 4) إعدادات التاجر
//     $merchantId  = env('MASTERCARD_MERCHANT_ID');
//     $apiPassword = env('MASTERCARD_API_PASSWORD');
//     $apiUsername = "merchant." . $merchantId;
//     $baseUrl     = rtrim(env('MASTERCARD_BASE_URL', 'https://eazypay.gateway.mastercard.com'), '/');

//     // 5) CREATE_CHECKOUT_SESSION (payload minimal لأنه tenant يرفض order.id/currency)
//     $createUrl = "{$baseUrl}/api/rest/version/100/merchant/{$merchantId}/session";
//     $createPayload = [
//         "apiOperation" => "CREATE_CHECKOUT_SESSION"
//     ];

//     $createRes = Http::withBasicAuth($apiUsername, $apiPassword)
//         ->withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
//         ->post($createUrl, $createPayload);

//     Log::info("MPGS CREATE status={$createRes->status()} body={$createRes->body()}");

//     if (!$createRes->successful()) {
//         return response()->json([
//             'ok' => false,
//             'message' => 'CREATE_CHECKOUT_SESSION failed',
//             'gateway' => $createRes->json()
//         ], 400);
//     }

//     $createData = $createRes->json();
//     $sessionId = $createData['session']['id'] ?? null;

//     if (!$sessionId) {
//         return response()->json([
//             'ok' => false,
//             'message' => 'Missing session.id',
//             'gateway' => $createData
//         ], 400);
//     }

//     // 6) UPDATE_SESSION (إضافة amount فقط - حسب اللوج اللي عندك هذا ينجح)
//     $updateUrl = "{$baseUrl}/api/rest/version/100/merchant/{$merchantId}/session/{$sessionId}";
//     $updatePayload = [
//         "apiOperation" => "UPDATE_SESSION",
//         "order" => [
//             "amount" => $amount
//         ]
//     ];

//     $updateRes = Http::withBasicAuth($apiUsername, $apiPassword)
//         ->withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
//         ->put($updateUrl, $updatePayload);

//     Log::info("MPGS UPDATE status={$updateRes->status()} body={$updateRes->body()}");

//     if (!$updateRes->successful()) {
//         return response()->json([
//             'ok' => false,
//             'message' => 'UPDATE_SESSION failed',
//             'gateway' => $updateRes->json()
//         ], 400);
//     }

//     // ✅ 7) INITIATE_CHECKOUT (هنا نضيف interaction.merchant المطلوب)
//     // endpoint الصحيح لبوابتك (حسب اللوج عندك INITIATE بيتم على نفس session endpoint بالـ PUT)
//     // $initiatePayload = [
//     //     "apiOperation" => "INITIATE_CHECKOUT",
//     //     "order" => [
//     //         "id" => $orderId,
//     //         "amount" => $amount,
//     //         "currency" => "BHD",
//     //         "description" => "Orphan Sponsorship"
//     //     ],
//     //     "interaction" => [
//     //         "merchant" => [
//     //             "name" => env('MPGS_MERCHANT_NAME', 'Your Merchant Name')
//     //         ]
//     //     ]
//     //     // ❌ لا تضع returnUrl/cancelUrl لأن tenant عندك بيرفضها
//     // ];

//  // 1. تعريف الرابط بإصدار 60 (يتجاوز قيود الـ interaction في الغالب)
// $initiateUrl = "{$baseUrl}/api/rest/version/60/merchant/{$merchantId}/session/{$sessionId}";

// $initiatePayload = [
//     "apiOperation" => "INITIATE_CHECKOUT",
//     "order" => [
//         "id"       => $orderId,
//         "amount"   => $amount,
//         "currency" => "BHD"
//     ]
// ];
// // تأكد من عدم وجود مصفوفة interaction هنا نهائياً

// $initiateRes = Http::withBasicAuth($apiUsername, $apiPassword)->put($initiateUrl, $initiatePayload);

// if ($initiateRes->successful()) {
//     $initiateData = $initiateRes->json();
//     // استخراج رابط التوجيه المباشر إذا توفر
//     $redirectUrl = data_get($initiateData, 'redirectUrl');

//     return response()->json([
//         'ok' => true,
//         'orderId' => $orderId,
//         'session' => ['id' => $sessionId],
//         'redirectUrl' => $redirectUrl // هذا هو مفتاح الحل
//     ]);
// }



//     $initiateRes = Http::withBasicAuth($apiUsername, $apiPassword)
//         ->withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
//         ->put($updateUrl, $initiatePayload);

//     Log::info("MPGS INITIATE status={$initiateRes->status()} body={$initiateRes->body()} payload=" . json_encode($initiatePayload));

//     if (!$initiateRes->successful()) {
//         return response()->json([
//             'ok' => false,
//             'message' => 'INITIATE_CHECKOUT failed',
//             'gateway' => $initiateRes->json()
//         ], 400);
//     }

//     return response()->json([
//         'ok' => true,
//         'orderId' => $orderId,
//         'amount' => $amount,
//         'session' => [
//             'id' => $sessionId
//         ]
//     ]);
// }

public function createSession(Request $request)
{
    $orderId = $request->input('orderId');

    // 1) جلب البيانات المؤقتة
    $pending = DB::table('pending_sponsorships')->where('order_id', $orderId)->first();
    if (!$pending) {
        return response()->json(['ok' => false, 'message' => 'Order not found'], 404);
    }

    // 2) حساب المبلغ
    $orphansCount = DB::table('pending_sponsorship_orphan')
        ->where('pending_sponsorship_id', $pending->id)
        ->count() ?: 1;

    $total = ($pending->type === 'gift')
        ? (float)$pending->bail_amount * $orphansCount
        : (float)$pending->bail_amount * (int)$pending->duration * $orphansCount;

    $amount = number_format($total, 2, '.', '');

    // 3) إعدادات الاتصال
    $merchantId  = env('MASTERCARD_MERCHANT_ID');
    $apiPassword = env('MASTERCARD_API_PASSWORD');
    $apiUsername = "merchant." . $merchantId;
    $baseUrl     = rtrim(env('MASTERCARD_BASE_URL'), '/');

    Log::info("Starting MPGS payment for order: {$orderId}, amount: {$amount} BHD");

    // 4) الطريقة الأساسية: CREATE_CHECKOUT_SESSION مع operation
    $payload = [
        "apiOperation" => "CREATE_CHECKOUT_SESSION",
        "order" => [
            "id"          => $orderId,
            "amount"      => $amount,
            "currency"    => "BHD",
            "description" => "Orphan Sponsorship"
        ],
        "interaction" => [
            "operation" => "PURCHASE", // هذه المعلمة مطلوبة
            "merchant" => [
                "name" => "Palestine Charity"
            ],
            "returnUrl" => route('payment.response') . "?orderId=" . $orderId
        ]
    ];

    Log::info("MPGS CREATE_CHECKOUT_SESSION Payload: " . json_encode($payload));

    // جرب أولاً مع إصدار 67
    $response = Http::withBasicAuth($apiUsername, $apiPassword)
        ->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])
        ->post("{$baseUrl}/api/rest/version/67/merchant/{$merchantId}/session", $payload);

    Log::info("MPGS Response Status: " . $response->status());
    Log::info("MPGS Response Body: " . $response->body());

    if ($response->successful()) {
        $data = $response->json();

        $sessionId = $data['session']['id'] ?? null;
        $successIndicator = $data['successIndicator'] ?? null;
        $redirectUrl = $data['redirectUrl'] ?? "{$baseUrl}/checkout/pay/{$sessionId}";

        if (!$sessionId) {
            return response()->json([
                'ok' => false,
                'message' => 'Missing session ID in response',
                'gateway' => $data
            ], 400);
        }

        // حفظ success indicator
        if ($successIndicator) {
            DB::table('pending_sponsorships')
                ->where('order_id', $orderId)
                ->update(['success_indicator' => $successIndicator]);
        }

        return response()->json([
            'ok' => true,
            'orderId' => $orderId,
            'session' => ['id' => $sessionId],
            'redirectUrl' => $redirectUrl,
            'successIndicator' => $successIndicator
        ]);
    }

    // 5) إذا فشلت الطريقة الأولى، جرب مع إصدارات أخرى من API
    $apiVersions = [60, 61, 62, 63, 64, 65, 66, 68, 69, 70, 75, 100];

    foreach ($apiVersions as $version) {
        Log::info("Trying API version {$version}...");

        $response = Http::withBasicAuth($apiUsername, $apiPassword)
            ->post("{$baseUrl}/api/rest/version/{$version}/merchant/{$merchantId}/session", $payload);

        Log::info("MPGS Version {$version} Response: " . $response->body());

        if ($response->successful()) {
            $data = $response->json();
            $sessionId = $data['session']['id'] ?? null;
            $successIndicator = $data['successIndicator'] ?? null;

            if ($successIndicator) {
                DB::table('pending_sponsorships')
                    ->where('order_id', $orderId)
                    ->update(['success_indicator' => $successIndicator]);
            }

            return response()->json([
                'ok' => true,
                'orderId' => $orderId,
                'session' => ['id' => $sessionId],
                'apiVersionUsed' => $version
            ]);
        }

        // انتظر قليلاً قبل المحاولة التالية
        usleep(100000); // 100ms
    }

    // 6) جرب التركيب البديل (operation خارج interaction)
    Log::info("Trying alternative structure with operation at root level...");

    $alternativePayload = [
        "apiOperation" => "CREATE_CHECKOUT_SESSION",
        "operation" => "PURCHASE", // operation في المستوى العلوي
        "order" => [
            "id"          => $orderId,
            "amount"      => $amount,
            "currency"    => "BHD"
        ],
        "interaction" => [
            "merchant" => [
                "name" => "Palestine Charity"
            ],
            "returnUrl" => route('payment.response') . "?orderId=" . $orderId
        ]
    ];

    $altResponse = Http::withBasicAuth($apiUsername, $apiPassword)
        ->post("{$baseUrl}/api/rest/version/60/merchant/{$merchantId}/session", $alternativePayload);

    Log::info("Alternative structure response: " . $altResponse->body());

    if ($altResponse->successful()) {
        $data = $altResponse->json();
        $sessionId = $data['session']['id'] ?? null;
        $successIndicator = $data['successIndicator'] ?? null;

        if ($successIndicator) {
            DB::table('pending_sponsorships')
                ->where('order_id', $orderId)
                ->update(['success_indicator' => $successIndicator]);
        }

        return response()->json([
            'ok' => true,
            'orderId' => $orderId,
            'session' => ['id' => $sessionId],
            'note' => 'Used alternative structure'
        ]);
    }

    // 7) جرب التركيب المبسط جداً
    Log::info("Trying minimal structure...");

    $minimalPayload = [
        "apiOperation" => "CREATE_CHECKOUT_SESSION",
        "order" => [
            "id"       => $orderId,
            "amount"   => $amount,
            "currency" => "BHD"
        ],
        "interaction" => [
            "operation" => "PURCHASE", // فقط operation بدون merchant
            "returnUrl" => route('payment.response') . "?orderId=" . $orderId
        ]
    ];

    $minimalResponse = Http::withBasicAuth($apiUsername, $apiPassword)
        ->post("{$baseUrl}/api/rest/version/67/merchant/{$merchantId}/session", $minimalPayload);

    Log::info("Minimal structure response: " . $minimalResponse->body());

    if ($minimalResponse->successful()) {
        $data = $minimalResponse->json();
        $sessionId = $data['session']['id'] ?? null;
        $successIndicator = $data['successIndicator'] ?? null;

        if ($successIndicator) {
            DB::table('pending_sponsorships')
                ->where('order_id', $orderId)
                ->update(['success_indicator' => $successIndicator]);
        }

        return response()->json([
            'ok' => true,
            'orderId' => $orderId,
            'session' => ['id' => $sessionId],
            'note' => 'Used minimal structure'
        ]);
    }

    // 8) جرب مع INITIATE_CHECKOUT كعملية منفصلة
    Log::info("Trying 3-step approach: CREATE → UPDATE → INITIATE...");

    // Step 1: CREATE_CHECKOUT_SESSION
    $createResponse = Http::withBasicAuth($apiUsername, $apiPassword)
        ->post("{$baseUrl}/api/rest/version/67/merchant/{$merchantId}/session", [
            "apiOperation" => "CREATE_CHECKOUT_SESSION"
        ]);

    if ($createResponse->successful()) {
        $createData = $createResponse->json();
        $sessionId = $createData['session']['id'] ?? null;
        $successIndicator = $createData['successIndicator'] ?? null;

        if (!$sessionId) {
            return response()->json(['ok' => false, 'message' => 'No session ID'], 400);
        }

        // Step 2: UPDATE_SESSION
        $updateResponse = Http::withBasicAuth($apiUsername, $apiPassword)
            ->put("{$baseUrl}/api/rest/version/67/merchant/{$merchantId}/session/{$sessionId}", [
                "apiOperation" => "UPDATE_SESSION",
                "order" => [
                    "id"       => $orderId,
                    "amount"   => $amount,
                    "currency" => "BHD"
                ]
            ]);

        // Step 3: INITIATE_CHECKOUT (هذه قد تنجح حيث فشلت السابقة)
        $initiateResponse = Http::withBasicAuth($apiUsername, $apiPassword)
            ->put("{$baseUrl}/api/rest/version/60/merchant/{$merchantId}/session/{$sessionId}", [
                "apiOperation" => "INITIATE_CHECKOUT",
                "operation" => "PURCHASE",
                "interaction" => [
                    "returnUrl" => route('payment.response') . "?orderId=" . $orderId
                ]
            ]);

        Log::info("3-step INITIATE response: " . $initiateResponse->body());

        if ($initiateResponse->successful()) {
            if ($successIndicator) {
                DB::table('pending_sponsorships')
                    ->where('order_id', $orderId)
                    ->update(['success_indicator' => $successIndicator]);
            }

            return response()->json([
                'ok' => true,
                'orderId' => $orderId,
                'session' => ['id' => $sessionId],
                'note' => 'Used 3-step approach'
            ]);
        }
    }

    // 9) الفشل النهائي - رسالة واضحة للمستخدم
    $errorMsg = "تعذر الاتصال ببوابة الدفع. يرجى:";
    $errorMsg .= "\n1. التحقق من إعدادات التاجر في Mastercard Merchant Administration";
    $errorMsg .= "\n2. التأكد من أن عملية 'PURCHASE' مسموحة";
    $errorMsg .= "\n3. التواصل مع الدعم الفني للبوابة";

    return response()->json([
        'ok' => false,
        'message' => $errorMsg,
        'gateway' => $response->json() ?: ['error' => 'Unknown error']
    ], 400);
}

    /**
     * 3) صفحة الاستجابة بعد الدفع
     * ✅ يعتمد على resultIndicator vs success_indicator
     * ✅ إذا ما تماثل، نخليها pending (لا تظهر فشل) + نقدر نعمل inquiry إذا بدك
     */
    public function paymentResponse(Request $request)
    {
        $orderId         = $request->query('orderId');
        $statusParam     = $request->query('status');
        $resultIndicator = $request->query('resultIndicator');

        $successStored = DB::table('pending_sponsorships')
            ->where('order_id', $orderId)
            ->value('success_indicator');

        $status = 'failed';

        if ($statusParam === 'cancel') {
            $status = 'cancelled';
        } elseif ($resultIndicator && $successStored && $resultIndicator === $successStored) {
            $status = 'success';
        } else {
            // لا نعرض "فشل" مباشرة
            $status = 'pending';
        }

        $pending = DB::table('pending_sponsorships')->where('order_id', $orderId)->first();

        if ($status === 'success') {

            if (!$pending) {
                $message = "❌ البيانات المؤقتة للكفالة غير موجودة في النظام. لم يتم تسجيل الكفالة.";
                Log::error($message);
                return view('Sponsers.payment-response', compact('status', 'message', 'orderId'));
            }

            $orphanIds = DB::table('pending_sponsorship_orphan')
                ->where('pending_sponsorship_id', $pending->id)
                ->pluck('orphan_id')
                ->toArray();

            if (empty($orphanIds)) {
                $message = "❌ لم يتم العثور على أي أيتام مرتبطين بالكفالة المؤقتة.";
                Log::error($message);
                return view('Sponsers.payment-response', compact('status', 'message', 'orderId'));
            }

            DB::beginTransaction();

            try {
                $sponsorId = auth('sponsor')->id();

                foreach ($orphanIds as $oid) {
                    $orphan = Orphan::findOrFail($oid);

                    if ($pending->type === 'sponsorship') {

                        $validated = [
                            'order_id'    => $pending->order_id,
                            'orphan_id'   => $orphan->id,
                            'sponsor_id'  => $sponsorId,
                            'duration'    => (int)$pending->duration,
                            'bail_amount' => (float)$pending->bail_amount,
                            'status'      => 'active',
                            'currency'    => 'BHD',
                            'total'       => (int)$pending->duration * (float)$pending->bail_amount,
                        ];

                        $last = Sponsorship::where('orphan_id', $orphan->id)
                            ->orderByDesc('sponsorship_date')
                            ->first();

                        $validated['sponsorship_date'] = $last
                            ? Carbon::parse($last->sponsorship_date)->addMonths((int)$last->duration)->format('Y-m-d')
                            : now()->format('Y-m-d');

                        Sponsorship::create($validated);
                        $orphan->update(['role' => \App\Enums\OrphanRole::SPONSORED->value]);

                    } else {
                        Gift::create([
                            'order_id'   => $pending->order_id,
                            'orphan_id'  => $orphan->id,
                            'sponsor_id' => $sponsorId,
                            'amount'     => (float)$pending->bail_amount,
                            'duration'   => (int)$pending->duration,
                            'total'      => (int)$pending->duration * (float)$pending->bail_amount,
                            'currency'   => 'BHD',
                            'gift_date'  => now(),
                            'notes'      => $pending->notes,
                        ]);
                    }
                }

                DB::commit();

                DB::table('pending_sponsorship_orphan')->where('pending_sponsorship_id', $pending->id)->delete();
                DB::table('pending_sponsorships')->where('id', $pending->id)->delete();

                $sponsorName  = auth('sponsor')->user()->name ?? 'كافل مجهول';
                $orphansNames = Orphan::whereIn('id', $orphanIds)->pluck('name')->toArray();
                $orphansList  = implode('، ', $orphansNames);
                $isGift       = $pending->type === 'gift';

                $duration = (int)$pending->duration;
                $amount   = (float)$pending->bail_amount;
                $count    = count($orphanIds);

                $grandTotal = $isGift ? ($amount * $count) : ($duration * $amount * $count);

                if ($isGift) {
                    $message = "
                        تم الدفع بنجاح وتم تسجيل <strong>هدية</strong>.<br>
                        المتبرع: <strong>{$sponsorName}</strong><br>
                        الأيتام: <strong>{$orphansList}</strong><br>
                        قيمة الهدية لليتيم الواحد: {$amount} دينار بحريني<br>
                        عدد الأيتام: {$count}
                    ";
                } else {
                    $message = "
                        تم الدفع بنجاح وتم تسجيل <strong>كفالة</strong>.<br>
                        الكافل: <strong>{$sponsorName}</strong><br>
                        الأيتام: <strong>{$orphansList}</strong><br>
                        مدة الكفالة: {$duration} شهر<br>
                        المبلغ الشهري لليتيم الواحد: {$amount} دينار بحريني<br>
                        عدد الأيتام: {$count}<br>
                        المجموع الكلي: <strong>{$grandTotal}</strong> دينار بحريني
                    ";
                }

            } catch (\Throwable $e) {
                DB::rollBack();
                $message = "❌ حدث خطأ أثناء تسجيل الكفالة: " . $e->getMessage();
                Log::error($message);
            }

            return view('Sponsers.payment-response', compact('status', 'message', 'orderId'));
        }

        if ($status === 'cancelled') {
            $message = 'تم إلغاء العملية من قبل المستخدم';
        } else {
            $status  = 'pending';
            $message = 'العملية قيد المعالجة، سيتم تأكيد الدفع خلال لحظات.';
        }

        return view('Sponsers.payment-response', compact('status', 'message', 'orderId'));
    }
}
