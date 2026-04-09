<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title ?? 'Documento PDF' }}</title>
    <style>
        @page {
            margin: 18px 20px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1f2937;
        }

        h1, h2, h3, h4, h5, h6, p {
            margin: 0;
        }

        .document-header {
            margin-bottom: 16px;
        }

        .document-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .document-subtitle {
            font-size: 11px;
            color: #4b5563;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            margin: 18px 0 8px;
        }

        .meta {
            margin-bottom: 12px;
        }

        .meta-row {
            margin-bottom: 4px;
        }

        .alert-warning {
            border: 1px solid #f59e0b;
            background: #fffbeb;
            padding: 10px 12px;
            margin-bottom: 12px;
        }

        .alert-warning ul {
            margin: 8px 0 0 18px;
            padding: 0;
        }

        .grid-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
            margin-bottom: 14px;
        }

        .grid-table th,
        .grid-table td {
            border: 1px solid #9ca3af;
            padding: 6px;
            vertical-align: middle;
        }

        .grid-table thead th {
            background: #e5e7eb;
            text-align: center;
            font-weight: bold;
        }

        .time-cell {
            width: 10%;
            text-align: center;
            background: #f3f4f6;
            font-size: 10px;
            font-weight: bold;
        }

        .recreo-row th,
        .recreo-row td {
            background: #f3f4f6;
            color: #4b5563;
            font-weight: bold;
            text-align: center;
        }

        .muted {
            color: #6b7280;
        }

        .cell-block {
            margin-bottom: 6px;
            padding: 4px;
            border: 1px solid #d1d5db;
            background: #f9fafb;
        }

        .cell-block:last-child {
            margin-bottom: 0;
        }

        .small {
            font-size: 10px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-end {
            text-align: right;
        }

        .text-muted {
            color: #6b7280;
        }

        .w-100 {
            width: 100%;
        }

        .mt-4 {
            margin-top: 16px;
        }

        .pt-3 {
            padding-top: 12px;
        }

        .border-top {
            border-top: 1px solid #d1d5db;
        }

        .card {
            border: 1px solid #d1d5db;
        }

        .card-header {
            padding: 12px 14px;
            background: #f3f4f6;
            border-bottom: 1px solid #d1d5db;
        }

        .card-body {
            padding: 14px;
        }

        .d-flex {
            width: 100%;
        }

        .d-flex p {
            display: inline-block;
            width: 48%;
            vertical-align: top;
        }

        .justify-content-between p:first-child {
            text-align: left;
        }

        .justify-content-between p:last-child {
            text-align: right;
        }

        .signature-table {
            width: 100%;
            margin-top: 24px;
        }

        .signature-table td {
            width: 50%;
            vertical-align: top;
        }

        .html-content p {
            margin-bottom: 10px;
            text-align: justify;
            line-height: 1.45;
        }
    </style>
    @yield('styles')
</head>
<body>
    @yield('content')
    @yield('footer')
</body>
</html>
