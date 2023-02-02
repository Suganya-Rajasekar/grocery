<!-- Name Field -->
<div class="col-md-6">
<div class="form-group ">
    {!! Form::label('name', 'Name:') !!}
    <input type="text" value="{{ $service->name }}" name="" class="form-control " disabled>
 
</div>
</div>

<!-- Description Month Field -->
<div class="col-md-6">
<div class="form-group">
    {!! Form::label('description', 'Description:') !!}
       <textarea name="" class="form-control " readonly="">{{ $service->description }}</textarea>
   </div>

</div>

<!-- Price Field -->
<div class="col-md-6">
<div class="form-group">
    {!! Form::label('price', 'Price:') !!}
       <textarea name="" class="form-control " readonly="">{{ $service->price }}</textarea>
   </div>

</div>
