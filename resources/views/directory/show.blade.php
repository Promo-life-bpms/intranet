@extends('layouts.app')

@section('template_title')
    {{ $directory->name ?? 'Show Directory' }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Show Directory</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('directories.index') }}"> Back</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>User Id:</strong>
                            {{ $directory->user_id }}
                        </div>
                        <div class="form-group">
                            <strong>Type:</strong>
                            {{ $directory->type }}
                        </div>
                        <div class="form-group">
                            <strong>Data:</strong>
                            {{ $directory->data }}
                        </div>
                        <div class="form-group">
                            <strong>Company:</strong>
                            {{ $directory->company }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
