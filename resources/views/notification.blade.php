<x-main-layout title="1000 أمل لأبناء الشهداء">


    <div class="header fw-bold" style="color: var(--primary-color);font-size:18px;  border-top-right-radius: 6px;border-top-left-radius:6px; padding:10px;background:linear-gradient(to left , #c6fdda , #edfaf1)">
        مرحبًا بك في لوحة التحكم
        <p class="fw-normal mt-2" style="font-size: 15px;color:var(--text-color)">
            يعرض في هذا القسم الإشعارات المرسلة
        </p>
    </div>

    <x-alert name="success" class="mt-2"/>
    <x-alert name="danger" class="mt-2"/>

    <section class="family-information mt-5">

        <div class="rounded">

            <div class="row">
                @forelse ($notifications as $notification)
                    @if($notification->type === 'App\Notifications\NewSponsorNotification')
                        <div class="card col-12 p-2 d-flex flex-row justify-content-between pt-3">
                            <h6 class="fw-bold text-success">{{ $notification->data['title'] }}</h6>
                            <p>الكافل الجديد: {{ $notification->data['name'] }}</p>
                            <small>البريد: {{ $notification->data['email'] }}</small>
                        </div>
                    @else
                    <div class="card col-12 p-2 d-flex flex-row justify-content-between pt-3">
                        {{-- @dd($message->orphan_id) --}}
                        {{-- <h5> {{$notification->data['orphan_name']}} </h5> --}}
                        <p class="mb-1">
                            {{$notification->data['message']}}
                        </p>
                        <p style="color: #777;font-size:15px">
                            {{$notification->created_at->diffForHumans()}}
                        </p>

                    </div>
                    @endif



                @empty

                  <div class="p-3 fs-6 fw-semibold  rounded w-100" style="background:linear-gradient(to left , #c6fdda , #edfaf1)">
                      عذرًا، لا توجد أي إشعارات مرسلة
                  </div>

                @endforelse

            </div>

        </div>

    </section>

    {{-- {{$notifications->withQueryString()->links()}} --}}

</x-main-layout>
