<article class="{{ isset($font_size) ? $font_size : '' }}">
    
    @if (!$hideTitle && !empty($postTitle))
        @typography([
                "variant" => "h2",
                "element" => "h4",
                "id" => 'mod-text-' . $ID .'-label'
        ])
                {!! $postTitle !!}
        @endtypography
    @endif
    
    {!! $post_content !!}
</article>
