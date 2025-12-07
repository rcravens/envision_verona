<div id="flash-container" class="fixed bottom-4 right-4 z-50 w-96 space-y-2"></div>

<script type="module">
    (function () {
        document.addEventListener('DOMContentLoaded', function () {
            @if(session('custom_alerts'))
            const alerts = @json(session()->pull('custom_alerts'));
            if (alerts) {
                for (const id in alerts) {
                    const alert = alerts[id];
                    show_flash(alert.type, alert.title, alert.message, alert.timer);
                }
            }
            @endif
        });

        function show_flash(type = 'info', title = 'Notice', message = '', timer = 3000) {
            const container = document.getElementById('flash-container');

            const types = {
                success: {bg: 'bg-green-600', icon: '✓'},
                error  : {bg: 'bg-red-600', icon: '✕'},
                warning: {bg: 'bg-yellow-500', icon: '!'},
                info   : {bg: 'bg-blue-600', icon: 'ℹ'},
            };
            const style = types[type] || types.info;

            const flash = document.createElement('div');
            flash.className = `${style.bg} px-6 py-4 rounded-lg shadow-xl flex flex-row items-center gap-3 transform transition-all duration-500 opacity-0 translate-y-4`;
            flash.innerHTML = `
            <div class="text-2xl font-bold">${style.icon}</div>
            <div>
                <strong class="block text-lg font-semibold">${title}</strong>
                <span class="block text-sm opacity-90">${message}</span>
            </div>
            <button class="ml-auto text-xl leading-none hover:opacity-70">&times;</button>
        `;

            flash.querySelector('button').addEventListener('click', () => {
                fade_out_and_remove(flash);
            });

            container.appendChild(flash);

            requestAnimationFrame(() => {
                flash.classList.remove('opacity-0', 'translate-y-4');
                flash.classList.add('opacity-100', 'translate-y-0');
            });

            if (timer > 0) {
                setTimeout(() => fade_out_and_remove(flash), timer);
            }
        }

        function fade_out_and_remove(element) {
            element.classList.add('opacity-0', 'translate-y-4');
            setTimeout(() => element.remove(), 500);
        }

        App.show_flash = show_flash;
    })();
</script>
