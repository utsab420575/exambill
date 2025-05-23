<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Grade Sheet (Provisional)</title>
    <style>
        @page {
            /*top right bottom left*/
            margin: 15mm 12mm 20mm 12mm;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
        }

        .header_table, .body_table_1, .footer_table_1 {
            width: 100%;
            border-collapse: collapse;
        }

        .header_table td {
            text-align: center;
            font-size: 13px;
        }

        .body_table_1 th, .body_table_1 td {
            border: 1px solid black;
            padding: 4px;
            text-align: center;
        }

        .footer_table_1 {
            margin-top: 50px;
            font-size: 12px;
        }

        .pt-20 { padding-top: 20px; }
        .pt-30 { padding-top: 30px; }
        .pt-40 { padding-top: 40px; }

        td.textstart{
            text-align: left;
        }
        td.textend{
            text-align: right;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
@php
    $global_sum=0;
@endphp
@foreach($teachers as  $teacher)

    {{-- Repeatable Header --}}
    <table class="header_table">
        <tr>
            <td style="width: 10%; text-align: left;">
                <img src="{{ public_path('images/logo_duet.png') }}" style="width: 50px;">
            </td>
            <td colspan="3">
                <strong>Dhaka University of Engineering & Technology, Gazipur</strong><br>
                Gazipur-1707
            </td>
        </tr>
        <tr>
            <td colspan="4" style="padding-top: 10px;padding-bottom: 10px;">
                <span style="margin-left: 40px;">(Examination Related Remuneration)</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: left;">B.Arch.</td>
            @php
                $ordinals = [1 => '1st', 2 => '2nd', 3 => '3rd', 4 => '4th', 5 => '5th'];
            @endphp
            <td style="text-align: left;">
                {{ $ordinals[$session_info->year] ?? $session_info->year . 'th' }} year
                {{ $ordinals[$session_info->semester] ?? $session_info->semester . 'th' }} semester
                <span style="float:right; font-weight: bold;">Regular</span>
            </td>
            <td>{{ $session_info->session }}</td>
            <td>(Held on: Sep, 2024)</td>
        </tr>
        <tr>
            <td colspan="2" class="pt-20"><strong>Name:</strong> {{ $teacher->user->name }}</td>
            <td class="pt-20"><strong>Designation:</strong> {{ $teacher->designation->name }}</td>
            <td class="pt-20"><strong>Department:</strong> ARCH, DUET</td>
        </tr>
        <tr>
            <td colspan="4" class="pt-30"><strong>Details of Examination Related Works</strong></td>
        </tr>
    </table>

    {{-- Body Table --}}
    <table class="body_table_1" style="margin-top: 10px;">
        <thead>
        <tr>
            <th>Sl. No.</th>
            <th colspan="2">Description of work</th>
            <th>Subject/Course</th>
            <th>Nos. of script/Students</th>
            <th>Rate</th>
            <th>Taka</th>
        </tr>
        </thead>
        <tbody>


        {{-- Order=1 --}}
        @php
            $assigns_order_1 = $teacher->rateAssigns->where('rateHead.order_no', '1');
            $total_taka = 0;
            $no_of_item = 0;

            if ($assigns_order_1->isNotEmpty()) {
                foreach ($assigns_order_1 as $assign) {
                    $global_sum += $assign->total_amount ?? 0;
                    $total_taka += $assign->total_amount ?? 0;
                    $no_of_item = $assign->no_of_item ?? 0;
                }
            }

            // Always show default RateHead and RateAmount
            $head = $rateHead_order_1->head ?? 'Moderation';
            $max_rate = $rateAmount_order_1->max_rate ?? ($rateAmount_order_1->default_rate ?? '');
            $min_rate = $rateAmount_order_1->min_rate ?? ($rateAmount_order_1->default_rate ?? '');
        @endphp

        <tr>
            <td rowspan="2">1</td>
            <td class="textstart" colspan="2" rowspan="2">{{ $head }}</td>
            <td rowspan="2"></td>
            <td rowspan="2">{{ $no_of_item == 0 ? '' : $no_of_item }}</td>
            <td class="textend">max. {{ $max_rate !== '' ? number_format($max_rate, 0) : '' }}</td>
            <td rowspan="2" class="textend">{{ $total_taka == 0 ? '' : number_format($total_taka, 2) }}</td>
        </tr>
        <tr>
            <td class="textend">min. {{ $min_rate !== '' ? number_format($min_rate, 0) : '' }}</td>
        </tr>



        {{-- Order = 2 --}}
        @php
            $assigns_order_2 = $teacher->rateAssigns->where('rateHead.order_no', '2');
            $total_assigns = $assigns_order_2->count();
            $loopIndex = 0;
        @endphp

        @foreach ($assigns_order_2 as $assign)
            @php
                $global_sum += $assign->total_amount ?? 0;
            @endphp
            <tr>
                @if ($loopIndex == 0)
                    <td rowspan="{{ $total_assigns }}">2</td>
                    <td class="textstart" colspan="2" rowspan="{{ $total_assigns }}">{{ $rateHead_order_2->head ?? 'Paper Setters' }}</td>
                @endif
                <td>course</td>
                <td></td>
                <td class="textend">{{ isset($assign->total_amount) ? number_format($assign->total_amount, 2) : '' }}</td>
                <td class="textend">{{ isset($assign->total_amount) ? number_format($assign->total_amount, 2) : '' }}</td>
            </tr>
            @php $loopIndex++; @endphp
        @endforeach


        {{-- Order = 3 --}}
        @php
            $assigns_order_3 = $teacher->rateAssigns->where('rateHead.order_no', '3');
            $total_assigns = $assigns_order_3->count();
            $loopIndex = 0;
        @endphp

        @foreach ($assigns_order_3 as $assign)
            @php
                $global_sum += $assign->total_amount ?? 0;
            @endphp
            <tr>
                @if ($loopIndex == 0)
                    <td rowspan="{{ $total_assigns }}">3</td>
                    <td class="textstart" colspan="2" rowspan="{{ $total_assigns }}">{{ $rateHead_order_3->head ?? 'Examiner' }}</td>
                @endif
                <td>course</td>
                <td>{{$assign->no_of_items}}</td>
                <td class="textend">{{ isset($assign->total_amount) ? number_format($assign->total_amount, 2) : '' }}</td>
                <td class="textend">{{ isset($assign->total_amount) ? number_format($assign->total_amount, 2) : '' }}</td>
            </tr>
            @php $loopIndex++; @endphp
        @endforeach


        {{-- Order = 4 --}}
        @php
            $assigns_order_4 = $teacher->rateAssigns->where('rateHead.order_no', '4');
            $total_assigns = $assigns_order_4->count();
            $loopIndex = 0;
        @endphp

        @foreach ($assigns_order_4 as $assign)
            @php
                $global_sum += $assign->total_amount ?? 0;
            @endphp
            <tr>
                @if ($loopIndex == 0)
                    <td rowspan="{{ $total_assigns }}">4</td>
                    <td class="textstart" colspan="2" rowspan="{{ $total_assigns }}">{{ $rateHead_order_4->head ?? 'Class Test' }}</td>
                @endif
                <td>course</td>
                <td>{{$assign->no_of_items}}</td>
                <td class="textend">{{ isset($assign->total_amount) ? number_format($assign->total_amount, 2) : '' }}</td>
                <td class="textend">{{ isset($assign->total_amount) ? number_format($assign->total_amount, 2) : '' }}</td>
            </tr>
            @php $loopIndex++; @endphp
        @endforeach




        </tbody>
    </table>

    {{-- Footer --}}
    <table class="footer_table_1">
        <tr>
            <td colspan="2" style="text-align: left;">---------------------------------------------------</td>
            <td colspan="2" style="text-align: right;">---------------------------------------------------</td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: left;">
                <span style="padding-left: 40px;">Countersigned<br></span>
                Chairman, Examination Committee
            </td>
            <td colspan="2" style="text-align: right;">
                <span style="padding-right: 30px;">Signature of Examiner and Date</span>
            </td>
        </tr>
        <tr>
            <td colspan="4" class="pt-20">---------------------------------------------------------------------------------------------------------------------------------------------</td>
        </tr>
        <tr>
            <td colspan="4">(For Comptroller office use only)</td>
        </tr>
        <tr>
            <td style="width: 20%;" class="pt-20">Taka ---<br>Received</td>
            <td style="width: 20%;" class="pt-20">------------ In words</td>
            <td style="width: 30%;" class="pt-20">--------------------------------------------------</td>
            <td style="width: 30%;" class="pt-20">--approved</td>
        </tr>
        <tr>
            <td class="pt-40">Signature of Examiner</td>
            <td class="pt-40">Prepared by</td>
            <td class="pt-40">Assistant Comptroller</td>
            <td class="pt-40">Comptroller (In Charge)</td>
        </tr>
    </table>

    @if (!$loop->last)
        <div class="page-break"></div>
    @endif

@endforeach

</body>
</html>
