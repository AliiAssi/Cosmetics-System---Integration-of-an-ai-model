function cannot() {
    Swal.fire({
        icon: "warning",
        title: "Oops...",
        text: "cannot delete an accepted order!",
    });
}
async function cancel(orderId) {
    const url = `../../routes/orderRoute.php?deleteOrderId=${orderId}`;
    Swal.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Yes, delete it!"
    }).then((result) => {
    if (result.isConfirmed) {
        const element = document.getElementById('tr' + orderId);
        if (element) {
            try {
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text(); // Assuming the response is text
                })
                .then(data => {
                    element.remove();
                    Swal.fire({
                    title: "Deleted!",
                    text: "Your item has been deleted.",
                    icon: "success"
                    });
                    console.log('Fetch request succeeded:', data);
                    const count =parseInt(data);
                    if(count == 0) {
                        window.location.href = "history.php";
                    }
                })
                .catch(error => {
                    Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Something went wrong!",
                    });
                });
            } catch (error) {
                console.error('Error:', error);
            }
        } else {
            console.error('Element not found with ID: item' + id);
        }
    }
    });
}