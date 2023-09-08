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
  
                <h6 class="card-title">Edit Roles</h6>

            <form id="myForm" method="POST" action="{{route('update.roles')}}" class="forms-sample">
                @csrf
<input type="hidden" name="id" value="{{$roles->id}}">
                <div class="form-group mb-3">
                    <label for="exampleInputUsername1" class="form-label">Roles Name</label>
                    <input type="text" name="name" class="form-control" value="{{$roles->name}}">
                </div>

                

            
            <button type="submit" class="btn btn-primary me-2">Update Roles</button>
                
            </form>
                </div>
            </div>
        </div>
      </div>
    </div>
</div>


@endsection