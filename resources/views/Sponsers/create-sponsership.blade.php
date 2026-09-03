<x-main-layout title="1000 أمل لأبناء الشهداء">
  <section class="mt-1">
    <x-alert name="success" />
    <x-alert name="danger" />

    <x-header1 title=" إضافة كفالة " description="في هذا القسم يمكنك إضافة كفالة جديدة إلى النظام."/>

    <div class="rounded mt-3" style="border-top-color:#f0fff4 !important">
      <div class="m-4 row">
        <form action="{{ route('sponsor.orphan.store') }}" id="sponsorshipForm" method="post" enctype="multipart/form-data">
          @csrf

          @foreach($orphans as $orphan)
            <input type="hidden" name="orphan_ids[]" value="{{ $orphan->id }}">
          @endforeach

          <p class="fw-bold mb-4" style="font-size: 18px;">
            أنت على وشك إضافة كفالة لعدد {{ $orphans_count }} يتيم/يتيمة.
          </p>

          <div class="col-12 mb-4">
            <x-form.input name="duration" type="number" id="duration"
              label="مدة الكفالة (بالأشهر)"
              placeholder="ادخل مدة الكفالة" min="1"/>
          </div>

          <div class="col-12 mb-4">
            <x-form.input id="amountInput" name="bail_amount" type="number"
              label="(بالشهر) مبلغ الكفالة"
              placeholder="ادخل مبلغ الكفالة" value="1" />
          </div>

          <div class="col-12 mb-4" id="notes-wrapper" style="display: none;">
            <x-form.textarea label="ملاحظات إضافية" name="notes" placeholder="ادخل ملاحظات إضافية (اختياري)"/>
          </div>

          <div class="col-12 row mb-4 d-flex align-items-center justify-content-between">
            <div class="col-12 col-sm-9">
              <x-form.select name="type" id="type-select" label=" نوع الكفالة" :options="[
                'sponsorship' => 'كفالة',
                'gift' => 'هدية'
              ]"/>
            </div>

            <button type="button"
              id="pay-now-btn"
              class="submit-btn col-12 col-sm-4 col-md-2 mt-4 fw-semibold"
              data-bs-toggle="modal"
              data-bs-target="#paymentModal"
              style="margin-left:10px; color:#1e9448; background-color: #cbfddd"
              disabled>
              ادفع الآن
            </button>
          </div>

          <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="paymentModalLabel">تأكيد الدفع</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                </div>
                <div class="modal-body">
                  <label class="fw-semibold">إجمالي المبلغ:</label>
                  <input type="text" id="modalAmount" class="form-control" readonly />
                  <label class="mt-2">العملة</label>
                  <input type="text" value="الدينار البحريني" class="form-control" disabled />
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                  <button type="button" class="btn btn-success" onclick="startPayment(event)">تأكيد الدفع</button>
                </div>
              </div>
            </div>
          </div>

          {{-- <div class="col-12 col-md-6 mb-4">

                        <label class="mb-2 fw-bold">إيصال الدفع</label> <br>

                        <label for="payment_received" class="custom-file-upload text-center" style="width: 90%;color:#777a78;">

                            <img src="{{asset('images/file.png')}}" alt="" width="50px" height="50px"> <br>

                            اسحب الملف هنا أو اضغط لاختياره

                        </label>

                        <x-form.input name="payment_received" class="hidden-file-style" type="file" id="payment_received" style="display: none;"/>

                    </div>



                    <div class="d-flex justify-content-center">

                        <button type="submit" class="submit-btn w-25">اكفل الآن</button>

                    </div> --}}

        </form>
      </div>
    </div>
  </section>

@push('scripts')
<script src="https://eazypay.gateway.mastercard.com/static/checkout/checkout.min.js"></script>

<script>
// --- أولاً: منطق واجهة المستخدم (حساب المبالغ وتفعيل الزر) ---
document.addEventListener("DOMContentLoaded", () => {
  const typeSelect    = document.getElementById("type-select");
  const notesWrapper  = document.getElementById("notes-wrapper");
  const amountInput   = document.getElementById("amountInput");
  const durationInput = document.getElementById("duration");
  const payBtn        = document.getElementById("pay-now-btn");
  const modalAmount   = document.getElementById("modalAmount");
  const paymentModal  = document.getElementById("paymentModal");

  function calculateTotal() {
    const amount = Number(amountInput?.value) || 0;
    const duration = Number(durationInput?.value) || 0;
    const orphanCount = document.querySelectorAll("input[name='orphan_ids[]']").length || 1;
    const type = typeSelect?.value || "sponsorship";

    let total = 0;
    if (type === "gift") total = amount * orphanCount;
    else total = amount * duration * orphanCount;

    if (modalAmount) {
      modalAmount.value = total > 0 ? total.toFixed(2) : "0.00";
    }

    // تفعيل أو تعطيل الزر بناءً على المجموع
    if (payBtn) payBtn.disabled = total <= 0;

    return total;
  }

  typeSelect?.addEventListener("change", () => {
    notesWrapper.style.display = (typeSelect.value === "gift") ? "block" : "none";
    calculateTotal();
  });

  amountInput?.addEventListener("input", calculateTotal);
  durationInput?.addEventListener("input", calculateTotal);
  paymentModal?.addEventListener("show.bs.modal", calculateTotal);

  calculateTotal(); // تشغيل أولي
});

// --- ثانياً: منطق عملية الدفع ---
let paymentInProgress = false;

// async function startPayment(event) {
//   if (event) event.preventDefault();
//   if (paymentInProgress) return;

//   try {
//     paymentInProgress = true;

//     // جلب البيانات
//     const orphanIds = Array.from(document.querySelectorAll("input[name='orphan_ids[]']")).map(i => i.value);
//     const duration  = Number(document.getElementById("duration")?.value);
//     const bailAmount= Number(document.getElementById("amountInput")?.value);
//     const type      = document.getElementById("type-select")?.value;
//     const notes     = document.querySelector("textarea[name='notes']")?.value;

//     // 1. التخزين المؤقت
//     const tempRes = await fetch("{{ route('payment.temp.store') }}", {
//       method: "POST",
//       headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
//       body: JSON.stringify({ orphan_ids: orphanIds, duration, bail_amount: bailAmount, type, notes })
//     });
//     const tempData = await tempRes.json();
//     if (!tempData.ok) throw new Error(tempData.message || "فشل حفظ البيانات");

//     // 2. إنشاء الجلسة
//     const sessRes = await fetch("{{ route('payment.session') }}", {
//       method: "POST",
//       headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
//       body: JSON.stringify({ orderId: tempData.orderId })
//     });
//     const sData = await sessRes.json();
//     if (!sData.ok) throw new Error(sData.message || "فشل إنشاء الجلسة");

//     // 3. إعداد المكتبة
//     // Checkout.configure({
//     //   session: { id: sData.sessionId },
//     //   interaction: {
//     //     merchant: { name: 'Palestine Charity' },
//     //     returnUrl: "{{ route('payment.response') }}?orderId=" + tempData.orderId
//     //   }
//     // });

//     // 3. إعداد المكتبة
//     Checkout.configure({
//       session: {
//         id: sData.session.id // المسار الصحيح حسب الـ Controller الخاص بك
//       },
//       interaction: {
//         merchant: { name: 'Palestine Charity' },
//         returnUrl: "{{ route('payment.response') }}?orderId=" + tempData.orderId
//       }
//     });

//     // 4. إغلاق المودال والتحويل لصفحة الدفع
//     const modalEl = document.getElementById("paymentModal");
//     const modalInstance = bootstrap.Modal.getInstance(modalEl);

//     if (modalInstance) {
//         // ننتظر حتى يختفي المودال تماماً لتجنب تعليق الصفحة (Reload)
//         modalEl.addEventListener('hidden.bs.modal', () => {
//             // إذا السيرفر أرسل رابط تحويل، نستخدمه فوراً لأنه الأضمن
//             if (sData.redirectUrl) {
//                 window.location.href = sData.redirectUrl;
//             } else {
//                 Checkout.showPaymentPage();
//             }
//         }, { once: true });
//         modalInstance.hide();
//     } else {
//         // في حال لم يكن المودال مفتوحاً لأي سبب
//         if (sData.redirectUrl) {
//             window.location.href = sData.redirectUrl;
//         } else {
//             Checkout.showPaymentPage();
//         }
//     }
// }

async function startPayment(event) {
    if (event) event.preventDefault();
    if (paymentInProgress) return;

    try {
        paymentInProgress = true;

        // جمع البيانات
        const orphanIds = Array.from(document.querySelectorAll("input[name='orphan_ids[]']")).map(i => i.value);
        const duration  = document.getElementById("duration")?.value;
        const bailAmount= document.getElementById("amountInput")?.value;
        const type      = document.getElementById("type-select")?.value;
        const notes     = document.querySelector("textarea[name='notes']")?.value;

        // 1. حفظ البيانات المؤقتة
        const tempRes = await fetch("{{ route('payment.temp.store') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                orphan_ids: orphanIds,
                duration,
                bail_amount: bailAmount,
                type,
                notes
            })
        });

        const tempData = await tempRes.json();
        if (!tempData.ok) throw new Error(tempData.message || "فشل حفظ البيانات المؤقتة");

        // 2. إنشاء جلسة الدفع
        const sessRes = await fetch("{{ route('payment.session') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ orderId: tempData.orderId })
        });

        const sData = await sessRes.json();

        if (!sData.ok) {
            throw new Error(sData.message || "فشل إعداد جلسة الدفع: " + (sData.gateway?.error?.explanation || ''));
        }

        // 3. تكوين مكتبة Mastercard
        Checkout.configure({
            session: {
                id: sData.session.id
            },
            // إذا كان هناك redirectUrl، أضفه هنا
            ...(sData.redirectUrl && {
                interaction: {
                    merchant: {
                        name: 'Palestine Charity'
                    }
                }
            })
        });

        // 4. التعامل مع المودال
        const modalEl = document.getElementById("paymentModal");
        const modalInstance = bootstrap.Modal.getInstance(modalEl);

        if (modalInstance) {
            modalEl.addEventListener('hidden.bs.modal', () => {
                // إذا كان هناك redirectUrl، استخدمه مباشرة
                if (sData.redirectUrl) {
                    window.location.href = sData.redirectUrl;
                } else {
                    // وإلا استخدم المكتبة
                    Checkout.showPaymentPage();
                }
            }, { once: true });
            modalInstance.hide();
        } else {
            if (sData.redirectUrl) {
                window.location.href = sData.redirectUrl;
            } else {
                Checkout.showPaymentPage();
            }
        }

    } catch (err) {
        console.error("Payment Error:", err);

        // عرض رسالة الخطأ للمستخدم
        let errorMsg = err.message;
        if (err.message.includes('Unexpected parameter')) {
            errorMsg = 'خطأ في إعدادات بوابة الدفع. يرجى التواصل مع الدعم الفني.';
        }

        alert("❌ " + errorMsg);

        // إعادة تفعيل الزر
        const payBtn = document.getElementById("pay-now-btn");
        if (payBtn) payBtn.disabled = false;

    } finally {
        paymentInProgress = false;
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.getElementById('type-select');
    const amountInput = document.getElementById('amountInput');

    function handleTypeChange() {
        if (typeSelect.value === 'sponsorship') {
            amountInput.value = 20;       // تثبيت القيمة على 20
            amountInput.min = 20;

        } else {
             amountInput.value = 1;
              amountInput.min = 1;

        }
    }

    // تشغيل الدالة فور تحميل الصفحة لضبط الحالة الابتدائية
    handleTypeChange();

    // تشغيل الدالة عند تغيير خيار السيلكت
    typeSelect.addEventListener('change', handleTypeChange);
});
</script>
@endpush
</x-main-layout>
