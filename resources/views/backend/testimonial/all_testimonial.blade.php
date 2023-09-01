@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <a href="{{route('add.testimonials')}}" class="btn btn-inverse-info">Add Testimonial</a>  
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
<div class="card">
  <div class="card-body">
    <h6 class="card-title">Testimonial All</h6>
    <p class="text-muted mb-3">Read the <a href="https://datatables.net/" target="_blank"> Official DataTables Documentation </a>for a full list of instructions and other options.</p>
    <div class="table-responsive">
      <table id="dataTableExample" class="table">
        <thead>
          <tr>
            <th>Sl</th>
            <th> Name</th>
            <th> Position</th>
            <th> Image</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>

        @foreach ($testimonial as $key=>$item )
            
          <tr>
            <td>{{$key+1}}</td>
            <td>{{$item->name}}</td>
            <td>{{$item->position}}</td>
            <td><img src="{{asset($item->image)}}" alt="" 
                style="width:70px; height:40px;" ></td>
            <td>
              <a href="{{route('edit.testimonials',$item->id)}}" class="btn btn-inverse-warning">Edit</a>  
              <a href="{{route('delete.testimonials',$item->id)}}" class="btn btn-inverse-danger" id="delete">Delete</a>  
            </td>
          </tr>

        @endforeach
         
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>
</div>

</div>

@endsection