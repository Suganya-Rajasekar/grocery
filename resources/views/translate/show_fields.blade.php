<!-- Name Field -->
<div class="col-md-6">
<div class="form-group ">
    {!! Form::label('name', 'Name:') !!}
    <input type="text" value="{{ $category->name }}" name="" class="form-control " disabled>
 
</div>
</div>

<!-- Description Month Field -->
<div class="col-md-6">
<div class="form-group">
    {!! Form::label('description', 'Description:') !!}
       <textarea name="" class="form-control " readonly="">{{ $category->description }}</textarea>
   </div>

</div>

<!-- Image Field -->
<div class="col-md-3">
    <p class="font-weight-bold pb-2 mb-0 f_16">Image</p>
    <div class="card">
        <img id="card-img-top" class="p-10" src="{{$category->image_src}}" alt="Card image cap">
    </div>
</div>

