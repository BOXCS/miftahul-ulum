<?php
$content = file_get_contents("resources/views/students/index.blade.php");

$find = <<<HTML
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button" class="btn btn-ghost btn-icon btn-sm"
HTML;

$replace = <<<HTML
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button" class="btn btn-ghost btn-icon btn-sm" title="Sidik Jari"
                                        @click="\$dispatch('open-fingerprint-modal', { student: student })">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M12 2a10 10 0 0 0-10 10v2a10 10 0 0 0 10 10 10 10 0 0 0 10-10v-2a10 10 0 0 0-10-10z" />
                                            <path d="M12 6a6 6 0 0 0-6 6v2a6 6 0 0 0 6 6 6 6 0 0 0 6-6v-2a6 6 0 0 0-6-6z" />
                                            <path d="M12 10a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2 2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2z" />
                                        </svg>
                                    </button>
                                    <button type="button" class="btn btn-ghost btn-icon btn-sm" title="Edit"
HTML;

$content = str_replace($find, $replace, $content);
file_put_contents("resources/views/students/index.blade.php", $content);
echo "Done\n";
