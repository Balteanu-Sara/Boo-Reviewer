document.addEventListener("DOMContentLoaded", function () {
	const urlParams = new URLSearchParams(window.location.search);
	const bookId = urlParams.get("book_id");

	if (!bookId) {
		console.error("No book ID provided in URL!");
		return;
	}

	fetchBookDetails(bookId);

	fetch(`http://localhost/Boo2/php/check_user_book.php?book_id=${bookId}`)
		.then((response) => response.json())
		.then((data) => {
			if (data.success && data.hasBook) {
				// daca userul are cartea adaugata in colectia afisam buttoanele de see review si back to my books
				showReviewButtons(bookId);
			} else {
				// daca nu are cartea in colectie, afisam doar butonul de add book
				showAddBookButton(bookId);
			}
		})
		.catch((error) => console.error("Error checking user book:", error));
});

function fetchBookDetails(bookId) {
	fetch(`http://localhost/Boo2/php/book_summary.php?book_id=${bookId}`)
		.then((response) => response.json())
		.then((data) => {
			if (data.success) {
				const book = data.book;
				displayBookDetails(book);
			} else {
				console.error("Error loading book details:", data.message);
			}
		})
		.catch((error) => console.error("Error fetching book details:", error));
}

function displayBookDetails(book) {
	document.getElementById("bookCover").src = book.image_url;
	document.getElementById("bookCover").alt = book.title;
	document.getElementById("bookTitle").textContent = book.title;
	document.getElementById("bookAuthor").textContent = book.author;
	document.getElementById("bookPublisher").textContent = book.publisher;
	document.getElementById("bookYear").textContent = book.year;
	document.getElementById("bookGenre").textContent = book.genre;
	document.getElementById("bookSummaryContainer").textContent = book.summary;
}

function showReviewButtons(bookId) {
	const seeReviewContainer = document.getElementById("seeReviewContainer");

	const goBackLink = document.createElement("a");
	goBackLink.href = "myBooks.html";
	goBackLink.textContent = "Back to My Books";
	goBackLink.classList.add("myBooks-main__goBack");
	seeReviewContainer.appendChild(goBackLink);

	const seeReviewButton = document.createElement("button");
	seeReviewButton.textContent = "See Review";
	seeReviewButton.classList.add("myBooks-main__seeMore");
	seeReviewButton.onclick = function () {
		seeMore(bookId);
	};
	seeReviewContainer.appendChild(seeReviewButton);
}

function showAddBookButton(bookId) {
	const seeReviewContainer = document.getElementById("seeReviewContainer");

	const addBookButton = document.createElement("button");
	addBookButton.textContent = "Add Book";
	addBookButton.classList.add("myBooks-main__addBook");
	addBookButton.onclick = function () {
		addBook(bookId);
	};
	seeReviewContainer.appendChild(addBookButton);
}

function addBook(bookId) {
	fetch("http://localhost/Boo2/php/addBook.php", {
		method: "POST",
		headers: {
			"Content-Type": "application/json",
		},
		body: JSON.stringify({ book_id: bookId }),
	})
		.then((response) => {
			if (response.status === 409) {
				return response.json().then((data) => {
					throw new Error(data.message || "Conflict occurred");
				});
			} else if (!response.ok) {
				throw new Error("Network response was not ok");
			}
			return response.json();
		})
		.then((data) => {
			if (data.success) {
				window.location.href = "myBooks.html";
			} else {
				alert("Failed to add book: " + data.message);
			}
		})
		.catch((error) => {
			if (error.message === "Cartea este deja adaugata in colectia ta!") {
				alert(error.message);
			} else {
				console.error("Error adding book:", error);
				alert("Failed to add book: " + error.message);
			}
		});
}