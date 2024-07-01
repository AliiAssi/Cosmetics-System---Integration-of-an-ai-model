async function changeAddress(country, city, area){
    const { value: formValues } = await Swal.fire({
    title: "TargetAddress",
    html: `
        <input id="swal-input1" class="swal2-input" name="country" value="${country}">
        <input id="swal-input2" class="swal2-input" name="city" value="${city}">
        <input id="swal-input3" class="swal2-input" name="area" value="${area}">
    `,
    focusConfirm: false,
    preConfirm: () => {
        return [
        document.getElementById("swal-input1").value,
        document.getElementById("swal-input2").value,
        document.getElementById("swal-input3").value
        ];
    }
    });
    if (formValues) {
        const country =formValues[0];
        const city =formValues[1];
        const area =formValues[2];
        if (country.trim() === '' || city.trim() === '' || area.trim() === '') {
            await Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "You must fill all entries !",
            });
        }else {
            const url = `../../routes/userRoute.php?country=${country}&city=${city}&area=${area}`;
            try {
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text(); // Assuming the response is text
                })
                .then(data => {
                    const addressDiv =document.getElementById('userAddress');
                    if (addressDiv){
                        addressDiv.innerHTML = `${country} /${city} /${area}`;
                    }
                    console.log("data :"+data);
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
} 