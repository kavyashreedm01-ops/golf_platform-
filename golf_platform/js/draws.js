async function loadDraws(){

    let res = await fetch("api/get_draws.php");
    let data = await res.json();

    let table = document.getElementById("drawTable");
    table.innerHTML = "";

    if(data.length === 0){
        table.innerHTML = "<tr><td colspan='2'>No draws yet</td></tr>";
        return;
    }

    data.forEach(d => {
        table.innerHTML += `
            <tr>
                <td>${d.draw_date}</td>
                <td>${d.numbers}</td>
            </tr>
        `;
    });
}

loadDraws();