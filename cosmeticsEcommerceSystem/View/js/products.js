async function deleteProduct(productId){
    const elementThatClicked = document.getElementById("delete" + productId);
    if (!elementThatClicked) {return;}

    const url = `../../routes/productRoute.php?deleteId=${productId}`;

    const result = await Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        reverseButtons: true
    });

    if(result.isConfirmed){
        try {
            const response = await fetch(url);

            if (!response.ok) {
                throw new Error('Failed to delete product');
            }

            const data = await response.text(); // Retrieve response as text

            console.log(data); // Log response from server to console
            if (data === "Product deleted successfully."){
                elementThatClicked.style.display = 'none';
            }
            Swal.fire({
                title: "Deleted!",
                text: ""+data,
                icon: "success"
            });
        } catch (error) {
            console.error('Error deleting product:', error);

            Swal.fire({
                title: "Error",
                text: "Failed to delete product. Please try again later.",
                icon: "error"
            });
        }
    } else {
        Swal.fire({
            title: "Cancelled",
            text: "You are safe :)",
            icon: "error"
        });
    }
}

async function like(userId, productId) {
    // Get the element that was clicked using its ID
    const elementThatClicked = document.getElementById("like" + productId);
    if (!elementThatClicked) return;

    // Define the fetch URL
    const url = `../../routes/userFavProductRoute.php?pId=${productId}&uId=${userId}`;

    // Debugging: Log the current fill color
    console.log(`Current fill color: ${elementThatClicked.getAttribute("fill")}`);

    if (elementThatClicked.getAttribute("fill") === "red") {
        console.log("Condition met: fill is red");

        // Show a confirmation dialog using SweetAlert2
        const result = await Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            reverseButtons: true
        });

        // If the user confirms, send a fetch request to delete the product
        if (result.isConfirmed) {
            try {
                const response = await fetch(url);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                // Change the fill color of the SVG element to white and border color to red
                elementThatClicked.setAttribute("fill", "white");
                elementThatClicked.setAttribute("stroke", "red"); // Adding border color

                // Debugging: Log the new fill and stroke colors
                console.log(`New fill color: ${elementThatClicked.getAttribute("fill")}`);
                console.log(`New stroke color: ${elementThatClicked.getAttribute("stroke")}`);

                // Show a success message
                Swal.fire({
                    title: "Deleted!",
                    text: "Your product has been deleted from favorites ^_^",
                    icon: "success"
                });
            } catch (error) {
                console.error('Fetch request failed:', error);
            }
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            // Show a cancellation message if the user cancels
            Swal.fire({
                title: "Cancelled",
                text: "You are safe :)",
                icon: "error"
            });
        }
    } else if (elementThatClicked.getAttribute("fill") === "white") {
        console.log("Condition met: fill is white");

        // Change the fill color of the SVG element to red and border color to white
        elementThatClicked.setAttribute("fill", "red");
        elementThatClicked.setAttribute("stroke", "white");

        // Debugging: Log the new fill and stroke colors
        console.log(`New fill color: ${elementThatClicked.getAttribute("fill")}`);
        console.log(`New stroke color: ${elementThatClicked.getAttribute("stroke")}`);

        Swal.fire({
            title: "Good job!",
            text: "Added to favorites",
            icon: "success"
        });

        try {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            // Log the successful response for debugging purposes
            console.log('Fetch request succeeded:', await response.text());
        } catch (error) {
            console.error('Fetch request failed:', error);
        }
    }
}

async function addToCart(pId){
    try {
    // Construct the fetch URL
    const url = `../../routes/cartRoute.php?product_to_add=${pId}`;
    // Send the fetch request
    await fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text(); // Assuming the response is text
        })
        .then(data => {
            if(data.trim() === "ok"){
                Swal.fire({
                    title: "Good job!",
                    text: "added to cart",
                    icon: "success"
                });
            }
            else{
                Swal.fire({
                    title: "no product items available!",
                    icon: "error"
                });
            }
            console.log('Fetch request succeeded:', data);
            // Add any further processing of the response data here
        })
        .catch(error => {
            console.error('Fetch request failed:', error);
        });
    } catch (error) {
        console.error('Error:', error);
    }
}

