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

if (isset($_SESSION['categoryCreationSuccess']) && $_SESSION['categoryCreationSuccess'] === true) {
  unset($_SESSION['categoryCreationSuccess']);
  echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
  <script>
      Swal.fire({
          title: 'Category Created Successfully!',
          text: 'Your new event category has been added.',
          icon: 'success',
          confirmButtonText: 'OK'
      });
  </script>";
}

if (isset($_SESSION['categoryUpdateSuccess']) && $_SESSION['categoryUpdateSuccess'] === true) {
  unset($_SESSION['categoryUpdateSuccess']);
  echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
  <script>
      Swal.fire({
          title: 'Category Updated Successfully!',
          text: 'The event category has been updated.',
          icon: 'success',
          confirmButtonText: 'OK'
      });
  </script>";
}

if (isset($_SESSION['eventUpdateSuccess']) && $_SESSION['eventUpdateSuccess'] === true) {
  unset($_SESSION['eventUpdateSuccess']);
  echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
      Swal.fire({
          title: 'Event Updated Successfully!',
          text: 'The event details have been updated.',
          icon: 'success',
          confirmButtonText: 'OK'
      });
    </script>";
}

if (isset($_SESSION['eventDeletionSuccess']) && $_SESSION['eventDeletionSuccess'] === true) {
  unset($_SESSION['eventDeletionSuccess']);
  echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
  <script>
      Swal.fire({
          title: 'Event Deleted Successfully!',
          text: 'The event has been removed from the list.',
          icon: 'success',
          confirmButtonText: 'OK'
      });
  </script>";
}

if (isset($_SESSION['eventCreationSuccess']) && $_SESSION['eventCreationSuccess'] === true) {
  unset($_SESSION['eventCreationSuccess']);
  echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
      Swal.fire({
          title: 'Event Created Successfully!',
          text: 'Your event has been saved and can now be viewed by others.',
          icon: 'success',
          confirmButtonText: 'OK'
      });
    </script>";
}

if (isset($_SESSION['registrationSuccess']) && $_SESSION['registrationSuccess'] === true) {

  unset($_SESSION['registrationSuccess']);
  echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
          Swal.fire({
              title: 'Registration Successful!',
              text: 'You can now log in with your credentials.',
              icon: 'success',
              confirmButtonText: 'Go to Login'
          }).then((result) => {
              if (result.isConfirmed) {
                  window.location.href = '/users/login'; // Redirect to login page
              }
          });
        </script>";
}
?>
