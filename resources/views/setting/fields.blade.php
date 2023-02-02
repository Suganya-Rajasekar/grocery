<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Per Bedroom Price:') !!}
    {!! Form::text('bedroom', null, ['class' => 'form-control','maxlength' => 70,'maxlength' => 70]) !!}
</div>

<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Per Bathroom Price:') !!}
    {!! Form::text('bathroom', null, ['class' => 'form-control','maxlength' => 70,'maxlength' => 70]) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-purple']) !!}
</div>

