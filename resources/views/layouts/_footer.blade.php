@props(['hide_footer' => true])

@if(!$hide_footer)
    <footer class="mt-8 bg-white rounded-lg shadow m-4 dark:bg-gray-800">
        <div class="w-full mx-auto max-w-screen-xl p-4 md:flex md:items-center md:justify-between">
            <span class="text-sm text-gray-800 sm:text-center dark:text-gray-400">
                &copy; {{date('Y')}} <a href="https://tekfoundry.com/" class="hover:underline">TekFoundry</a>. All Rights Reserved.
            </span>
            <ul class="flex flex-wrap items-center mt-3 text-sm font-medium text-gray-500 dark:text-gray-400 sm:mt-0">
                <li>
                    <a href="#" class="hover:underline me-4 md:me-6">Privacy Policy</a>
                </li>
                <li>
                    <a href="#" class="hover:underline me-4 md:me-6">Terms</a>
                </li>
                <li>
                    <a href="#" target="_blank" class="hover:underline me-4 md:me-6">FAQ</a>
                </li>
                <li>
                    <a href="#" target="_blank" class="hover:underline">Contact</a>
                </li>
            </ul>
        </div>
    </footer>
@endif
