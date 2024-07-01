function toPage() {
    window.location.href = 'checkout.php';
}
async function deleteItem(id){
    const url = `../../routes/cartRoute.php?item_id=${id}`;
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
        const element = document.getElementById('item' + id);
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
                            const checki  =document.getElementById('checki');
                            checki.remove();
                            const here = document.getElementById('here');
                            here.innerHTML = `
                                <div class="alert alert-danger mt-5 text-center">
                                    <strong>Alert!</strong>
                                    <p>Dear valued customer, your cart is empty ^_^</p>
                                </div>`;
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
async function incrementItem(id){
    const qtyInc =document.getElementById('qty'+id);
    if(qtyInc)  {
        const qtyValue = qtyInc.innerText;
        const url = `../../routes/cartRoute.php?inc_id=${id}&qty=${(parseInt(qtyValue)+1)}`;
        try {
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text(); // Assuming the response is text
            })
            .then(data => {
                console.log(data);
                if(data.trim() !== "no"){
                    qtyInc.innerText = (parseInt(qtyValue) + 1);
                }
                else{
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "no enough items available",
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "request error!",
                });
            });
        } catch (error) {
            console.log(error);
        }
    }
}
async function decrementItem(id){
    const qtyDec =document.getElementById('qty'+id);
    
    if(qtyDec)  {
        const qtyValue = qtyDec.innerText;
        if(qtyValue == 1){
            Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "cannot lol *_* ",
            });
            return;
        }
        qtyDec.innerText =(parseInt(qtyValue) - 1);
        const url = `../../routes/cartRoute.php?dec_id=${id}&qty=${(parseInt(qtyValue)-1)}`;
        try {
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text(); // Assuming the response is text
            })
            .then(data => {
                qtyDec.innerText =(parseInt(qtyValue) - 1);
                console.log(data);

            })
            .catch(error => {
                Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "request error!",
                });
            });
        } catch (error) {
            console.log(error);
        }
    }
}
