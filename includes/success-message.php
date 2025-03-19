<?php
if (isset($_SESSION['successMessage']) && !empty($_SESSION['successMessage'])) {
    echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          <script>
            Swal.fire({
                title: 'Success!',
                text: '" . $_SESSION['successMessage'] . "',
                icon: 'success',
                confirmButtonText: 'Okay'
            });
          </script>";
    unset($_SESSION['successMessage']);
}
?>
