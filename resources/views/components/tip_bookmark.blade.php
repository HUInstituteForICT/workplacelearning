<div class="{{ $bookmarked ?: 'bookmarkAnim' }} bookmarkTip" style="background-color: {{ $bookmarked ? '#00e283' : '#00a1e2' }};">
    @if ($bookmarked)

        <img class="save_tip_icon"
             src="{{ URL::asset('assets/img/opgeslagen_icon_wit.svg', true) }}"/></span>
    @else
        <a title="{{ __('saved_learning_items.save') }}" class="right"
           href="{{ route('saved-learning-item-create', ['category' => 'tip', 'item_id' => $id]) }}">
            <img class="save_tip_icon"
                 src="{{ URL::asset('assets/img/opgeslagen-niet-ingevuld.svg', true) }}"/></a>
    @endif
</div>
