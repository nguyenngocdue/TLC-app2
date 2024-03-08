<div class="no-print text-center dark:text-white p-3 text-gray-700 text-xs" title="Developed with love and care by Fortune Truong">
    <x-elapse />
    <x-elapse total=1/>
    TLC Modular App ©2017-{{date("Y")}}. All rights reserved. Version {{config("version.app_version")}}
</div>
<script>
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();

        document.querySelector(this.getAttribute('href')).scrollIntoView({
            behavior: 'smooth',
            // behavior: 'auto',
        });
    });
});
</script>
