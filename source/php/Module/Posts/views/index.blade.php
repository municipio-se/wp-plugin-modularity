@includeWhen(!$hideTitle && !empty($postTitle), 'partials.post-title')
@includeWhen($preamble, 'partials.preamble')

<div class="o-grid{{ !empty($stretch) ? ' o-grid--stretch' : '' }}{{ !empty($noGutter) ? ' o-grid--no-gutter' : '' }}">
    @if($posts)    
        @foreach ($posts as $post)
            @if ($post->asTemplate)
                <template name="mod-posts-post">
                    <div class="{{ $posts_columns }}">
                        @include('partials.post.card')
                    </div>
                </template>
            @else
                <div class="{{ $loop->first && $highlight_first_column ? $highlight_first_column : $posts_columns }}">
                    @if ($loop->first && $highlight_first_column && $highlight_first_column_as === 'block')
                        @include('partials.post.block', ['ratio' => '16:9'])
                    @else
                        @include('partials.post.card')
                    @endif
                </div>
            @endif
        @endforeach
    @endif
</div>

@include('partials.more')
