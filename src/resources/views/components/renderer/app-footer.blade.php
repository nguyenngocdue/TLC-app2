<div class="no-print text-center p-3 pt-20 text-xs bg-body text-body" 
     title="Developed with love and care by Fortune Truong">
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
