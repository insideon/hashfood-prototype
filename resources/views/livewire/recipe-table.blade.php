<div>
    <style>
        /* 다크모드 테이블 스타일링 */
        .dark .table {
            background-color: #1f2937 !important;
            color: #f9fafb !important;
        }
        
        .dark .table thead th {
            background-color: #374151 !important;
            color: #f9fafb !important;
            border-color: #4b5563 !important;
        }
        
        .dark .table tbody td {
            background-color: #1f2937 !important;
            color: #f9fafb !important;
            border-color: #374151 !important;
        }
        
        .dark .table tbody tr:hover td {
            background-color: #374151 !important;
        }
        
        /* 다크모드 검색/필터 스타일 */
        .dark input[type="search"],
        .dark select {
            background-color: #374151 !important;
            color: #f9fafb !important;
            border-color: #4b5563 !important;
        }
        
        .dark input[type="search"]::placeholder {
            color: #9ca3af !important;
        }
        
        /* 다크모드 페이지네이션 */
        .dark .pagination a,
        .dark .pagination span {
            background-color: #374151 !important;
            color: #f9fafb !important;
            border-color: #4b5563 !important;
        }
        
        .dark .pagination a:hover {
            background-color: #4b5563 !important;
        }
        
        .dark .pagination .active span {
            background-color: #3b82f6 !important;
            color: white !important;
        }
    </style>
    
    {{ $this->table }}
</div>