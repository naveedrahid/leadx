<!doctype html>
<html lang="en">
  <head>
    <title>{{ config('app.name') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        .page-break {
            page-break-after: always;
        }

        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .bg-secondary {
            background-color: #f5f5f5; 
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid #eceeef;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid #eceeef;
        }

        .table tbody + tbody {
            border-top: 2px solid #eceeef;
        }

        .table .table {
            background-color: #fff;
        }

        .table-sm th,
        .table-sm td {
            padding: 0.3rem;
        }

        .table-bordered {
            border: 1px solid #eceeef;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #eceeef;
        }

        .table-bordered thead th,
        .table-bordered thead td {
            border-bottom-width: 2px;
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .table-active,
        .table-active > th,
        .table-active > td {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .table-hover .table-active:hover {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .table-hover .table-active:hover > td,
        .table-hover .table-active:hover > th {
            background-color: rgba(0, 0, 0, 0.075);
        }

        .table-success,
        .table-success > th,
        .table-success > td {
            background-color: #dff0d8;
        }

        .table-hover .table-success:hover {
            background-color: #d0e9c6;
        }

        .table-hover .table-success:hover > td,
        .table-hover .table-success:hover > th {
            background-color: #d0e9c6;
        }

        .table-info,
        .table-info > th,
        .table-info > td {
            background-color: #d9edf7;
        }

        .table-hover .table-info:hover {
            background-color: #c4e3f3;
        }

        .table-hover .table-info:hover > td,
        .table-hover .table-info:hover > th {
            background-color: #c4e3f3;
        }

        .table-warning,
        .table-warning > th,
        .table-warning > td {
            background-color: #fcf8e3;
        }

        .table-hover .table-warning:hover {
            background-color: #faf2cc;
        }

        .table-hover .table-warning:hover > td,
        .table-hover .table-warning:hover > th {
            background-color: #faf2cc;
        }

        .table-danger,
        .table-danger > th,
        .table-danger > td {
            background-color: #f2dede;
        }

        .table-hover .table-danger:hover {
            background-color: #ebcccc;
        }

        .table-hover .table-danger:hover > td,
        .table-hover .table-danger:hover > th {
            background-color: #ebcccc;
        }

        .thead-inverse th {
            color: #fff;
            background-color: #292b2c;
        }

        .thead-default th {
            color: #464a4c;
            background-color: #eceeef;
        }

        .table-inverse {
            color: #fff;
            background-color: #292b2c;
        }

        .table-inverse th,
        .table-inverse td,
        .table-inverse thead th {
            border-color: #fff;
        }

        .table-inverse.table-bordered {
            border: 0;
        }

        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -ms-overflow-style: -ms-autohiding-scrollbar;
        }

        .table-responsive.table-bordered {
            border: 0;
        }
    </style>
  </head>
  <body>
    @foreach($leads as $index => $lead)
        @php
            $form_data = json_decode($lead->form_data);
        @endphp
        <h2 class="text-center"><strong>Lead ID </strong> #{{ $lead->id > 9 ? $lead->id : '0'. $lead->id }}</h2>
        <br/>
        <table class="table table-bordered" cellspacing="0">
            <tr class="bg-secondary">
                <th colspan="2" cass="text-center"><h3 style="margin: 0">Lead Details</h3></th>
            </tr>
            <tr>
                <th class="text-left">Form Name</th>
                <td>{{ $lead->wpform_name }}</td>
            </tr>
            <tr>
                <th class="text-left">Lead Status</th>
                <td>{{ leadStatus($lead->status) }}</td>
            </tr>
            <tr>
                <th class="text-left">Submitted on</th>
                <td>{{ $lead->created_at->format('F j, Y') }}</td>
            </tr>
            <tr class="bg-secondary">
                <th colspan="2" cass="text-center"><h3 style="margin: 0">User Information</h3></th>
            </tr>
            <tr>
                <th class="text-left">IP Address</th>
                <td>{{ $form_data->visitor_info->ip }}</td>
            </tr>
            <tr>
                <th class="text-left">Platform</th>
                <td>{{ $form_data->visitor_info->platform }}</td>
            </tr>
            <tr>
                <th class="text-left">Browser/OS</th>
                <td>{{ $form_data->visitor_info->browser }}</td>
            </tr>
            <tr>
                <th class="text-left">Referrer URL</th>
                <td>{{ $form_data->visitor_info->ref_url }}</td>
            </tr>
            <tr>
                <th class="text-left">Continent</th>
                <td>{{ ($form_data->visitor_info->continent !== '' && $form_data->visitor_info->continent !== 'unknown') ? $form_data->visitor_info->continent : 'Not Available' }}</td>
            </tr>
            <tr>
                <th class="text-left">Country</th>
                <td>{{ ($form_data->visitor_info->country !== '' && $form_data->visitor_info->country !== 'unknown') ? $form_data->visitor_info->country : 'Not Available' }}</td>
            </tr>
            <tr>
                <th class="text-left">Country Code</th>
                <td>{{ ($form_data->visitor_info->country_code !== '' && $form_data->visitor_info->country_code !== 'unknown') ? $form_data->visitor_info->country_code : 'Not Available' }}</td>
            </tr>
            <tr>
                <th class="text-left">State</th>
                <td>{{ ($form_data->visitor_info->state !== '' && $form_data->visitor_info->state !== 'unknown') ? $form_data->visitor_info->state : 'Not Available' }}</td>
            </tr>
            <tr>
                <th class="text-left">City</th>
                <td>{{ ($form_data->visitor_info->city !== '' && $form_data->visitor_info->city !== 'unknown') ? $form_data->visitor_info->city : 'Not Available' }}</td>
            </tr>
            <tr class="bg-secondary">
                <th colspan="2" cass="text-center"><h3 style="margin: 0">Form Lead Details</h3></th>
            </tr>
            @if($form_data->data)
                @foreach($form_data->data as $field => $item)
                    @if($field == 'checkbox-list')
                        @foreach($item as $key => $checkbox_list)
                            @php 
                                $checkboxValue = implode(', ', (array) $checkbox_list);
                            @endphp
                            <tr>
                                <th class="text-left">{{ formatText($key) }}</th>
                                <td>
                                    {{ $checkboxValue }}
                                </td>
                            </tr>
                        @endforeach
                    @elseif($field == 'file')
                        @foreach($item as $key => $file)
                            <tr>
                                <th class="text-left">{{ formatText($key) }}</th>
                                <td>{{ $file->url }}</td>
                            </tr>
                        @endforeach
                    @else
                        @foreach($item as $key => $value)
                            <tr>
                                <th class="text-left">{{ formatText($key) }}</th>
                                <td>{{ $value }}</td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            @endif
        </table>
        <br/>
        <br/>

        {{-- <div class="page-break"></div> --}}
    @endforeach
  </body>
</html>