<div style="background-color: {{ $bookmarked ? '#00e283' : '#00a1e2' }}; width: 40px; height: 40px; border-radius: 20px; display: flex; justify-content: center; align-items: center; box-shadow: 0px 0px 9px -5px #333;">
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
