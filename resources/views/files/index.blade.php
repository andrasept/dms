@extends('layouts.app-master')

@section('content')

    <div class="bg-light p-4 rounded">
        <h2>Upload Documents</h2>
        <div class="lead">
            <br/>
            Documents management.
            <br/>
            BUAT NOTIF REMINDER DISINI<br/>
            buat 2 bulan sebelum date_exp, tampilkan terus sampai lewat dari date_exp<br/>
            tampilkan list foreach, lalu buatkan modal window atau accordion, atau open link new tab untuk menampilkan semua dokumen yg exp
            <br/>
            <p></p>
            <a href="{{ route('files.create') }}" class="btn btn-primary btn-sm float-right">Add Document</a>
            <br/><br/>
        </div>
        
        <div class="mt-2">
            @include('layouts.partials.messages')
        </div>

        <table id="datatable" class="display">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Dept.</th>
                    <th>Name</th>
                    <th>Tanggal</th>
                    <th>Tanggal Exp.</th>
                    <th>Notes</th>
                    <!-- <th>Size</th> -->
                    <th>Type</th>
                    <th>Upload By</th>
                    <th>Upload At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($files as $key => $file)
                <tr>
                    <td>{{ $loop->iteration + (($files->currentPage() -1) * $files->perPage())  }}</td>
                    <td>
                      @foreach ($departments as $key2 => $dept)
                        @if ($file->dept_id == $dept->id)
                          {{ $dept->name }}
                        @endif
                      @endforeach
                    </td>
                    <td>{{ $file->doc_name }}</td>
                    <td>{{ $file->doc_date }}</td>
                    <td>{{ $file->doc_date_exp }}</td>
                    <td>{{ $file->doc_note }}</td>
                    <!-- <td>{{ $file->doc_size }}</td> -->
                    <td>{{ $file->doc_type }}</td>
                    <td>
                      @foreach ($users as $key3 => $user)
                        @if ($file->created_by == $user->id)
                          {{ $user->name }}
                        @endif
                      @endforeach
                    </td>
                    <td>{{ $file->created_at }}</td>
                    <td>
                        <a href="{{url('')}}/file/{{ $file->doc_name }}" target="_newtab">Download</a>
                        <br/><br/><br/>
                        {!! Form::open(['method' => 'DELETE','route' => ['files.destroy', $file->id],'style'=>'display:inline']) !!}
                        {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm', 'onclick' => 'return confirm("Are you sure?")']) !!}
                        {!! Form::close() !!}
                    </td>
                </tr>
                @endforeach
            </tbody>         
        </table>

        <div class="d-flex">
            {!! $files->links() !!}
        </div>

    </div>
@endsection

@push('scripts')
<script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
  $(document).ready(function() {
      $('#datatable').DataTable({
        // "pageLength": 2
      });
  } );
</script>
@endpush
