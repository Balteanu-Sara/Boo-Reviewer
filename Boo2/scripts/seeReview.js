document.addEventListener("DOMContentLoaded", function () {
	const urlParams = new URLSearchParams(window.location.search);
	const bookId = urlParams.get("bookId");
	const userId = localStorage.getItem("userId");

	if (bookId) {
		fetchBookReview(bookId);
	} else {
		console.error("Missing bookId in URL.");
		alert("Missing bookId in URL.");
	}
});

function fetchBookReview(bookId) {
	Promise.all([fetchUserReview(bookId), fetchOtherReviews(bookId)])
		.then(([userReviewData, otherReviewsData]) => {
			displayUserReview(userReviewData, bookId);
			displayOtherReviews(otherReviewsData);
		})
		.catch((error) => {
			console.error("Error fetching reviews:", error);
			alert("Error fetching reviews.");
		});
}

function fetchUserReview(bookId) {
	return fetch(`http://localhost/Boo2/php/fetchUserReview.php?bookId=${bookId}`)
		.then((response) => response.json())
		.catch((error) => {
			console.error("Error fetching user review:", error);
			return { userHasReview: false, userReview: null};
		});
}

function fetchOtherReviews(bookId) {
	return fetch(
		`http://localhost/Boo2/php/fetchOtherReviews.php?bookId=${bookId}`
	)
		.then((response) => response.json())
		.catch((error) => {
			console.error("Error fetching other reviews:", error);
			return { otherReviews: [], noReviews: true };
		});
}

function displayUserReview(userReviewData, bookId) {
	const userReviewContainer = document.getElementById("userReviewContainer");
	if (userReviewData.userHasReview) {
		userReviewContainer.innerHTML = `
    <div class="wrapper__containersReview__quote">
        <h2>Your Review</h2>
        <blockquote>${userReviewData.userReview}</blockquote>
    </div>
`;
	} else {
		userReviewContainer.innerHTML = `
           <div class="wrapper__containers">
        <h2>Write your review!</h2>
        <textarea name="reviewText" id="reviewText" placeholder="Type in your review..."></textarea>
        <button onclick="postReview(${bookId})">Post Review</button>
      </div>
        `;

		
	}
}

function displayOtherReviews(otherReviewsData) {
	const otherReviewsContainer = document.getElementById(
		"otherReviewsContainer"
	);
	if (otherReviewsData.noReviews) {
		otherReviewsContainer.innerHTML = `
        
						<li class="wrapper__containersReview__no-comments__list__element">
							<p>No reviews from other users yet.</p>
						</li>
           
                `;
	} else {
		otherReviewsContainer.innerHTML = "";
		otherReviewsData.otherReviews.forEach((review) => {
			const reviewElement = document.createElement("div");
			reviewElement.classList.add("other-review");
			reviewElement.innerHTML = `
            <div class="wrapper__containersReview__comments">
                <ul class="wrapper__containersReview__comments__list">
						<li class="wrapper__containersReview__comments__list__element">
							<p
								class="wrapper__containersReview__comments__list__element--mod1"
							>
                            ${review.username}
							</p>
							<p
								class="wrapper__containersReview__comments__list__element--mod2"
							>
                            ${review.review}
							</p>
						</li>
                    </ul>
                </div>
            `;
			otherReviewsContainer.appendChild(reviewElement);
		});
	}
}

function postReview(bookId) {
	const reviewContent = document.getElementById("reviewText").value;
	console.log(reviewContent);
	fetch(`http://localhost/Boo2/php/postReview.php`, {
		method: "POST",
		headers: {
			"Content-Type": "application/json",
		},
		body: JSON.stringify({ bookId: bookId, review: reviewContent }),
	})
		.then((response) => response.json())
		.then((data) => {
			if (data.success) {
				alert("Review posted successfully.");
				window.location.reload();
			} else {
				console.error("Error posting review:", data.message);
				alert("Error posting review.");
			}
		})
		.catch((error) => {
			console.error("Error:", error);
			alert("An error occurred while posting the review.");
		});
}
