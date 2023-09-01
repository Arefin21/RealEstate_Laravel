@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">

    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            
        <button type="button" class="btn btn-inverse-info" data-bs-toggle="modal"
         data-bs-target="#exampleModal">Add Category</button>

        </ol>
    </nav>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
<div class="card">
  <div class="card-body">
    <h6 class="card-title">Blog Category All</h6>
    <p class="text-muted mb-3">Read the <a href="https://datatables.net/" target="_blank"> Official DataTables Documentation </a>for a full list of instructions and other options.</p>
    <div class="table-responsive">
      <table id="dataTableExample" class="table">
        <thead>
          <tr>
            <th>Sl</th>
            <th>Blog Category Name</th>
            <th>Blog Category Slug</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>

        @foreach ($category as $key=>$item )
            
          <tr>
            <td>{{$key+1}}</td>
            <td>{{$item->catagory_name}}</td>
            <td>{{$item->catagory_slug}}</td>
            <td>
                  
    <button type="button" class="btn btn-inverse-info" data-bs-toggle="modal"
    data-bs-target="#catedit" id="{{$item->id}}" onclick="categoryEdit(this.id)">Edit</button>

    <a href="{{route('delete.blog.category',$item->id)}}" class="btn btn-inverse-danger" 
        id="delete">Delete</a>  

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


<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Category</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
        </div>
        <div class="modal-body">

            <form method="POST" action="{{route('store.blog.category')}}" class="forms-sample" enctype="multipart/form-data">
                @csrf

            <div class="mb-3">
                <label for="exampleInputUsername1" class="form-label">Blog Category Name</label>
                <input type="text" name="catagory_name" class="form-control ">
            
            </div>
        </div>
        <div class="modal-footer">
          
          <button type="submit" class="btn btn-primary">Save Blog Category</button>
        </div>
    </form>
      </div>
    </div>
  </div>

{{-- //Edit Category Model --}}

  <div class="modal fade" id="catedit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="btn-close"></button>
        </div>
        <div class="modal-body">

            <form method="POST" action="{{route('update.blog.category')}}" class="forms-sample" >
                @csrf

<input type="hidden" name="cat_id" id="cat_id">

            <div class="mb-3">
                <label for="exampleInputUsername1" class="form-label">Blog Category Name</label>
                <input type="text" name="catagory_name" class="form-control" id="cat">
            
            </div>
        </div>
        <div class="modal-footer">
          
          <button type="submit" class="btn btn-primary">Update Blog Category</button>
        </div>
    </form>
      </div>
    </div>
  </div>

  <script type="text/javascript">

  function categoryEdit(id){
    $.ajax({
        type:'GET',
        url:'/blog/category/'+id,
        dataType:'json',

        success:function(data){
           // console.log(data)
           $('#cat').val(data.catagory_name);
           $('#cat_id').val(data.id);
        }
  })
  }

  </script>

@endsection