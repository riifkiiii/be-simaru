@extends('layout')
  
@section('content')

    <div class="container">
        <div id="message">
        </div>
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col col-sm-9">Booking Ruangan</div>
                    <div class="col col-sm-3">
                        <button type="button" id="add_data" class="btn btn-success btn-sm float-end">Booking Ruangan</button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="sample_data">
                        <thead>
                            <tr>
                                <th>Nama Pembooking</th>
                                <th>Nama Ruangan</th>
                                <th>Start Booking</th>
                                <th>End Booking</th>
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
                             @if($userRole != 'user')
                              <select class="form-control" class="form-control" name="user_id" id="user_id">
                                <option value="">-Pembooking-</option>
                                @foreach($user as $u)
                                <option value="{{ $u['id'] }}">{{ $u['name'] }}</option>
                                @endforeach
                              </select>
                            @else
                             <input type="hidden" name="user_id" id="user_id" value="{{ auth()->user()->id }}">
                            @endif
                            <div class="mb-3">
                                <label class="form-label">Pilih Ruangan</label>
                                <input type="hidden" name="user_id">
                              <select name="ruangan_id" class="form-control" id="ruangan_id">
                                <option value="">-Pilih Ruangan-</option>
                                @foreach($ruangan as $r)
                                    <option value="{{ $r['id'] }}"> {{ $r['nama_ruangan'] }} | Kapasitas {{ $r['kapasitas'] }}</option>
                                @endforeach
                              </select>
                                <span id="ruangan_id_error" class="text-danger"></span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Start Booking</label>
                                <input type="datetime-local" name="start_book" id="start_book" class="form-control" />
                                <span id="start_book_error" class="text-danger"></span>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">End Booking</label>
                                    <input type="datetime-local" name="end_book" id="end_book" class="form-control" />
                                    <span id="end_book_error" class="text-danger"></span>
                                </div>
                        </div>
                        <div class="modal-footer">
                            <input type="hidden" name="id" id="id" />
                            <input type="hidden" name="action" id="action" value="Add" />
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="action_button">Simpan Booking Ruangan</button>
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
            $('#dynamic_modal_title').text('Booking Ruangan');
            $('#sample_form')[0].reset();
            $('#action').val('Add');
            $('#action_button').text('Simpan Booking Ruangan');
            $('.text-danger').text('');
            $('#action_modal').modal('show');
        });
        
        $('#sample_form').on('submit', function(event){
            event.preventDefault();
            if($('#action').val() == "Add"){
                var formData = {
                '_token': '{{ csrf_token() }}',
                'ruangan_id' : $('#ruangan_id').val(),
                'user_id' : $('#user_id').val(),
                'start_book' : $('#start_book').val(),
                'end_book' : $('#end_book').val(),
                }

                $.ajax({
                    headers: {
                        "Content-Type":"application/json",
                        "Authorization": "Bearer {{ session('accessToken') }}"
                    },
                    url:"{{ url('api/bookings/create')}}",
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
                    'ruangan_id' : $('#ruangan_id').val(),
                    'user_id' : $('#user_id').val(),
                    'start_book' : $('#start_book').val(),
                    'end_book' : $('#end_book').val(),
                }

                $.ajax({ 
                    headers: {
                        "Content-Type":"application/json",
                        "Authorization": "Bearer {{ session('accessToken') }}"
                    },
                    url:"{{ url('api/bookings/')}}/"+$('#id').val()+"/update",
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
            url:"{{ url('api/bookings/all')}}",
            success: function(response) {
            // console.log(response);
                var json = response;
                var dataSet=[];
                for (var i = 0; i < json.length; i++) {
                    var sub_array = {
                        'pembooking' : json[i].user['name'],
                        'ruangan_id' : json[i].ruangan['nama_ruangan'],
                        'start_book' : json[i].start_book,
                        'end_book' : json[i].end_book,
                        'action' : '<button onclick="showOne('+json[i].id+')" class="btn btn-sm btn-warning">Edit</button>'+
                        '<button onclick="deleteOne('+json[i].id+')" class="btn btn-sm btn-danger">Delete</button>'
                    };
                    dataSet.push(sub_array);
                }
                $('#sample_data').DataTable({
                    data: dataSet,
                    columns : [
                        { data : "pembooking" },
                        { data : "ruangan_id" },
                        { data : "start_book" },
                        { data : "end_book" },
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
            url:"{{ url('api/bookings')}}/"+id+"/show",
            success: function(response) {
                $('#id').val(id);
                $("#ruangan_id").val(response.ruangan_id);
                $('#user_id').val(response.user_id);
                $('#start_book').val(response.start_book);
                $('#end_book').val(response.end_book);
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
            url:"{{ url('api/bookings')}}/"+id+"/delete",
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