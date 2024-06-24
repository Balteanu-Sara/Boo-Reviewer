document.addEventListener("DOMContentLoaded", function () {
	fetchUserInfo();
});

function fetchUserInfo() {
	fetch("http://localhost/Boo2/php/profile_info.php")
		.then((response) => {
			if (!response.ok) {
				throw new Error("Network response was not ok");
			}
			return response.json();
		})
		.then((data) => {
			document.getElementById("email").innerText = data.email;
			document.getElementById("usernameHeader").innerText = data.username;
			document.getElementById("usernameMain").innerText = data.username;
		})
		.catch((error) => {
			console.error("Eroare:", error);
			alert("A aparut o eroare la preluarea informatiilor despre utilizator.");
		});
}

