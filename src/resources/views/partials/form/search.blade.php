<div class="input-group">
    <input id="navbar-search-input" type="{{ $input }}" name="{{ $name }}" value="{{ $value }}" placeholder="{{ $placeholder }}" class="form-control {{ $class }}" {!! $attributes !!}>
    @if(isset($searchBtn) && $searchBtn)
    <span class="input-group-btn">
        <button id="navbar-search-input" type="submit" name="search" class="form-control" data-loading-text="<i class='fa fa-spinner fa-spin '></i>">
          <i class="fa fa-search"></i>
        </button>
    </span>
    @endif
</div>