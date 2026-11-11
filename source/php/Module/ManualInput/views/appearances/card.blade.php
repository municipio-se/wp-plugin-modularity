 <div class="{{$input['columnSize']}}">
    @card([
        'attributeList' => ['target' => $input['linkTarget'] ?? null],
        'link'              => $input['link'],
        'heading'           => $input['title'],
        'context'           => $context,
        'content'           => $input['content'],
        'image'             => $input['image'],
        'imageFirst'        => true,
        'containerAware'    => true,
        'classList'         => ['u-height--100']
    ])
    @endcard
</div>
