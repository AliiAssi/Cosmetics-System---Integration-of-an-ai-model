async function del(bannerId) {
  const url = `../../routes/bannerRoute.php?id=${bannerId}`;
  const elementToDelete = document.getElementById("banner" + bannerId);
  if (elementToDelete) {
    const result = await Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Yes, delete it!",
      cancelButtonText: "No, cancel!",
      reverseButtons: true,
    });
    if (result.isConfirmed) {
      try {
        fetch(url)
          .then((response) => {
            if (!response.ok) {
              throw new Error("Network response was not ok");
            }
            return response.text(); // Assuming the response is text
          })
          .then((data) => {
            if (data === "cannot") {
              Toastify({
                text: "YOU CANNOT DELETE THE LAST BANNER, ONE BANNER MUST BE DISPLAYED",
                duration: 4000,
                close: true,
                gravity: "top", // `top` or `bottom`
                position: "center", // `left`, `center` or `right`
                style: {
                  background: "linear-gradient(to left, #00b09b, #96c93d)",
                },
              }).showToast();
            } else {
              Swal.fire({
                title: "Deleted!",
                text: "Your item has been deleted.",
                icon: "success",
              });
              elementToDelete.remove();
            }
          })
          .catch((error) => {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "Something went wrong!",
            });
          });
      } catch (error) {
        console.error("Fetch request failed:", error);
      }
    } else if (result.dismiss === Swal.DismissReason.cancel) {
      Swal.fire({
        title: "Cancelled",
        text: "banner is safe :)",
        icon: "error",
      });
    }
  }
}
