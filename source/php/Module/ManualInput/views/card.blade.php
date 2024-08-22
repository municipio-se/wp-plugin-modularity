@includeWhen(empty($hideTitle) && !empty($postTitle), 'partials.post-title')
@if (!empty($manualInputs))
    <div class="o-grid{{ !empty($stretch) ? ' o-grid--stretch' : '' }}">
        @foreach ($manualInputs as $input)
        <div class="{{$columns}}">
        @endphp
            @card([
                'link'              => $input['link']['url'],
                'heading'           => $input['title'],
                'context'           => $context,
                'content'           => $input['content'],
                'image'             => $input['image'],
                'imageFirst'        => $input['imageBeforeContent'],
                'containerAware'    => true,
                'classList'         => ['u-height--100']
            ])
            @endcard
        </div>
        @endforeach
    </div>
@endif