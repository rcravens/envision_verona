<x-layouts.app>

    <!-- Section: Population Growth Over Time -->
    <section class="h-screen px-6 py-8 bg-gray-100 dark:bg-gray-800">
        <h2 class="text-4xl font-bold mb-12">Report List</h2>

        <table class="min-w-full bg-gray-100 dark:bg-gray-900 text-left">
            <thead>
            <tr>
                <x-table.th>Project Name</x-table.th>
                <x-table.th>Analysis</x-table.th>
            </tr>
            </thead>
            <tbody>
            @foreach($projects as $project)
                <tr>
                    <x-table.td>{{ $project->name }}</x-table.td>
                    <x-table.td>
                        <x-table.link :href="route('reports.analysis', $project->slug)">Analysis</x-table.link>
                    </x-table.td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </section>

</x-layouts.app>
