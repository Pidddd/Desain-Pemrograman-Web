async function muatDataTabel(url, keys) {
    const tbody = document.querySelector(".table-responsive table tbody");
    const loading = document.getElementById("loading-indicator");
    if (!tbody) return;

    loading.style.display = "block";
    tbody.innerHTML = "";

    try {
        await new Promise((resolve) => setTimeout(resolve, 3000)); // Simulasi delay
        
        const res = await fetch(url);
        if (!res.ok) throw new Error("Gagal mengambil data (status " + res.status + ")");
        
        const dataArr = await res.json();
        
        dataArr.forEach(function (item) {
            const tr = document.createElement("tr");
            
            // Generate <td> sesuai urutan keys yang dikirim
            let htmlSel = "";
            keys.forEach(function(key) {
                htmlSel += "<td>" + item[key] + "</td>";
            });
            
            // Tambahkan kolom Aksi di akhir
            htmlSel += `<td>
                <button type="button">Edit</button>
                <button type="button" class="btn-hapus">Hapus</button>
            </td>`;
            
            tr.innerHTML = htmlSel;
            tbody.appendChild(tr);
        });
    } catch (err) {
        tbody.innerHTML = `<tr><td colspan="${keys.length + 1}">Gagal memuat data: ${err.message}</td></tr>`;
    } finally {
        loading.style.display = "none";
    }
}