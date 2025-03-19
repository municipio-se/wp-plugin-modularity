@includeWhen(!$hideTitle && !empty($postTitle), 'partials.post-title')
@includeWhen($preamble, 'partials.preamble')

<div class="o-grid{{ !empty($stretch) ? ' o-grid--stretch' : '' }}{{ !empty($noGutter) ? ' o-grid--no-gutter' : '' }}">
    @if($posts)
        @foreach ($posts as $post)
            @if ($post->asTemplate)
                <template name="mod-posts-post">
                    <div class="{{ $posts_columns }}">
                        @include('partials.post.block')
                    </div>
                </template>
            @else
                <div class="{{ $loop->first && $highlight_first_column ? $highlight_first_column : $posts_columns }}">
                    @if ($loop->first && $highlight_first_column && $highlight_first_column_as === 'card')
                        @include('partials.post.card')
                    @else
                        @include('partials.post.block')
                    @endif
                </div>
            @endif
        @endforeach
    @endif
</div>

@include('partials.more')
