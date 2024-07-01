function sing(userId) {
    window.location.href = "./user.php?fetch=" + userId;
}
async function block(userId) {
    let operate = false;
    const block = document.getElementById('block' + userId);
    const unblock = document.getElementById('unblock' + userId);
    let t = "Are you sure you want to unblock this user?";
    let icon = "success";
    let confirm = "UNBLOCK";

    if (block.style.display == "none") {
        block.style.display = "inline";
        unblock.style.display = "none";
    } else {
        unblock.style.display = "inline";
        block.style.display = "none";
        operate = true;
        t = "Are you sure you want to block this user?";
        icon = "warning";
        confirm = "BLOCK";
    }

    const result = await Swal.fire({
        title: t,
        text: "You won't be able to revert this!",
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: "red",
        cancelButtonColor: "blue",
        confirmButtonText: confirm
    });

    if (result.isConfirmed) {
        const url = `../../routes/userRoute.php?user_id=${userId}&operate=${operate ? 1 : 0}`;
        fetch(url)
            .then(response => {
                if (response.ok) {
                    Swal.fire({
                        icon: "success",
                        title: "Done",
                        text: `User ${operate ? "blocked" : "unblocked"}`,
                    });
                } else {
                    throw new Error('Request failed');
                }
            })
            .catch(err => {
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Request error!",
                });
                // Revert display changes if there's an error
                if (operate) {
                    unblock.style.display = "inline";
                    block.style.display = "none";
                } else {
                    block.style.display = "inline";
                    unblock.style.display = "none";
                }
            });
    } else {
        // Revert the display changes if the action was cancelled
        if (operate) {
            block.style.display = "inline";
            unblock.style.display = "none";
        } else {
            unblock.style.display = "inline";
            block.style.display = "none";
        }
    }
}

