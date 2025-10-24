<div>
    <style>
        /* 테이블 도구 모음 다크모드 스타일링 */
        .dark .rounded-md.shadow-sm input[type="search"] {
            background-color: #374151 !important;
            color: #f9fafb !important;
            border-color: #4b5563 !important;
        }
        
        .dark .rounded-md.shadow-sm input[type="search"]::placeholder {
            color: #9ca3af !important;
        }
        
        .dark .rounded-md.shadow-sm input[type="search"]:focus {
            border-color: #6366f1 !important;
            ring-color: #6366f1 !important;
        }
        
        /* 페이지네이션 선택 드롭다운 */
        .dark select {
            background-color: #374151 !important;
            color: #f9fafb !important;
            border-color: #4b5563 !important;
        }
        
        .dark select:focus {
            border-color: #6366f1 !important;
            ring-color: #6366f1 !important;
        }
        
        /* 컬럼 선택 버튼 */
        .dark .inline-flex.justify-center.px-4.py-2 {
            background-color: #374151 !important;
            color: #f9fafb !important;
            border-color: #4b5563 !important;
        }
        
        .dark .inline-flex.justify-center.px-4.py-2:hover {
            background-color: #4b5563 !important;
        }
        
        /* 컬럼 선택 드롭다운 메뉴 */
        .dark .bg-white.rounded-md.shadow-xs {
            background-color: #374151 !important;
            color: #f9fafb !important;
        }
        
        /* 컬럼 선택 체크박스 */
        .dark input[type="checkbox"] {
            background-color: #1f2937 !important;
            border-color: #4b5563 !important;
        }
        
        .dark input[type="checkbox"]:checked {
            background-color: #6366f1 !important;
            border-color: #6366f1 !important;
        }
        
        /* 정렬 필터 */
        .dark .inline-flex.items-center.px-2\.5.py-0\.5 {
            background-color: #374151 !important;
            color: #f9fafb !important;
        }
        
        .dark .bg-indigo-100.text-indigo-800 {
            background-color: #4c1d95 !important;
            color: #e0e7ff !important;
        }
        
        /* Clear 버튼 */
        .dark .bg-gray-100.text-gray-800 {
            background-color: #374151 !important;
            color: #f9fafb !important;
        }
        
        /* 페이지네이션 */
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
            background-color: #6366f1 !important;
            color: white !important;
        }
        
        /* 도구 모음 전체 배경 */
        .dark .md\\:flex.md\\:justify-between {
            background-color: transparent !important;
        }
        
        /* 검색 입력 필드 래퍼 */
        .dark .rounded-md.shadow-sm.flex {
            background-color: transparent !important;
        }
    </style>
    
    {{ $this->table }}
</div>