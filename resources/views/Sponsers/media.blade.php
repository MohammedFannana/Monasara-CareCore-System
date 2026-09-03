<x-main-layout title="1000 أمل لأبناء الشهداء">

    @push('styles')

        <style>
            .color-text{
                color:rgba(36, 36, 36, 0.6);
            }

            .media-card {
                width: 100%;
                max-width: 340px;
                background: #fff;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.08);
                overflow: hidden;
                margin-bottom: 20px;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .media-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 8px 18px rgba(0,0,0,0.12);
            }

            .media-preview {
                width: 100%;
                height: 220px;
                background: #f5f5f5;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .media-preview img,
            .media-preview video {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .media-note {
                padding: 12px 14px;
                font-size: 14px;
                color: #555;
                border-top: 1px solid #eee;
                background: #fafafa;
            }


        </style>

    @endpush


    <div class="header fw-bold" style="color: var(--primary-color);font-size:18px;  border-top-right-radius: 6px;border-top-left-radius:6px; padding:10px;background:linear-gradient(to left , #c6fdda , #edfaf1)">
        مرحبًا بك في لوحة الكافلين
        <p class="color-text fw-normal mt-2" style="font-size: 15px">
            يعرض هذا القسم يعرض صور وفيديوهات  الأيتام المكفولين ، بما في ذلك بياناتهم الشخصية،          .
        </p>
    </div>

    <x-alert name="success" />
    <x-alert name="danger" />

    <section class="family-information mt-5">

        <div class="rounded">

            <div class="d-flex justify-content-between mb-3">
                <p class="fs-5 fw-semibold"> شاهد يتيمك (صور، فيديو) </p>
            </div>



            @forelse ($medias as $media)

                <div class="media-card">
                    <div class="media-preview">
                        @if($media->type === 'image')
                            <img src="{{ asset('storage/'.$media->file_path) }}" alt="media">
                        @else
                            <video controls>
                                <source src="{{ asset('storage/'.$media->file_path) }}">
                                متصفحك لا يدعم تشغيل الفيديو
                            </video>
                        @endif
                    </div>

                    @if($media->note)
                        <div class="media-note">
                            {{ $media->note }}
                        </div>
                    @endif
                </div>


            @empty


                <p class="text-center fs-5 rounded text-danger">
                    {{__('لا يوجد وسائط  لهذا اليتيم في النظام')}}
                </p>


            @endforelse


        </div>

    </section>

</x-main-layout>
