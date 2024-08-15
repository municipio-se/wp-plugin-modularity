@typography([
    'id' => 'mod-posts-' . $ID . '-label',
    'element' => $element ?? 'h2',
    'variant' => $variant ?? 'h2',
    'classList' => array_merge($classList ?? [], ['module-title'])
])
    {!! $postTitle !!}
@endtypography
