<?php
if (isset($_SESSION['errorMessage']) && !empty($_SESSION['errorMessage'])) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          <script>
            Swal.fire({
                title: 'Error!',
                text: '" . $_SESSION['errorMessage'] . "',
                icon: 'error',
                confirmButtonText: 'Okay'
            });
          </script>";
    unset($_SESSION['errorMessage']);
}
?>
