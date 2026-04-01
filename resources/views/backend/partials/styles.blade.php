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
    /* ===== DT LAYOUT ROW (BOTTOM) ===== */
    .dt-layout-row {
        display: flex !important;
        justify-content: space-between;
        align-items: center;
        background: #fff;
        margin: 10px 0px;
    }

    .dt-layout-row.dt-layout-table {
        display: unset !important;
        justify-content: space-between;
        align-items: center;
        background: #fff;
    }

    /* Left side (info text) */
    .dt-layout-start {
        display: flex;
        align-items: center;
    }

    .dt-info {
        font-size: 13px;
        color: #6c757d;
    }

    /* Right side (pagination) */
    .dt-layout-end {
        display: flex;
        align-items: center;
    }

    /* Pagination container */
    .dt-paging nav {
        display: flex;
        gap: 6px;
    }

    /* Buttons */
    .dt-paging-button {
        border: none;
        padding: 6px 12px;
        border-radius: 8px;
        background: #f1f5ff;
        color: #0d6efd;
        font-weight: 500;
        cursor: pointer;
        transition: 0.2s;
    }

    /* Hover */
    .dt-paging-button:hover {
        background: #0d6efd;
        color: #fff;
    }

    /* Active */
    .dt-paging-button.current {
        background: #0d6efd;
        color: #fff;
        box-shadow: 0 2px 6px rgba(13, 110, 253, 0.3);
    }

    /* Disabled */
    .dt-paging-button.disabled {
        background: #e9ecef;
        color: #adb5bd;
        cursor: not-allowed;
    }
</style>

@stack('styles')
