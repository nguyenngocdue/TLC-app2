<div class="fixed right-5 bottom-5 no-print z-30">
    <x-renderer.button id="go-to-top-button" onClick="goToTopFunction()" size="md" type="info">
        <i class="fa-solid fa-arrow-up"></i>
    </x-renderer.button>
</div>
<div class="fixed right-5 bottom-5 no-print z-30">
    <x-renderer.button id="go-to-bottom-button" onClick="goToBottomFunction()" size="md" type="info">
        <i class="fa-solid fa-arrow-down"></i>
    </x-renderer.button>
</div>
<script>
    let buttonGoToTop = document.getElementById("go-to-top-button");
    let buttonGoToBottom = document.getElementById("go-to-bottom-button");
    window.onscroll = function() {scrollFunction()};
    function scrollFunction() {
      if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        buttonGoToTop.style.display = "block";
        buttonGoToBottom.style.display = "none";
      } else {
        buttonGoToTop.style.display = "none";
        buttonGoToBottom.style.display = "block";
      }
    }
    function goToTopFunction() {
      // document.body.scrollTop = 0;
      // document.documentElement.scrollTop = 0;
      window.scrollTo({top: 0, behavior: 'smooth'});
    }
    function goToBottomFunction() {
      // document.body.scrollTop =  document.body.scrollHeight;
      // document.documentElement.scrollTop = document.body.scrollHeight;
      window.scrollTo({top: document.body.scrollHeight, behavior: 'smooth'});
    }
    </script>
