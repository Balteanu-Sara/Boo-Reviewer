function registerUser(event) {
	event.preventDefault();

	const form = document.getElementById("registerForm");
	const formData = new FormData(form);

	fetch("http://localhost/Boo2/php/register.php", {
		method: "POST",
		body: JSON.stringify(Object.fromEntries(formData.entries())),
		headers: {
			"Content-Type": "application/json",
		},
	})
		.then((response) => response.json())
		.then((data) => {
			if (data.user_id) {
				alert("Contul a fost creat cu succes!");
				window.location.href = "sign-in.html";
			} else {
				alert(data.message);
			}
		})
		.catch((error) => {
			console.error("Eroare:", error);
			alert("Eroare la crearea contului.");
		});
}
