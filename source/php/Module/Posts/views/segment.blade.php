@includeWhen(!$hideTitle && !empty($postTitle), 'partials.post-title')
@includeWhen($preamble, 'partials.preamble')
@if($posts)
    <div class="o-grid{{ !empty($stretch) ? ' o-grid--stretch' : '' }}{{ !empty($noGutter) ? ' o-grid--no-gutter' : '' }}">
        @foreach ($posts as $post)
            <div class="{{ $posts_columns }}">
                @include('partials.post.segment')
            </div>
        @endforeach
    </div>

    @include('partials.more')

@endif