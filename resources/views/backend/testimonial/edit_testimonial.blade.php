@extends('admin.admin_dashboard')
@section('admin')

<script src="https://code.jquery.com/jquery-3.7.0.slim.min.js" integrity="sha256-tG5mcZUtJsZvyKAxYLVXrmjKBVLd6VpVccqz/r4ypFE=" crossorigin="anonymous"></script>

<div class="page-content">

    <div class="row profile-body">

      <!-- middle wrapper start -->
      <div class="col-md-8 col-xl-8 middle-wrapper">
        <div class="row">
          
            <div class="card">
                <div class="card-body">
  
                <h6 class="card-title">Update Testimonial</h6>

            <form method="POST" action="{{route('update.testimonials')}}" class="forms-sample" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{$testimonial->id}}">
                <div class="mb-3">
                    <label for="exampleInputUsername1" class="form-label"> Name</label>
                    <input type="text" name="name" class="form-control " value="{{$testimonial->name}}">
                
                </div>

                <div class="mb-3">
                    <label for="exampleInputUsername1" class="form-label"> Position</label>
                    <input type="text" name="position" class="form-control" value="{{$testimonial->position}} ">
                
                </div>

                <div class="mb-3">
                    <label for="exampleInputUsername1" class="form-label"> Message</label>
                    <textarea name="message" id="" rows="3" class="form-control">{{$testimonial->message}}</textarea>
                
                </div>

                <div class="mb-3">
                    <label for="exampleInputUsername1" class="form-label">Testimonial Photo</label>
                    <input type="file" name="image" class="form-control" id="image">
                </div>

                <div class="mb-3">
                    <label for="exampleInputUsername1" class="form-label"></label>
                    <img id="showImage" class="wd-80 rounded-circle" 
                    src="{{ asset($testimonial->image) }}" alt="profile">
                </div>
            
            <button type="submit" class="btn btn-primary me-2">Save Testimonial</button>
                
            </form>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('#image').change(function(e){
            var reader=new FileReader();
            reader.onload=function(e){
                $('#showImage').attr('src',e.target.result);
            }
            reader.readAsDataURL(e.target.files['0']);
        });
    });
</script>

@endsection