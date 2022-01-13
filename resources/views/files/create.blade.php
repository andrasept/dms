@extends('layouts.app-master')

@section('content')
    <div class="bg-light p-4 rounded">
        <h2>Upload Documents</h2>
        <div class="lead">
            <!-- Add new Document. -->
        </div>

        <div class="container mt-4">

            <form action="{{ route('files.store') }}" method="post" enctype="multipart/form-data">
                @include('layouts.partials.messages')
                @csrf
                
                @role("admin")
                <div class="mb-3">
                    <label for="dept_id" class="form-label">Department</label>
                    <select class="form-control" id="dept_id" name="dept_id" required="required" >
                        <option value="15" selected="selected"> All Department </option>
                        @foreach ($departments as $dept)
                            <option value="{{ $dept->id }}" >{{ $dept->name}}</option>
                        @endforeach
                    </select>

                    @if ($errors->has('dept_id'))
                        <span class="text-danger text-left">{{ $errors->first('dept_id') }}</span>
                    @endif
                </div>
                @endrole

                @role("HRGA")
                    <div class="mb-3">
                        <label for="dept_id" class="form-label">Department</label>
                        <select class="form-control" id="dept_id" name="dept_id" required="required" >
                            <option value="6" selected="selected"> HRGA EHS EXIM IT </option>
                            <option value="15" selected="selected"> All Department </option>
                        </select>
                        @if ($errors->has('dept_id'))
                            <span class="text-danger text-left">{{ $errors->first('dept_id') }}</span>
                        @endif
                    </div>
                @endrole

                <div class="mb-3">
                    <label for="doc_number" class="form-label">Nomor Dokumen</label>
                    <input value="{{ old('doc_number') }}" 
                        type="text" 
                        class="form-control" 
                        name="doc_number" 
                        placeholder="format-nomor-surat-001" required>

                    @if ($errors->has('doc_number'))
                        <span class="text-danger text-left">{{ $errors->first('doc_number') }}</span>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="doc_name" class="form-label">Nama Dokumen</label>
                    <input value="{{ old('doc_name') }}" 
                        type="text" 
                        class="form-control" 
                        name="doc_name" 
                        placeholder="Nama atau Judul Dokumen" required>

                    @if ($errors->has('doc_name'))
                        <span class="text-danger text-left">{{ $errors->first('doc_name') }}</span>
                    @endif
                </div>

                <div class="mb-3">
                    <label for="doc_date" class="form-label">Tanggal Dokumen</label>
                    <input value="{{ old('doc_date') }}" 
                        type="date" 
                        class="form-control" 
                        name="doc_date" 
                        placeholder="Tanggal Dokumen">

                    @if ($errors->has('doc_date'))
                        <span class="text-danger text-left">{{ $errors->first('doc_date') }}</span>
                    @endif
                </div>   

                <div class="mb-3">
                    <label for="doc_date_exp" class="form-label">Tanggal Expired Dokumen</label>
                    <input value="{{ old('doc_date_exp') }}" 
                        type="date" 
                        class="form-control" 
                        name="doc_date_exp" 
                        placeholder="Tanggal Dokumen Expired">

                    @if ($errors->has('doc_date_exp'))
                        <span class="text-danger text-left">{{ $errors->first('doc_date_exp') }}</span>
                    @endif
                </div>     

                <div class="mb-3">
                    <label for="doc_note" class="form-label">Notes</label>
                    <textarea class="form-control" 
                        name="doc_note"
                        rows="3" 
                        placeholder="Note atau Keterangan">
                        {{ old('doc_note') }}
                    </textarea>

                    @if ($errors->has('doc_note'))
                        <span class="text-danger text-left">{{ $errors->first('doc_note') }}</span>
                    @endif
                </div>  

                <div class="mb-3">
                    <label for="file" class="form-label">Upload Dokumen</label>
                    <input type="file" name="file" class="form-control" accept=".jpg,.jpeg,.bmp,.png,.gif,.doc,.docx,.csv,.rtf,.xlsx,.xls,.txt,.pdf,.zip">
                </div>
                <br/><br/>       

                <button type="submit" class="btn btn-primary">Save</button>
                <a href="{{ route('files.index') }}" class="btn btn-default">Back</a>
            </form>
        </div>

    </div>
@endsection