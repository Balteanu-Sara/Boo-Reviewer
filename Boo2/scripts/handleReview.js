document.addEventListener("DOMContentLoaded", function () {
	const reviewData = JSON.parse(localStorage.getItem("reviewData"));
	const bookId = localStorage.getItem("bookId");
	const userId = localStorage.getItem("userId");
	const reviewContainer = document.getElementById("reviewContainer");

	if (reviewData) {
		reviewContainer.innerHTML = `<h2>Your review</h2><blockquote>${reviewData}</blockquote>`;
	} else {
		reviewContainer.innerHTML = `
          <form id="reviewForm">
              <textarea id="reviewText" rows="5" cols="50" placeholder="Write your review here..."></textarea><br>
              <button type="submit">Submit Review</button>
          </form>
      `;

		document
			.getElementById("reviewForm")
			.addEventListener("submit", function (event) {
				event.preventDefault();
				const reviewText = document.getElementById("reviewText").value;

				if (bookId && reviewText && userId) {
					fetch("http://localhost/Boo2/php/post_review.php", {
						method: "POST",
						headers: {
							"Content-Type": "application/json",
						},
						body: JSON.stringify({
							bookId: bookId,
							review: reviewText,
							userId: userId,
						}),
					})
						.then((response) => response.json())
						.then((data) => {
							if (data.success) {
								localStorage.setItem("reviewData", JSON.stringify(reviewText));
								window.location.reload();
							} else {
								alert(data.message);
							}
						})
						.catch((error) => {
							console.error("Error:", error);
							alert("An error occurred while posting the review.");
						});
				} else {
					alert("Missing required data to submit review.");
				}
			});
	}
});
