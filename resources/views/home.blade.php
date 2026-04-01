@extends('layouts.app')

@section('content')

<div class="videos">

    @if($videos->count())
        @foreach($videos as $video)
            <div class="video-card">
                <video controls preload="metadata">
                    <source src="{{ asset($video->video_path) }}" type="video/mp4">
                </video>

                <h4>{{ $video->title }}</h4>
                <p>{{ $video->description }}</p>
            </div>
        @endforeach

    @else
        {{-- fallback nếu chưa có video --}}
        @for ($i = 0; $i < 8; $i++)
            <div class="video-card">
                <img src="https://picsum.photos/300/150">
                <h4>Video {{ $i }}</h4>
                <p>Channel name</p>
            </div>
        @endfor
    @endif

</div>

@endsection