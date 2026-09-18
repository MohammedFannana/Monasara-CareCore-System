<x-main-layout title="تسليم الكفالات">
    <div class="header fw-bold" style="color: var(--primary-color);font-size:18px; border-top-right-radius:6px;border-top-left-radius:6px; padding:10px;background:linear-gradient(to left,#c6fdda,#edfaf1)">
        تسليم الكفالات
        <p class="fw-normal mt-2" style="font-size:15px;color:var(--text-color)">
            عرض ومتابعة حالة تسليم الكفالات.
        </p>
    </div>

    <section class="family-information mt-5">
        <div class="rounded">
            <form action="{{ route('admin.sponsorship-delivery.index') }}" method="GET" class="mb-4">
                <div class="row g-2 align-items-end">
                    <div class="col-12 col-md-3">
                        <label for="orphan" class="form-label">اسم اليتيم</label>
                        <input id="orphan" name="orphan" value="{{ request('orphan') }}" class="form-control" type="text">
                    </div>
                    <div class="col-12 col-md-3">
                        <label for="sponsor" class="form-label">اسم الكافل</label>
                        <input id="sponsor" name="sponsor" value="{{ request('sponsor') }}" class="form-control" type="text">
                    </div>
                    <div class="col-12 col-md-2">
                        <label for="delivery" class="form-label">تسليم الكفالة</label>
                        <select id="delivery" name="delivery" class="form-select">
                            <option value="">الكل</option>
                            <option value="done" @selected(request('delivery') === 'done')>مدفوع</option>
                            <option value="not done" @selected(request('delivery') === 'not done')>غير مدفوع</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-1">
                        <label for="from" class="form-label">من</label>
                        <input id="from" name="from" value="{{ $from }}" class="form-control" type="month">
                    </div>
                    <div class="col-6 col-md-1">
                        <label for="to" class="form-label">إلى</label>
                        <input id="to" name="to" value="{{ $to }}" class="form-control" type="month">
                    </div>
                    <div class="col-12 col-md-2">
                        <button type="submit" class="submit-btn w-100">بحث</button>
                    </div>
                </div>
            </form>

            <div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap">
                <p class="fs-5 fw-semibold mb-0">قائمة الكفالات ({{ $sponsorships->count() }})</p>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center">
                    <thead>
                        <tr>
                            <th>رقم معرف الطلب</th>
                            <th>اسم اليتيم</th>
                            <th>رقم هوية اليتيم</th>
                            <th>اسم الوصي</th>
                            <th>رقم هوية الوصي</th>
                            <th>اسم الكافل</th>
                            <th>تاريخ بدء الكفالة</th>
                            <th>تاريخ دفع الكفالة</th>
                            <th>المدة</th>
                            <th>المبلغ الشهري</th>
                            <th>الإجمالي</th>
                            <th>حالة الكفالة</th>
                            <th>تسليم الكفالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sponsorships as $sponsorship)
                            <tr>
                                <td>{{ $sponsorship->order_id ?? '' }}</td>
                                <td>{{ $sponsorship->orphan->name }}</td>
                                <td>{{ $sponsorship->orphan->id_number }}</td>
                                <td>{{ $sponsorship->orphan->guardian_name }}</td>
                                <td>{{ $sponsorship->orphan->profile?->guardian_id_number }}</td>
                                <td>{{ $sponsorship->sponsor->name }}</td>
                                <td>{{ $sponsorship->sponsorship_date }}</td>
                                <td>{{ $sponsorship->created_at?->format('Y-m-d H:i') }}</td>
                                <td>{{ $sponsorship->duration }}</td>
                                <td>{{ $sponsorship->bail_amount }}</td>
                                <td>{{ $sponsorship->total }}</td>
                                <td>{{ $sponsorship->status === 'active' ? 'نشطة' : 'منتهية' }}</td>
                                <td>
                                    @if ($sponsorship->sponsorship_delivery === 'done')
                                        <span class="badge bg-success">مدفوع</span>
                                    @else
                                        <span class="badge bg-secondary">غير مدفوع</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="12" class="text-center">لا توجد كفالات مطابقة للبحث.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</x-main-layout>
