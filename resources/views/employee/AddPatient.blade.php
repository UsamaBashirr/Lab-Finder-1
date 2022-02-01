@extends('layouts.app')
@section('title','Employee Dashboard')
@section('content')

<div class="content-body">
    @if (Session::get('success'))
    <div class="alert alert-success">
        {{ Session::get('success') }}
    </div>
    @endif
    <!-- Basic Tables start -->
    <div class="row mt-3" id="basic-table">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"> Patients </h3>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="modalBtn">
                        Add Patient
                    </button>
                </div>
                @if(count($users)>0)
                <div class="card-body">
                    <!-- Table with outer spacing -->
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>NAME</th>
                                    <th>EMAIL</th>
                                    <th>Due Date</th>
                                    <th>ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td class="text-bold-500">{{ $user->name }}</td>
                                    <td> {{ $user->email }} </td>
                                    <td class="text-bold-500"> {{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }} </td>
                                    <td>
                                        <div class="row">
                                            <span id="btn" value="{{$user->id}}"><i class="badge-circle badge-circle-light-secondary bx bx-show font-medium-1"></i></span>
                                            <a style="float: right; margin: 7px 0 0 7px;" href="/patient/delete/{{$user->id}}"><i class="badge-circle badge-circle-light-secondary bx bx-trash font-medium-1"></i></a>
                                            <i id="imageUpload" class="badge-circle badge-circle-light-secondary bx bx-upload font-medium-1"></i>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <h4 class="pl-2">No Record</h4>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Basic Tables end -->
    <!-- Modal -->
    <div class="modal fade" id="exampleModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> Add Patient </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="card-body">
                            <form action="/lab/addPatient" method="POST" class="form form-horizontal">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-3 pl-0 pl-0">
                                            <label>Name</label>
                                        </div>
                                        <div class="col-md-9 form-group ">
                                            <div class="position-relative has-icon-left">
                                                <input type="text" id="fname-icon" class="form-control" name="name" placeholder="Name">
                                                <span class="text-danger">@error('name'){{ $message }}@enderror</span>
                                                <div class="form-control-position">
                                                    <i class="bx bx-user"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 pl-0">
                                            <label>Email</label>
                                        </div>
                                        <div class="col-md-9 form-group">
                                            <div class="position-relative has-icon-left">
                                                <input type="email" id="email-icon" class="form-control" name="email" placeholder="Email">
                                                <span class="text-danger">@error('email'){{ $message }}@enderror</span>
                                                <div class="form-control-position">
                                                    <i class="bx bx-mail-send"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 pl-0">
                                            <label>Password</label>
                                        </div>
                                        <div class="col-md-9 form-group">
                                            <div class="position-relative has-icon-left">
                                                <input type="password" class="form-control" name="password" placeholder="Password">
                                                <span class="text-danger">@error('password'){{ $message }}@enderror</span>
                                                <div class="form-control-position">
                                                    <i class="bx bx-lock"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3 pl-0">
                                            <label>Confirm Password</label>
                                        </div>
                                        <div class="col-md-9 form-group">
                                            <div class="position-relative has-icon-left">
                                                <input type="password" class="form-control" name="cpassword" placeholder="Confirm Password">
                                                <span class="text-danger">@error('cpassword'){{ $message }}@enderror</span>
                                                <div class="form-control-position">
                                                    <i class="bx bx-lock"></i>
                                                </div>
                                            </div>
                                            <input type="text" name="lab_id" value="{{Auth::guard('web')->user()->lab_id}}" hidden>
                                            <input type="text" name="emp_id" value="{{Auth::guard('web')->user()->id}}" hidden>
                                            <input type="text" name="role" value="patient" hidden>
                                        </div>
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="reset" class="btn btn-light-secondary">Reset</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal -->

    <div class="modal fade" id="imageUploadModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> Upload Patient Report </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-md-12">
                        <div class="card-body">
                            <form action="/patient/uploadReport" method="POST" enctype="multipart/form-data" class="form form-horizontal">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-3 pl-0 pl-0">
                                            <label>File Name</label>
                                        </div>
                                        <div class="col-md-9 form-group ">
                                            <div class="position-relative has-icon-left">
                                                <input type="file" class="form-control" name="file" placeholder="Select report">
                                                <span class="text-danger">@error('file'){{ $message }}@enderror</span>
                                                <div class="form-control-position">
                                                    <i class="bx bx-user"></i>
                                                </div>
                                                <input type="text" name="lab_id" value="{{Auth::guard('web')->user()->lab_id}}" hidden>
                                                <input type="text" name="emp_id" value="{{Auth::guard('web')->user()->id}}" hidden>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Upload</button>
                    <button type="reset" class="btn btn-light-secondary">Reset</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Patient Modal -->
    <div class="modal fade" id="viewPatient">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> <b class="name">'s</b> information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row invoice-info">
                        <div class="col-sm-6 col-12 mt-1">
                            <div class="mb-1">
                                <span style="font-size: 18px;">Name :</span>
                                <p class="name"> </p>
                            </div>
                            <div class="mb-1">
                                <span style="font-size: 18px;">Email :</span>
                                <p id="email"> </p>
                            </div>
                            <div class="mb-1">
                                <span style="font-size: 18px;">Report :</span>
                                <iframe id="fileName" width="100%"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-success">View Tests</button> -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#modalBtn').on('click', function() {
                $('#exampleModal').modal({
                    show: true
                });
            })

            $('#imageUpload').on('click', function() {
                $('#imageUploadModal').modal({
                    show: true
                });
            })


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(document).on('click', '#btn', function() {
                let id = $(this).attr('value');
                $.ajax({
                    type: 'get',
                    url: "/patient/viewPatient/" + id,
                    data: {
                        _token: "{{  csrf_token() }}"
                    },
                    success: function(data) {
                        console.log(data);
                        $('#viewPatient').modal('show');
                        if (data.user.name == "" || data.user.name == null) {
                            $('.name').text("Name Not Found");
                        } else {
                            $('.name').text(data.user.name);
                        }
                        if (data.user.email == "" || data.user.email == null) {
                            $('#email').text("email Not Found");
                        } else {
                            $('#email').text(data.user.email);
                        }
                        if (data.user.fileName == "" || data.user.fileName == null) {
                            $('#fileName').attr('alt', "No Report");
                        } else {
                            // $('#fileName').attr('src', "http://157.175.136.92/public/images/profile/" + data.data.logo);
                            $('#fileName').attr('src', "/storage/uploads/" + data.user.fileName);
                        }
                    }
                })
            })
        })
    </script>

</div>
@endsection

<!-- gfufu -->