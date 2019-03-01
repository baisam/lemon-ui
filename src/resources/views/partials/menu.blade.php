<ul @if($id)id="{{ $id }}"@endif class="sidebar-menu {{ $class }}" data-widget="tree" {!! $attributes !!}>
@foreach($items as $item)
    @if (is_string($item) && $item === '##_SEPARATOR_##')
    <li role="separator" class="divider"></li>
    @else
    <li @if($item->hasChildren())class="treeview"@elseif($item->isActive())class="active"@endif>{{ $item }}</li>
    @endif
@endforeach
</ul>
@script('menu', 'view')
$(function() {
    $('.sidebar-menu li.active').parents('li.treeview').addClass('menu-open');
    $('.sidebar-menu li.active').parents('ul.treeview-menu').show();

    $('.sidebar-menu li:not(.treeview) > a').on('click', function(){
        var $parent = $(this).parent('li').addClass('active');
        $parent.siblings('.treeview.active').find('> a').trigger('click');
        $parent.siblings().removeClass('active').find('li').removeClass('active');
    });
});
@endscript