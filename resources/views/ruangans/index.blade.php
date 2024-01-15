@extends('layout')
  
@section('content')

    <div class="container">
        <div id="message">
        </div>
          @if (session('error'))
                    <div class="alert alert-danger" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col col-sm-9">Daftar Ruangan</div>
                    <div class="col col-sm-3">
                        <button type="button" id="add_data" class="btn btn-success btn-sm float-end">Add</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="sample_data">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Keterangan</th>
                                <th>Kapasitas</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal" tabindex="-1" id="action_modal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" id="sample_form">
                        <div class="modal-header">
                            <h5 class="modal-title" id="dynamic_modal_title"></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" name="nama_ruangan" id="nama_ruangan" class="form-control" />
                                <span id="nama_ruangan_error" class="text-danger"></span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <input type="text" name="keterangan" id="keterangan" class="form-control" />
                                <span id="keterangan_error" class="text-danger"></span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kapasitas</label>
                                    <input type="text" name="kapasitas" id="kapasitas" class="form-control" />
                                    <span id="pass_error" class="text-danger"></span>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id" id="id" />
                            <input type="hidden" name="action" id="action" value="Add" />
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="action_button">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    
    <script>
    $(document).ready(function() {
        showAll();

        $('#add_data').click(function(){
            $('#dynamic_modal_title').text('Add Data User');
            $('#sample_form')[0].reset();
            $('#action').val('Add');
            $('#action_button').text('Add');
            $('.text-danger').text('');
            $('#action_modal').modal('show');
        });
        
        $('#sample_form').on('submit', function(event){
            event.preventDefault();
            if($('#action').val() == "Add"){
                var formData = {
                '_token': '{{ csrf_token() }}',
                'nama_ruangan' : $('#nama_ruangan').val(),
                'keterangan' : $('#keterangan').val(),
                'kapasitas' : $('#kapasitas').val()
                }

                $.ajax({
                    headers: {
                        "Content-Type":"application/json",
                        "Authorization": "Bearer {{ session('accessToken') }}"
                    },
                    url:"{{ url('api/ruangans/create')}}",
                    method:"POST",
                    data: JSON.stringify(formData),
                    success:function(data){
                        $('#action_button').attr('disabled', false);
                        $('#message').html('<div class="alert alert-success">'+data.message+'</div>');
                        $('#action_modal').modal('hide');
                        $('#sample_data').DataTable().destroy();
                        showAll();
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            }else if($('#action').val() == "Update"){
                var formData = {
                    '_token': '{{ csrf_token() }}',
                    'nama_ruangan' : $('#nama_ruangan').val(),
                    'keterangan' : $('#keterangan').val(),
                    'kapasitas' : $('#kapasitas').val()
                }

                $.ajax({ 
                    headers: {
                        "Content-Type":"application/json",
                        "Authorization": "Bearer {{ session('accessToken') }}"
                    },
                    url:"{{ url('api/ruangans/')}}/"+$('#id').val()+"/update",
                    method:"POST",
                    data: JSON.stringify(formData),
                    success:function(data){
                        $('#action_button').attr('disabled', false);
                        $('#message').html('<div class="alert alert-success">'+data.message+'</div>');
                        $('#action_modal').modal('hide');
                        $('#sample_data').DataTable().destroy();
                        showAll();
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            }
            });
    });

    function showAll() {
        $.ajax({
            type: "GET",
            headers: {
                "Content-Type":"application/json",
                "Authorization": "Bearer {{ session('accessToken') }}"
            },
            url:"{{ url('api/ruangans/all')}}",
            success: function(response) {
            // console.log(response);
                var json = response;
                var dataSet=[];
                for (var i = 0; i < json.length; i++) {
                    var sub_array = {
                        'nama_ruangan' : json[i].nama_ruangan,
                        'keterangan' : json[i].keterangan,
                        'kapasitas' : json[i].kapasitas,
                        'action' : '<button onclick="showOne('+json[i].id+')" class="btn btn-sm btn-warning">Edit</button>'+
                        '<button onclick="deleteOne('+json[i].id+')" class="btn btn-sm btn-danger">Delete</button>'
                    };
                    dataSet.push(sub_array);
                }
                $('#sample_data').DataTable({
                    data: dataSet,
                    columns : [
                        { data : "nama_ruangan" },
                        { data : "keterangan" },
                        { data : "kapasitas" },
                        { data : "action" }
                    ]
                });
            },
            error: function(err) {
                console.log(err);
            }
        });
    }

    function showOne(id) {
        $('#dynamic_modal_title').text('Edit Data');
        $('#sample_form')[0].reset();
        $('#action').val('Update');
        $('#action_button').text('Update');
        $('.text-danger').text('');
        $('#action_modal').modal('show');

        $.ajax({
            type: "GET",
            headers: {
                "Content-Type":"application/json",
                "Authorization": "Bearer {{ session('accessToken') }}"
            },
            url:"{{ url('api/ruangans')}}/"+id+"/show",
            success: function(response) {
                $('#id').val(response.id);
                $('#nama_ruangan').val(response.nama_ruangan);
                $('#keterangan').val(response.keterangan);
                $('#kapasitas').val(response.kapasitas);
            },
            error: function(err) {
                console.log(err);
            }
        });
    }

    function deleteOne(id) {
        alert('Yakin untuk hapus data ?');
        $.ajax({
            headers: {
                "Content-Type":"application/json",
                "Authorization": "Bearer {{ session('accessToken') }}"
            },
            url:"{{ url('api/ruangans')}}/"+id+"/delete",
            method:"DELETE",            
            data: JSON.stringify({
                    '_token': '{{ csrf_token() }}'
                }),
            success:function(data){
                $('#action_button').attr('disabled', false);
                $('#message').html('<div class="alert alert-success">'+data+'</div>');
                $('#action_modal').modal('hide');
                $('#sample_data').DataTable().destroy();
                showAll();
            },
            error: function(err) {
                console.log(err);
            }
        });
    }
    </script>
@endsection