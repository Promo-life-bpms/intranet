@extends('layouts.app')

@section('content')
    <div class="card-header">
        <div class="d-flex justify-content-between">
            <h3>Directorio de vacaciones</h3>

            <a style="margin-left: 20px;" href=" {{ route('admin.vacations.export') }} " type="button"
                class="btn btn btn-success">Exportar Excel</a>
        </div>
    </div>
    <div class="card-body">
        @php
            $randomKey = time();
        @endphp
        @livewire('vacations.vacations', [], key($randomKey))
    </div>
@stop

@section('styles')
    <style>
        .tab {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
        }

        /* Style the buttons inside the tab */
        .tab button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
            font-size: 17px;
        }

        /* Change background color of buttons on hover */
        .tab button:hover {
            background-color: #ddd;
        }

        /* Create an active/current tablink class */
        .tab button.active {
            background-color: #ccc;
        }

        /* Style the tab content */
        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
        }


        table.dataTable {
            width: 100%;
            margin: 0 auto;
            clear: both;
            border-collapse: separate;
            border-spacing: 0;
        }

        table.dataTable thead th,
        table.dataTable tfoot th {
            font-weight: bold;
        }

        table.dataTable thead th,
        table.dataTable thead td {
            padding: 10px 18px;
            border-bottom: 1px solid #ebebeb;
        }

        table.dataTable thead th:active,
        table.dataTable thead td:active {
            outline: none;
        }

        table.dataTable tfoot th,
        table.dataTable tfoot td {
            padding: 10px 18px 6px 18px;
            border-top: 1px solid #ebebeb;
        }

        table.dataTable thead .sorting,
        table.dataTable thead .sorting_asc,
        table.dataTable thead .sorting_desc,
        table.dataTable thead .sorting_asc_disabled,
        table.dataTable thead .sorting_desc_disabled {
            cursor: pointer;
            background-repeat: no-repeat;
            background-position: center right;
        }

        table.dataTable tbody tr {
            background-color: #ffffff;
        }

        table.dataTable tbody tr.selected {
            background-color: #b0bed9;
        }

        table.dataTable tbody th,
        table.dataTable tbody td {
            padding: 8px 10px;
        }

        table.dataTable.row-border tbody th,
        table.dataTable.row-border tbody td,
        table.dataTable.display tbody th,
        table.dataTable.display tbody td {
            border-top: 1px solid #dddddd;
        }

        table.dataTable.row-border tbody tr:first-child th,
        table.dataTable.row-border tbody tr:first-child td,
        table.dataTable.display tbody tr:first-child th,
        table.dataTable.display tbody tr:first-child td {
            border-top: none;
        }

        table.dataTable.cell-border tbody th,
        table.dataTable.cell-border tbody td {
            border-top: 1px solid #dddddd;
            border-right: 1px solid #dddddd;
        }

        table.dataTable.cell-border tbody tr th:first-child,
        table.dataTable.cell-border tbody tr td:first-child {
            border-left: 1px solid #dddddd;
        }

        table.dataTable.cell-border tbody tr:first-child th,
        table.dataTable.cell-border tbody tr:first-child td {
            border-top: none;
        }

        table.dataTable.stripe tbody tr.odd,
        table.dataTable.display tbody tr.odd {
            background-color: #f9f9f9;
        }

        table.dataTable.stripe tbody tr.odd.selected,
        table.dataTable.display tbody tr.odd.selected {
            background-color: #acbad4;
        }

        table.dataTable.hover tbody tr:hover,
        table.dataTable.display tbody tr:hover {
            background-color: #f6f6f6;
        }

        table.dataTable.hover tbody tr:hover.selected,
        table.dataTable.display tbody tr:hover.selected {
            background-color: #aab7d1;
        }

        table.dataTable.order-column tbody tr>.sorting_1,
        table.dataTable.order-column tbody tr>.sorting_2,
        table.dataTable.order-column tbody tr>.sorting_3,
        table.dataTable.display tbody tr>.sorting_1,
        table.dataTable.display tbody tr>.sorting_2,
        table.dataTable.display tbody tr>.sorting_3 {
            background-color: #fafafa;
        }

        table.dataTable.order-column tbody tr.selected>.sorting_1,
        table.dataTable.order-column tbody tr.selected>.sorting_2,
        table.dataTable.order-column tbody tr.selected>.sorting_3,
        table.dataTable.display tbody tr.selected>.sorting_1,
        table.dataTable.display tbody tr.selected>.sorting_2,
        table.dataTable.display tbody tr.selected>.sorting_3 {
            background-color: #acbad5;
        }

        table.dataTable.display tbody tr.odd>.sorting_1,
        table.dataTable.order-column.stripe tbody tr.odd>.sorting_1 {
            background-color: #f1f1f1;
        }

        table.dataTable.display tbody tr.odd>.sorting_2,
        table.dataTable.order-column.stripe tbody tr.odd>.sorting_2 {
            background-color: #f3f3f3;
        }

        table.dataTable.display tbody tr.odd>.sorting_3,
        table.dataTable.order-column.stripe tbody tr.odd>.sorting_3 {
            background-color: whitesmoke;
        }

        table.dataTable.display tbody tr.odd.selected>.sorting_1,
        table.dataTable.order-column.stripe tbody tr.odd.selected>.sorting_1 {
            background-color: #a6b4cd;
        }

        table.dataTable.display tbody tr.odd.selected>.sorting_2,
        table.dataTable.order-column.stripe tbody tr.odd.selected>.sorting_2 {
            background-color: #a8b5cf;
        }

        table.dataTable.display tbody tr.odd.selected>.sorting_3,
        table.dataTable.order-column.stripe tbody tr.odd.selected>.sorting_3 {
            background-color: #a9b7d1;
        }

        table.dataTable.display tbody tr.even>.sorting_1,
        table.dataTable.order-column.stripe tbody tr.even>.sorting_1 {
            background-color: #fafafa;
        }

        table.dataTable.display tbody tr.even>.sorting_2,
        table.dataTable.order-column.stripe tbody tr.even>.sorting_2 {
            background-color: #fcfcfc;
        }

        table.dataTable.display tbody tr.even>.sorting_3,
        table.dataTable.order-column.stripe tbody tr.even>.sorting_3 {
            background-color: #fefefe;
        }

        table.dataTable.display tbody tr.even.selected>.sorting_1,
        table.dataTable.order-column.stripe tbody tr.even.selected>.sorting_1 {
            background-color: #acbad5;
        }

        table.dataTable.display tbody tr.even.selected>.sorting_2,
        table.dataTable.order-column.stripe tbody tr.even.selected>.sorting_2 {
            background-color: #aebcd6;
        }

        table.dataTable.display tbody tr.even.selected>.sorting_3,
        table.dataTable.order-column.stripe tbody tr.even.selected>.sorting_3 {
            background-color: #afbdd8;
        }

        table.dataTable.display tbody tr:hover>.sorting_1,
        table.dataTable.order-column.hover tbody tr:hover>.sorting_1 {
            background-color: #eaeaea;
        }

        table.dataTable.display tbody tr:hover>.sorting_2,
        table.dataTable.order-column.hover tbody tr:hover>.sorting_2 {
            background-color: #ececec;
        }

        table.dataTable.display tbody tr:hover>.sorting_3,
        table.dataTable.order-column.hover tbody tr:hover>.sorting_3 {
            background-color: #efefef;
        }

        table.dataTable.display tbody tr:hover.selected>.sorting_1,
        table.dataTable.order-column.hover tbody tr:hover.selected>.sorting_1 {
            background-color: #a2aec7;
        }

        table.dataTable.display tbody tr:hover.selected>.sorting_2,
        table.dataTable.order-column.hover tbody tr:hover.selected>.sorting_2 {
            background-color: #a3b0c9;
        }

        table.dataTable.display tbody tr:hover.selected>.sorting_3,
        table.dataTable.order-column.hover tbody tr:hover.selected>.sorting_3 {
            background-color: #a5b2cb;
        }

        table.dataTable.no-footer {
            border-bottom: 1px solid #ebebeb;
        }

        table.dataTable.nowrap th,
        table.dataTable.nowrap td {
            white-space: nowrap;
        }

        table.dataTable.compact thead th,
        table.dataTable.compact thead td {
            padding: 4px 17px;
        }

        table.dataTable.compact tfoot th,
        table.dataTable.compact tfoot td {
            padding: 4px;
        }

        table.dataTable.compact tbody th,
        table.dataTable.compact tbody td {
            padding: 4px;
        }

        table.dataTable th,
        table.dataTable td {
            box-sizing: content-box;
        }

        .dataTables_wrapper {
            position: relative;
            clear: both;
        }

        .dataTables_wrapper .dataTables_length {
            float: left;
        }

        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #aaa;
            border-radius: 3px;
            padding: 5px;
            background-color: transparent;
            padding: 4px;
        }

        .dataTables_wrapper .dataTables_filter {
            float: right;
            text-align: right;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #aaa;
            border-radius: 3px;
            padding: 5px;
            background-color: transparent;
            margin-left: 3px;
        }

        .dataTables_wrapper .dataTables_info {
            clear: both;
            float: left;
            padding-top: 0.755em;
        }

        .dataTables_wrapper .dataTables_paginate {
            float: right;
            text-align: right;
            padding-top: 0.25em;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            box-sizing: border-box;
            display: inline-block;
            min-width: 1.5em;
            padding: 0.5em 1em;
            margin-left: 2px;
            text-align: center;
            text-decoration: none !important;
            cursor: pointer;
            *cursor: hand;
            color: #000000 !important;
            border: 1px solid transparent;
            border-radius: 2px;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            color: #FFFFFF !important;
            background-color: #006EAD;
            border-radius: 5px;

        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active {
            cursor: default;
            color: #666 !important;
            border: 1px solid transparent;
            background: transparent;
            box-shadow: none;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            color: rgb(0, 0, 0) !important;
            border: 1px solid #ffffff;
            background-color: white;

        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:active {
            outline: none;
            background-color: white;
            box-shadow: inset 0 0 3px #111;
        }

        .dataTables_wrapper .dataTables_paginate .ellipsis {
            padding: 0 1em;
        }

        .dataTables_wrapper .dataTables_processing {
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100%;
            height: 40px;
            margin-left: -50%;
            margin-top: -25px;
            padding-top: 20px;
            text-align: center;
            font-size: 1.2em;
            background-color: white;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_processing,
        .dataTables_wrapper .dataTables_paginate {
            color: #000000;
        }

        .dataTables_wrapper .dataTables_scroll {
            clear: both;
        }

        .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody {
            *margin-top: -1px;
            -webkit-overflow-scrolling: touch;
        }

        .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>thead>tr>th,
        .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>thead>tr>td,
        .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>tbody>tr>th,
        .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>tbody>tr>td {
            vertical-align: middle;
        }

        .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>thead>tr>th>div.dataTables_sizing,
        .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>thead>tr>td>div.dataTables_sizing,
        .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>tbody>tr>th>div.dataTables_sizing,
        .dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>tbody>tr>td>div.dataTables_sizing {
            height: 0;
            overflow: hidden;
            margin: 0 !important;
            padding: 0 !important;
        }

        .dataTables_wrapper.no-footer .dataTables_scrollBody {
            border-bottom: 1px solid #ebebeb;
        }

        .dataTables_wrapper.no-footer div.dataTables_scrollHead table.dataTable,
        .dataTables_wrapper.no-footer div.dataTables_scrollBody>table {
            border-bottom: none;
        }

        .dataTables_wrapper:after {
            visibility: hidden;
            display: block;
            content: "";
            clear: both;
            height: 0;
        }
    </style>
@stop


@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.js"></script>

@stop
