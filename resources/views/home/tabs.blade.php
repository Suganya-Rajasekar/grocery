<div class="container-fluid area-asw-filter">      
    <section class="filterby my-5 mx-sm-5 mx-0 ">
        <div class="container-fluid">
            <input type="hidden" id="hid_lat" value="0">
            <input type="hidden" id="hid_lang" value="0">
            <div class="form-group">
                <select class="form-control font-montserrat" name="area" id="filter">
                    @foreach($Explore->exploreseemore as $k => $value)      
                    <option @if(isset($value->slug) && $module == $value->slug) selected="true" @endif value="{{ $value->slug }}" data-id="@if(isset($value->href)){{ $value->href }}@else{{'explore'}}@endif">{{ $value->name }}</option>
                    @endforeach    
                </select>
            </div>
        </div>
    </section>
</div>