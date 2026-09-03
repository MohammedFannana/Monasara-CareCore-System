<x-main-layout title="1000 أمل لأبناء الشهداء">

    @push('styles')
        <style>
            .color-text {
                color: rgba(36, 36, 36, 0.6);
            }
        </style>
    @endpush

    <div class="header fw-bold" style="color: var(--primary-color);font-size:18px; border-top-right-radius: 6px;border-top-left-radius:6px; padding:10px;background:linear-gradient(to left , #c6fdda , #edfaf1)">
        مرحبًا بك في لوحة الكافلين
        <p class="color-text fw-normal mt-2" style="font-size: 15px">
            يعرض هذا القسم معلومات الأيتام المكفولين، بما في ذلك بياناتهم الشخصية، مع إمكانية متابعة حالة الكفالة، وتجديدها أو إيقافها حسب الحاجة.
        </p>
    </div>

    <x-alert name="success" />
    <x-alert name="danger" />

    <section class="family-information mt-5">
        <div class="rounded">

            <div class="d-flex justify-content-between mb-3">
                <p class="fs-5 fw-semibold">قائمة الأيتام المكفولين</p>
            </div>

            <!-- بحث -->
            <form action="{{ route('sponsor.orphan.sponsor.index') }}" method="GET" class="search custom-sm-style w-100">
                @csrf
                <div class="input-group flex-nowrap mb-4">
                    <input type="text" name="search" class="form-control" placeholder="{{ __('البحث عن يتيم...') }}" aria-describedby="addon-wrapping">
                    <button type="submit" class="input-group-text" id="addon-wrapping">
                        <svg xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 512 512">
                            <path fill="#1e9448" d="M384 208A176 176 0 1 0 32 208a176 176 0 1 0 352 0zM343.3 366C307 397.2 259.7 416 208 416C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208c0 51.7-18.8 99-50 135.3L507.3 484.7c6.2 6.2 6.2 16.4 0 22.6s-16.4 6.2-22.6 0L343.3 366z"/>
                        </svg>
                    </button>
                </div>
            </form>

            <!-- زر تجديد الكفالة الجماعي -->
            <div class="mb-3" id="renewButtonWrapper" style="display: none;">
                <form id="batchSponsorshipForm" action="{{ route('sponsor.orphan.create') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- hidden input واحد يحوي جميع IDs مفصولة بفاصلة -->
                    <input type="hidden" name="orphans_ids" id="selectedOrphans">
                    <button type="submit" class="btn btn-success">{{ __('دفع الكفالة') }}</button>
                </form>
            </div>

            <!-- جدول الأيتام -->
            <!--<div class="table-responsive">-->
                <table class="border-0 w-100 text-center" style="border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="selectAll"></th>
                            <th>اسم اليتيم</th>
                            <th>الجنس</th>
                            <th>العمر</th>
                            <th>المبلغ المدفوع</th>
                            <th>مدة الكفالة</th>
                            <th>تاريخ بدء الكفالة</th>
                            <th>التاريخ القادم للدفع</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orphans as $orphan)
                            <tr>
                                @php
                                    // استخدام latestSponsorship بدلاً من جمع الكل ثم last() لتقليل استعلامات N+1
                                    $lastSponsorship = $orphan->latestSponsorship;

                                    // حساب العمر
                                    $birthDate = \Carbon\Carbon::parse($orphan->birth_date);
                                    $age = $birthDate->age;

                                    // استخراج تاريخ البدء والمدة من آخر كفالة
                                    $startDate = ($lastSponsorship && $lastSponsorship->sponsorship_date)
                                        ? \Carbon\Carbon::parse($lastSponsorship->sponsorship_date)
                                        : null;

                                    $duration = $lastSponsorship->duration ?? 0;

                                    // حساب تاريخ الانتهاء
                                    $endDate = ($startDate && $duration)
                                        ? $startDate->copy()->addMonths((int)$duration)
                                        : null;
                                @endphp

                                <td>
                                    <input type="checkbox" class="orphan-checkbox" value="{{ $orphan->id }}">
                                </td>
                                <td>{{ $orphan->name }}</td>
                                <td>{{ $orphan->gender }}</td>
                                <td>{{ $age }}</td>
                                <td>{{ $lastSponsorship->bail_amount ?? '0' }}</td>
                                <td>{{ $duration }}</td>
                                <td>{{ $lastSponsorship->sponsorship_date ?? '-' }}</td>
                                <td>{{ $endDate ? $endDate->format('Y-m-d') : '-' }}</td>

                                <td style="position: relative;">
                                    <img class="show-action" src="{{ asset('images/Group 8.svg') }}" alt="">
                                    <div class="action" style="width:180px">
                                        <!-- تجديد الكفالة مفرد -->
                                        <form action="{{ route('sponsor.orphan.create') }}" method="POST" class="text-right">
                                            @csrf
                                            <input type="hidden" name="orphans_ids[]" value="{{ $orphan->id }}">
                                            <button type="submit" class="btn m-0 p-0" style="color: var(--text-color);">
                                                <img src="{{ asset('images/Show.svg') }}" alt="">
                                                {{ __('دفع الكفالة') }}
                                            </button>
                                        </form>

                                        <a href="{{ route('sponsor.orphan.sponsor.view', $orphan->id) }}" class="text-decoration-none mb-1">
                                            <img src="{{ asset('images/Show.svg') }}" alt="">
                                            <span style="color: var(--text-color);">{{ __('عرض التفاصيل') }}</span>
                                        </a>

                                        <a href="{{ route('sponsorship.show', $orphan->id) }}" class="text-decoration-none mb-1">
                                            <img src="{{ asset('images/Show.svg') }}" alt="">
                                            <span style="color: var(--text-color);">{{ __('عرض الكفالات') }}</span>
                                        </a>

                                        <a href="{{ route('gift.show', $orphan->id) }}" class="text-decoration-none mb-1">
                                            <img src="{{ asset('images/Show.svg') }}" alt="">
                                            <span style="color: var(--text-color);">{{ __('عرض الهدايا') }}</span>
                                        </a>

                                        <a href="{{ route('sponsor.orphan.media', $orphan->id) }}" class="text-decoration-none mb-1">
                                            <img src="{{ asset('images/Show.svg') }}" alt="">
                                            <span style="color: var(--text-color);">{{ __('شاهد يتيمك') }}</span>
                                        </a>

                                        <a href="{{ route('orphan.payments', $orphan->id) }}" class="text-decoration-none mb-1">
                                            <img src="{{ asset('images/Show.svg') }}" alt="">
                                            <span style="color: var(--text-color);">{{ __(' شاهد تقارير ايتامك  ') }}</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center fs-5 rounded text-danger">
                                    {{ __('لا يوجد أيتام مكفولين في النظام') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            <!--</div>-->
        </div>
    </section>

    @push('scripts')
    <script>
        const allOrphanIds = @json($allOrphanIds);
    </script>

    <script>
        const checkboxes = document.querySelectorAll('.orphan-checkbox');
        const renewButtonWrapper = document.getElementById('renewButtonWrapper');
        const selectedOrphansInput = document.getElementById('selectedOrphans');
        const selectAll = document.getElementById('selectAll');

        function updateSelectedOrphans() {
            const selected = Array.from(checkboxes)
                                .filter(ch => ch.checked)
                                .map(ch => ch.value);

            // إذا تم تحديد كل checkboxes الصفحة، خليه يعبر عن تحديد جزئي
            const isPartialSelect = selected.length > 0 && selected.length < checkboxes.length;

            renewButtonWrapper.style.display = selected.length > 0 ? 'block' : 'none';

            // إذا selectAll مفعل و partial لا نستخدم جميع IDs
            if(selectAll.checked && !isPartialSelect){
                selectedOrphansInput.value = allOrphanIds.join(',');
            } else {
                selectedOrphansInput.value = selected.join(',');
            }
        }

        // حدث تغيير لكل checkbox
        checkboxes.forEach(cb => cb.addEventListener('change', updateSelectedOrphans));

        // تحديد/إلغاء تحديد الكل
        if(selectAll){
            selectAll.addEventListener('change', function() {
                const isChecked = selectAll.checked;

                // كل checkboxes في الصفحة الحالية تتغير
                checkboxes.forEach(cb => cb.checked = isChecked);

                if(isChecked){
                    // كل الأيتام في النظام مختار
                    selectedOrphansInput.value = allOrphanIds.join(',');
                    renewButtonWrapper.style.display = 'block';
                } else {
                    selectedOrphansInput.value = '';
                    renewButtonWrapper.style.display = 'none';
                }
            });
        }

    </script>
    @endpush

    {{$orphans->withQueryString()->links()}}

</x-main-layout>
