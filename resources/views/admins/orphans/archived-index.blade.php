<x-main-layout title="1000 أمل لأبناء الشهداء">
    <div class="header fw-bold" style="color: var(--primary-color);font-size:18px;border-radius:6px;padding:10px;background:linear-gradient(to left,#c6fdda,#edfaf1)">
        قائمة الأيتام المؤرشفين
        <p class="fw-normal mt-2" style="font-size:15px;color:var(--text-color)">
            يعرض هذا القسم الأيتام المؤرشفين في النظام.
        </p>
    </div>

    <section class="family-information mt-5">
        <div class="rounded">
            <form action="{{ route('admin.orphan.ArchivedOrphan') }}" method="GET" class="search custom-sm-style w-100">
                <div class="input-group flex-nowrap mb-4">
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="البحث عن يتيم..." aria-label="البحث عن يتيم">
                    <button type="submit" class="input-group-text" aria-label="بحث">بحث</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="border-0 w-100 text-center" style="border-collapse:collapse;">
                    <thead>
                        <tr><th>#</th><th>اسم اليتيم</th><th>الجنس</th><th>الدولة</th><th>اسم الجمعية</th><th>الإجراءات</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($orphans as $orphan)
                            <tr>
                                <td><span class="value">{{ $orphan->id }}</span></td>
                                <td><span class="value">{{ $orphan->name }}</span></td>
                                <td><span class="value">{{ $orphan->gender }}</span></td>
                                <td><span class="value">{{ $orphan->country }}</span></td>
                                <td><span class="value">{{ $orphan->association->name }}</span></td>
                                <td><a href="{{ route('admin.orphan.show', $orphan) }}" class="text-decoration-none">عرض التفاصيل</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="text-center fs-5" style="color:var(--primary-color)">لا يوجد أيتام مؤرشفون</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{ $orphans->withQueryString()->links() }}
</x-main-layout>
