<?php
$content = file_get_contents("resources/views/attendance/index.blade.php");

$find = <<<HTML
    <div class="flex items-center gap-3">
        <form action="{{ route('attendance.index') }}" method="GET" class="flex items-center gap-2">
HTML;

$replace = <<<HTML
    <div class="flex items-center gap-3">
        <button type="button" class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 text-white" @click="\$dispatch('open-verify-modal')">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2a10 10 0 0 0-10 10v2a10 10 0 0 0 10 10 10 10 0 0 0 10-10v-2a10 10 0 0 0-10-10z" />
                <path d="M12 6a6 6 0 0 0-6 6v2a6 6 0 0 0 6 6 6 6 0 0 0 6-6v-2a6 6 0 0 0-6-6z" />
                <path d="M12 10a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2 2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2z" />
            </svg>
            Scan Absensi
        </button>
        <form action="{{ route('attendance.index') }}" method="GET" class="flex items-center gap-2">
HTML;

$content = str_replace($find, $replace, $content);
file_put_contents("resources/views/attendance/index.blade.php", $content);
echo "Done1\n";
