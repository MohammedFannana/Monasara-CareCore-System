<x-main-layout title="تسليم الكفالات">
    <div class="header fw-bold" style="color: var(--primary-color);font-size:18px; border-top-right-radius:6px;border-top-left-radius:6px; padding:10px;background:linear-gradient(to left,#c6fdda,#edfaf1)">
        تسليم الكفالات
        <p class="fw-normal mt-2" style="font-size:15px;color:var(--text-color)">
            ارفع ملف الكفالات المدفوعة ليتم تحديث الحالات عند التطابق الكامل.
        </p>
    </div>

    <x-alert name="success" class="mt-2" />
    <x-alert name="danger" class="mt-2" />

    @if (session('delivery_import'))
        @php($result = session('delivery_import'))
        <div class="alert alert-info mt-2">
            تم تحديث {{ $result['updated'] }} كفالة، وموجودة مسبقاً كـ تم التسليم: {{ $result['already_done'] }}،
            غير مطابقة: {{ $result['unmatched'] }}، ومطابقة مكررة: {{ $result['ambiguous'] }}.
        </div>
    @endif

    <section class="family-information mt-5">
        <div class="rounded">
            <form action="{{ route('association.sponsorship-delivery.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="file" class="form-label">ملف الكفالات المدفوعة</label>
                    <input id="file" name="file" type="file" class="form-control" accept=".xlsx,.xls,.csv" required>
                    @error('file') <div class="text-danger mt-2">{{ $message }}</div> @enderror
                </div>
                <button type="submit" class="submit-btn">رفع وتحديث الكفالات</button>
            </form>
        </div>
    </section>

    <section class="family-information mt-5">
        <div class="rounded">
            <form action="{{ route('association.sponsorship-delivery.create') }}" method="GET" class="mb-4">
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

            <form action="{{ route('association.sponsorship-delivery.mark-as-delivered') }}" method="POST" id="deliveryForm">
                @csrf
                <div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap">
                    <p class="fs-5 fw-semibold mb-0">قائمة الكفالات ({{ $sponsorships->total() }})</p>
                    <div class="d-flex align-items-center gap-2">
                        <label class="d-flex align-items-center gap-1 mb-0">
                            <input type="checkbox" id="checkAll">
                            تحديد الكل
                        </label>
                        <button type="submit" class="submit-btn">دفع الكفالة</button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered align-middle text-center">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="checkAllHeader"></th>
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
                                    <td><input class="delivery-checkbox" type="checkbox" value="{{ $sponsorship->id }}" @disabled($sponsorship->sponsorship_delivery === 'done')></td>
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
                                <tr><td colspan="14" class="text-center">لا توجد كفالات مطابقة للبحث.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $sponsorships->links() }}
                </div>
            </form>
        </div>
    </section>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const deliveryForm = document.getElementById('deliveryForm');
                const checkboxes = Array.from(document.querySelectorAll('.delivery-checkbox'));
                const selectionUrl = new URL(window.location.href);
                selectionUrl.searchParams.delete('page');
                const selectionKey = 'sponsorship-delivery:' + selectionUrl.pathname + selectionUrl.search;
                const selectedIds = new Set(JSON.parse(sessionStorage.getItem(selectionKey) || '[]'));
                const hiddenInputs = document.createElement('div');
                deliveryForm.appendChild(hiddenInputs);

                const syncHiddenInputs = () => {
                    hiddenInputs.innerHTML = '';
                    selectedIds.forEach((id) => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'sponsorship_ids[]';
                        input.value = id;
                        hiddenInputs.appendChild(input);
                    });
                    sessionStorage.setItem(selectionKey, JSON.stringify([...selectedIds]));
                };

                const syncVisibleCheckboxes = () => checkboxes.forEach((checkbox) => {
                    checkbox.checked = selectedIds.has(checkbox.value);
                });

                const syncSelectAll = () => {
                    const available = checkboxes.filter((checkbox) => !checkbox.disabled);
                    const allSelected = available.length > 0 && available.every((checkbox) => selectedIds.has(checkbox.value));
                    document.querySelectorAll('#checkAll, #checkAllHeader').forEach((control) => control.checked = allSelected);
                };

                checkboxes.forEach((checkbox) => checkbox.addEventListener('change', function () {
                    this.checked ? selectedIds.add(this.value) : selectedIds.delete(this.value);
                    syncHiddenInputs();
                    syncSelectAll();
                }));

                document.querySelectorAll('#checkAll, #checkAllHeader').forEach((control) => {
                    control.addEventListener('change', function () {
                        checkboxes.forEach((checkbox) => {
                            if (checkbox.disabled) return;
                            this.checked ? selectedIds.add(checkbox.value) : selectedIds.delete(checkbox.value);
                        });
                        syncHiddenInputs();
                        document.querySelectorAll('#checkAll, #checkAllHeader').forEach((other) => other.checked = this.checked);
                    });
                });

                deliveryForm.addEventListener('submit', function (event) {
                    syncHiddenInputs();
                    if (selectedIds.size === 0) {
                        event.preventDefault();
                        alert('يرجى اختيار كفالة واحدة على الأقل.');
                        return;
                    }
                    sessionStorage.removeItem(selectionKey);
                });

                syncVisibleCheckboxes();
                syncHiddenInputs();
                syncSelectAll();
            });
        </script>
    @endpush
</x-main-layout>
