<?php ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const btnToggle = document.getElementById('btnToggleSidebar');
      const sidebar = document.getElementById('appSidebar');
      if (btnToggle && sidebar) {
        btnToggle.addEventListener('click', (e) => {
          e.stopPropagation();
          sidebar.classList.toggle('active');
        });

        document.addEventListener('click', (e) => {
          if (window.innerWidth < 768 && sidebar.classList.contains('active')) {
            if (!sidebar.contains(e.target) && e.target !== btnToggle) {
              sidebar.classList.remove('active');
            }
          }
        });
      }
    });
  </script>
</body>
</html>
