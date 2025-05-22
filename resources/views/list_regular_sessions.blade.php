@extends('layouts.app')
@section('styles')



@endsection
@section('content')
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>All Regular Session</h2>
            <div class="right-wrapper text-end">
                <ol class="breadcrumbs">
                    <li>
                        <a href="index.html">
                            <i class="bx bx-home-alt"></i>
                        </a>
                    </li>
                    <li><span>Layouts</span></li>
                    <li><span>Light Sidebar</span></li>
                </ol>
                <a class="sidebar-right-toggle" data-open="sidebar-right"><i class="fas fa-chevron-left"></i></a>
            </div>
        </header>
        <!-- start: page -->


        <!-- end: page -->
    </section>

@endsection
<!-- Add Script Data(You can write it any javascript file and than just import this js) -->
<!-- this will be fire for any 'delete' class element[const target = event.target.closest('.delete');] -->
@push('scripts')

@endpush


