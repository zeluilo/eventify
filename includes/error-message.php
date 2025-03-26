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

if (isset($_SESSION['loginSuccess']) && $_SESSION['loginSuccess'] === true) {
  unset($_SESSION['loginSuccess']);
  echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
          <script>
            Swal.fire({
                title: 'Welcome to Eventify!',
                text: 'You have successfully logged in. Explore upcoming events now!',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
          </script>";
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

if (isset($_SESSION['categoryDeletionSuccess']) && $_SESSION['categoryDeletionSuccess'] === true) {
  unset($_SESSION['categoryDeletionSuccess']);
  echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
  <script>
      Swal.fire({
          title: 'Category Deleted Successfully!',
          text: 'The event category has been deleted from the system.',
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

if (isset($_SESSION['userCreationSuccess']) && $_SESSION['userCreationSuccess'] === true) {
  unset($_SESSION['userCreationSuccess']);
  echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            title: 'User Created Successfully!',
            text: 'The user account has been successfully created.',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    </script>";
}

if (isset($_SESSION['userDeletionSuccess']) && $_SESSION['userDeletionSuccess'] === true) {
  unset($_SESSION['userDeletionSuccess']);
  echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
  <script>
      Swal.fire({
          title: 'User Deleted Successfully!',
          text: 'The user account has been successfully removed from the system.',
          icon: 'success',
          confirmButtonText: 'OK'
      });
  </script>";
}

if (isset($_SESSION['userUpdateSuccess']) && $_SESSION['userUpdateSuccess'] === true) {
  unset($_SESSION['userUpdateSuccess']);
  echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
  <script>
      Swal.fire({
          title: 'User Updated Successfully!',
          text: 'The user details have been updated successfully.',
          icon: 'success',
          confirmButtonText: 'OK'
      });
  </script>";
}
