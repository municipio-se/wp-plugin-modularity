<div class="{{$input['columnSize']}}">
    @box([
        'attributeList' => ['target' => $input['linkTarget'] ?? null],
        'heading'   => $input['title'],
        'content'   => $input['content'],
        'link'      => $input['link'],
        'ratio'     => $ratio,
        'image'     => $input['image'],
        'icon'      => $input['boxIcon'],
        'context'   => $context
    ])
    @endbox
</div>
