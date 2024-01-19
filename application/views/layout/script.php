  <!-- Libs JS -->
  <script src="<?=base_url("assets")?>/dist/libs/apexcharts/dist/apexcharts.min.js?1684106062" defer></script>
  <script src="<?=base_url("assets")?>/dist/libs/jsvectormap/dist/js/jsvectormap.min.js?1684106062" defer></script>
  <script src="<?=base_url("assets")?>/dist/libs/jsvectormap/dist/maps/world.js?1684106062" defer></script>
  <script src="<?=base_url("assets")?>/dist/libs/jsvectormap/dist/maps/world-merc.js?1684106062" defer></script>
  <script src="<?=base_url("assets")?>/dist/js/tabler.min.js?1684106062" defer></script>
  <script src="<?=base_url("assets")?>/dist/js/demo.min.js?1684106062" defer></script>
  
  <script class="appendHere"></script>
  <script>
      $(document).ready(function() {
          const perPageJs = $('.javascript').html();
          $('.appendHere').append(perPageJs);
          $('.javascript').html("");
      });
  </script>