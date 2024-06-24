function fetchBooks(genreId, containerId) {
    fetch(`http://localhost/Boo2/php/books.php?genre=${genreId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const container = document.getElementById(containerId);
            container.innerHTML = '';

            if (data.length > 0) {
                data.forEach(book => {
                    const cardCollection = document.createElement('div');
                      cardCollection.classList.add('cardCollection');
                      cardCollection.innerHTML = `
                      <div class="content">
                        <div class="card-inner">
                            <div class="img">
                                <img src="${book.image_url}" alt="${book.title}" />
                            </div>
                            <div class="details">
                                <div class="title">${book.title}</div>
                                <div class="author">${book.author}</div>
                                <div class="addBook-button">
                                  <button onclick="addBook(${book.book_id})">Add Book</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                    container.appendChild(cardCollection);
                });
            } else {
                container.innerHTML = '<p>Momentan, nu exista carti de acest gen.</p>';
            }
        })
        .catch(error => {
            console.error('Error fetching books:', error);
        });
}

function addBook(bookId) {
fetch('http://localhost/Boo2/php/addBook.php', {
method: 'POST',
headers: {
    'Content-Type': 'application/json'
},
body: JSON.stringify({ book_id: bookId })
})
.then(response => {
if (response.status === 409) {
    return response.json().then(data => {
        throw new Error(data.message || 'Conflict occurred');
    });
} else if (!response.ok) {
    throw new Error('Network response was not ok');
}
return response.json();
})
.then(data => {
if (data.success) {
    window.location.href = 'myBooks.html';
} else {
    alert('Failed to add book: ' + data.message);
}
})
.catch(error => {
if (error.message === "Cartea este deja adaugata in colectia ta!") {
    alert(error.message);
} else {
    console.error('Error adding book:', error);
    alert('Failed to add book: ' + error.message);
}
});
}

fetchBooks(1, 'art-books-container'); 
fetchBooks(2, 'biography-books-container');
fetchBooks(3, 'business-books-container'); 
fetchBooks(4, 'children-books-container');
fetchBooks(5, 'comics-books-container'); 
fetchBooks(6, 'crime-books-container'); 
fetchBooks(7, 'fantasy-books-container'); 
fetchBooks(8, 'fiction-books-container'); 
fetchBooks(9, 'history-books-container'); 
fetchBooks(10, 'horror-books-container'); 
fetchBooks(11, 'humor&comedy-books-container');
fetchBooks(12, 'music-books-container'); 
fetchBooks(13, 'mystery-books-container'); 
fetchBooks(14, 'nonfiction-books-container'); 
fetchBooks(15, 'philosophy-books-container'); 
fetchBooks(16, 'poetry-books-container'); 
fetchBooks(17, 'psychology-books-container'); 
fetchBooks(18, 'religion-books-container');
fetchBooks(19, 'romance-books-container'); 
fetchBooks(20, 'science-books-container'); 
fetchBooks(21, 'science_fiction-books-container'); 
fetchBooks(22, 'spirituality-books-container'); 
fetchBooks(23, 'sports-books-container'); 
fetchBooks(24, 'thriller-books-container'); 
fetchBooks(25, 'travel-books-container');