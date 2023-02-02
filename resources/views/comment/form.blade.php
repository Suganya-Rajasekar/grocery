@extends('layouts.backend.app')
@section('content')

<!-- Content area -->
<div class="content">

  <!-- Form horizontal -->
  <div class="panel panel-flat">
    <div class="panel-heading">
      <h5 class="panel-title">Create/Edit Review</h5>
      <div class="heading-elements">
        <ul class="icons-list">
          <li><a data-action="collapse"></a></li>
        </ul>
      </div>
    </div>

    <div class="panel-body">

        
        <form action="{!!url(getRoleName().'/review/store')!!}" method="POST" class="form-horizontal" enctype="Multipart/form-data">
        {{ csrf_field() }}
        {{ method_field('PUT') }}
        <input type="hidden" name="id" id="id" value="{!!isset($review->id) ? $review->id : ''!!}">
        <fieldset class="content-group">
          <legend class="text-bold">Basic details</legend>          
          <div class="form-group">
            <label class="control-label col-lg-2">Rating</label>
            <div class="col-lg-5">
            <select name="rating" id="rating" class="select-search" required="">
              <option {!!isset($review->rating) && $review->rating==1  ? 'selected=""' : ''!!}  value="1">1</option>
              <option {!!isset($review->rating) && $review->rating==2  ? 'selected=""' : ''!!}  value="2">2</option>
              <option {!!isset($review->rating) && $review->rating==3  ? 'selected=""' : ''!!}  value="3">3</option>
              <option {!!isset($review->rating) && $review->rating==4  ? 'selected=""' : ''!!}  value="4">4</option>
              <option {!!isset($review->rating) && $review->rating==5  ? 'selected=""' : ''!!}  value="5">5</option>
            </select>
            </div>
          </div>
           <div class="form-group">
            <label class="control-label col-lg-2">Review</label>
            <div class="col-lg-5">
            <input type="text" class="form-control" name="reviews" value="{!!isset($review->reviews) ? $review->reviews : ''!!}"> 
            </div>
          </div>    

          <div class="form-group">
            <label class="control-label col-lg-2">Status</label>
            <div class="col-lg-5">
            <select name="status" id="status" class="select-search" required="">
              <option {!!isset($review->status) && $review->status=='pending'  ? 'selected=""' : ''!!}  value="pending">Pending</option>
              <option {!!isset($review->status) && $review->status=='published'  ? 'selected=""' : 'published'!!}  value="published">Published</option>
              <option {!!isset($review->status) && $review->status=='rejected'  ? 'selected=""' : 'rejected'!!}  value="rejected">Rejected</option>
              
            </select>
            </div>
          </div>
        </fieldset>

        <div class="text-right">
          <button type="submit" class="btn btn-primary">Submit <i class="icon-arrow-right14 position-right"></i></button>
        </div>
      </form>
    </div>
  </div>
  <!-- /form horizontal -->

</div>
<!-- /content area -->
@endsection
