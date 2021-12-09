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
                <div class="accordion accordion-flush" id="accordionFlushExample">
                  <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingOne">
                      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                        Expired Documents
                      </button>
                    </h2>
                    <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                      <div class="accordion-body">
                        Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the first item's accordion body.
                      </div>
                    </div>
                  </div>
                </div>
            <p>
                @foreach ($files as $key => $file)
                    <?php
                        $date_exp = $file->doc_date_exp;
                        $orderdate=$date_exp;
                        $orderdate = explode('-', $orderdate);
                        $day   = $orderdate[2];
                        $month = $orderdate[1];
                        $year  = $orderdate[0];

                        $date_exp = Carbon\Carbon::create($year, $month, $day, 0);
                        // expire date
                        // echo $date_exp."<br/>"; 
                        // 1 month to expired
                        $date_exp_2mo = $date_exp->subMonth(2);
                        // echo $date_exp_2mo."<br/>"; 
                        // if curr_date >= 2-mo-to-exp && curr_date <= date_exp
                        // if (($current_date >= $date_exp_2mo) && ($current_date <= $date_exp)) {
                        if (($current_date >= $date_exp_2mo) || ($current_date >= $date_exp)) {
                            echo $file->doc_name;
                            echo " masuk ke masa tenggang expired di ".$date_exp."<br/>";
                        } 
                    ?>
                @endforeach
            </p>
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
