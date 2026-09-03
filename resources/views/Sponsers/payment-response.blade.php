<x-main-layout title="نتيجة الدفع">
    <div class="container mt-5 text-center">
        @if($status === 'success')
            <div class="alert alert-success fw-bold" style="line-height: 1.8;">
                {!! $message !!}
            </div>
            <a href="{{ route('sponsor.orphan.waiting.index') }}" class="btn btn-success mt-3">
                العودة إلى قائمة الأيتام
            </a>
        @elseif($status === 'cancelled')
            <div class="alert alert-warning fw-bold">{{ $message }}</div>
            <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">العودة</a>
        @else
            <div class="alert alert-danger fw-bold">{{ $message }}</div>
            <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">العودة</a>
        @endif
    </div>
</x-main-layout>
