<!-- Name Field -->
<div class="form-group col-sm-6">
    {!! Form::label('name', 'Key:') !!}
    {!! Form::text('name', null, ['class' => 'form-control','maxlength' => 70,'maxlength' => 70]) !!}
</div>

<!-- Value Field -->
<div class="form-group col-sm-6"> 
    {!! Form::label('content', 'Value:') !!}
    {!! Form::textarea('content', null, ['class' => 'form-control']) !!}
</div>
<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit('Save', ['class' => 'btn btn-purple']) !!}
    <a href="{{ route('translate.index') }}" class="btn btn-default">Cancel</a>
</div>

