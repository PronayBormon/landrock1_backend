<!-- Theme Config Js -->
<script src="{{ asset('backend/assets/js/config.js') }}"></script>

<!-- Vendor css -->
<link href="{{ asset('backend/assets/css/vendor.min.css') }}"
    rel="stylesheet"
    type="text/css" />

<!-- App css -->
<link href="{{ asset('backend/assets/css/app.min.css') }}"
    rel="stylesheet"
    type="text/css"
    id="app-style" />

<!-- Icons css -->
<link href="{{ asset('backend/assets/css/icons.min.css') }}"
    rel="stylesheet"
    type="text/css" />
<!-- <link href="https://cdn.datatables.net/2.3.6/css/dataTables.dataTables.min.css"
    rel="stylesheet"
    type="text/css" /> -->
<!-- Sweet Alert css-->
<link href="/backend/assets/vendor/sweetalert2/sweetalert2.min.css"
    rel="stylesheet"
    type="text/css" />
<!-- DataTables CSS -->
<!-- <link rel="stylesheet"
    href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css"> -->
<link rel="stylesheet"
    href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

<style>
    /* ===== TOP SECTION FIX ===== */
    .dataTables_wrapper .row:first-child {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
        /* remove grey */
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 15px;
        border: 1px solid #eee;
    }

    /* Remove bootstrap column weird stacking */
    .dataTables_wrapper .row:first-child>div {
        width: auto !important;
        padding: 0 !important;
    }

    /* Length (left) */
    .dataTables_length {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Search (right) */
    .dataTables_filter {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Search input fix */
    .dataTables_filter input {
        width: 200px;
    }


    /* ===== BOTTOM SECTION FIX ===== */
    .dataTables_wrapper .row:last-child {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
        padding: 12px 16px;
        border-radius: 10px;
        margin-top: 15px;
        border: 1px solid #eee;
    }

    /* Remove bootstrap column issues */
    .dataTables_wrapper .row:last-child>div {
        width: auto !important;
        padding: 0 !important;
    }

    /* Info text (left) */
    .dataTables_info {
        font-size: 13px;
    }

    /* Pagination (right) */
    .dataTables_paginate {
        margin: 0;
    }
</style>

@stack('styles')
