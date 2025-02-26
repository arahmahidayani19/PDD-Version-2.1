$(document).ready(function () {
  loadUserTable();

  function loadUserTable() {
    $.ajax({
      url: "user_data.php",
      method: "GET",
      success: function (data) {
        try {
          const users = JSON.parse(data);
          let rows = "";

          users.forEach((user) => {
            rows += `
                        <tr>
                            <td>${user.username}</td>
                            <td>${user.role}</td>
                            <td>
                               <button class="btn btn-warning btn-sm" data-id="${user.id}" data-bs-toggle="modal" data-bs-target="#editUserModal">
                                <i class="fas fa-edit"></i> Edit
                                </button>
                                <button class="btn btn-danger btn-sm" onclick="deleteUser(${user.id})">
                                <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </td>
                        </tr>`;
          });

          if ($.fn.DataTable.isDataTable("#userTable")) {
            $("#userTable").DataTable().clear().destroy();
          }

          $("#userTable tbody").html(rows);
          $("#userTable").DataTable({
            retrieve: true,
            autoWidth: false,
          });
        } catch (e) {
          console.error("Error parsing JSON data", e);
        }
      },
      error: function (xhr, status, error) {
        console.error(`Error loading user data: ${status} - ${error}`);
      },
    });
  }

  $(document).on("click", ".btn-warning", function () {
    const userId = $(this).data("id");
    editUser(userId);
  });

  window.deleteUser = function (id) {
    Swal.fire({
      title: "Are you sure?",
      text: "You will not be able to recover this user!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, delete it!",
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          url: "delete_user.php",
          method: "POST",
          data: { userId: id },
          dataType: "json",
          success: function (response) {
            if (response.status === "success") {
              Swal.fire("Deleted!", response.message, "success");
              loadUserTable();
            } else {
              Swal.fire("Error!", response.message, "error");
            }
          },
          error: function (xhr, status, error) {
            Swal.fire("Error!", "Failed to delete user.", "error");
            console.error(`Error deleting user: ${status} - ${error}`);
          },
        });
      }
    });
  };

  // ✅ Event Delegation untuk tombol Edit (fix modal tidak muncul)
  $(document).on("click", ".editUserBtn", function () {
    const userId = $(this).data("id");
    const username = $(this).data("username");
    const role = $(this).data("role");

    console.log(
      "Klik Edit - ID:",
      userId,
      "Username:",
      username,
      "Role:",
      role
    );

    $("#editUserId").val(userId);
    $("#editUsername").val(username);
    $("#editRole").val(role);

    $("#editUserModal").modal("show"); // 🔥 Pastikan modal tampil
  });

  // Handle Submit Edit Form
  $("#editUserForm").on("submit", function (e) {
    e.preventDefault();
    const formData = $(this).serialize();

    $.ajax({
      url: "edit_user.php",
      method: "POST",
      data: formData,
      dataType: "json",
      success: function (response) {
        if (response.status === "success") {
          Swal.fire({
            position: "top-end",
            icon: "success",
            title: "✅ User berhasil diperbarui!",
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
            toast: true,
          });
          $("#editUserModal").modal("hide");
          loadUserTable();
        } else {
          Swal.fire({
            position: "top-end",
            icon: "error",
            title: "❌ " + response.message,
            showConfirmButton: false,
            timer: 1500,
            timerProgressBar: true,
            toast: true,
          });
        }
      },
      error: function (xhr, status, error) {
        Swal.fire({
          position: "top-end",
          icon: "error",
          title: "⚠️ Terjadi kesalahan!",
          showConfirmButton: false,
          timer: 1500,
          timerProgressBar: true,
          toast: true,
        });
        console.error(`Error updating user: ${status} - ${error}`);
      },
    });
  });

  $("#addUserForm").on("submit", function (e) {
    e.preventDefault();
    const formData = $(this).serialize();

    $.ajax({
      url: "add_user.php",
      method: "POST",
      data: formData,
      dataType: "json",
      success: function (response) {
        if (response.status === "success") {
          Swal.fire({
            position: "top-center",
            icon: "success",
            title: response.message,
            showConfirmButton: false,
            timer: 1500,
          });
          $("#inlineForm").modal("hide");
          loadUserTable();
        } else {
          Swal.fire({
            position: "top-center",
            icon: "error",
            title: response.message,
            showConfirmButton: false,
            timer: 1500,
          });
        }
      },
      error: function (xhr, status, error) {
        Swal.fire({
          position: "top-center",
          icon: "error",
          title: "An error occurred. Please try again.",
          showConfirmButton: false,
          timer: 1500,
        });
        console.error(`Error adding user: ${status} - ${error}`);
      },
    });
  });
});
