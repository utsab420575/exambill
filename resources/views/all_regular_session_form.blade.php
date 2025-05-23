@extends('layouts.app')
@section('styles')
    <style>
        #table-list-of-examination-committee td {
            transition: background-color 0.6s ease-in-out, opacity 0.6s ease-in-out;
        }

        .fade-green {
            background-color: #68a17a !important;
            opacity: 1;
        }

        .fade-out {
            opacity: 0.3;
        }

    </style>
    @stack('styles')

@endsection
@section('content')
    <section role="main" class="content-body">
        <header class="page-header">
            <h2>Light Sidebar Layout</h2>
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

        @include('list_moderation_committe')
        @include('list_paper_setter_examineer')
        @include('list_class_test_teacher')
        @include('list_sessional_course_teacher')
        @include('list_scrutinizers')
        @include('list_preparation_theory_grade_sheet')
        @include('list_preparation_sessional_grade_sheet')
        @include('list_scrutinizing_theory_grade_sheet')
        @include('list_scrutinizing_sessional_grade_sheet')
        @include('list_prepared_computerized_result')
        @include('list_verified_computerized_result')
        @include('list_supervision_under_chairman_exam_committee')
        @include('list_advisor_student')
        @include('list_verified_final_graduation_result')
        @include('list_teachers_conducted_central_oral_exam')
        @include('list_involved_survey')
        @include('list_conducted_priliminary_viva')
        @include('list_examined_thesis_project')
        @include('list_conducted_oral_examination')
        @include('list_supervised_thesis_project')
        @include('list_honorarium_coordinator')
        @include('list_honorarium_chairman')


        <!-- end: page -->
    </section>

@endsection
<!-- Add Script Data(You can write it any javascript file and than just import this js) -->
<!-- this will be fire for any 'delete' class element[const target = event.target.closest('.delete');] -->
@push('scripts')

@endpush


