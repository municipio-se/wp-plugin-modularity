@card([
    'context' => 'module.text.box'
])
    @if (empty($hideTitle) && !empty($postTitle))
        <div class="c-card__header">
            @typography([
                "element" => "h4",
                'id' => 'mod-text-' . $ID .'-label'
            ])
                {!! $postTitle !!}
            @endtypography
        </div>
    @endif
    <div class="c-card__body">
        {!! $post_content !!}
    </div>
@endcard