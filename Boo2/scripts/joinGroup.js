function joinGroup(groupId) {
	fetch("http://localhost/Boo2/php/joinGroup.php", {
		method: "POST",
		headers: {
			"Content-Type": "application/json",
		},
		body: JSON.stringify({
			group_id: groupId,
		}),
	})
		.then((response) => {
			if (response.status === 200) {
				return response.json().then((data) => {
					console.log("Succes:", data.message);
					window.location.href = `http://localhost/Boo2/bookClub.html?club_id=${groupId}`;
				});
			} else if (response.ok) {
				return response.json().then((data) => {
					console.log("Success:", data.message);
					window.location.href = `http://localhost/Boo2/bookClub.html?club_id=${groupId}`;
				});
			} else {
				return response.json().then((data) => {
					throw new Error(data.message);
				});
			}
		})
		.catch((error) => {
			console.error("Error:", error);
			alert("An error occurred while trying to join the group.");
		});
}
