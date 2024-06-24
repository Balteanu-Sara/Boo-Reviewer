function saveGenres(event) {
	event.preventDefault();

	const checkboxes = document.querySelectorAll('input[name="genre"]:checked');
	const selectedGenres = Array.from(checkboxes).map((cb) => cb.value);

	fetch("http://localhost/Boo2/php/save_genres.php", {
		method: "POST",
		body: JSON.stringify({ genres: selectedGenres }),
		headers: {
			"Content-Type": "application/json",
		},
	})
		.then((response) => response.json())
		.then((data) => {
			if (data.success) {
				alert("Genurile au fost salvate cu succes!");
				window.location.href = "loggedPage.html";
			} else {
				alert(data.message);
			}
		})
		.catch((error) => {
			console.error("Eroare:", error);
			alert("A aparut o eroare la salvarea genurilor.");
		});
}
