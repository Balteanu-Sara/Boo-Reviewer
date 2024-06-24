document.addEventListener('DOMContentLoaded', function() {
    fetch('http://localhost/Boo2/php/getUserBooks.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const tableBody = document.querySelector('.myBooks-table tbody');
                data.books.forEach(book => {
                    const row = document.createElement('tr');
                    row.classList.add('myBooks-table__row');
                    row.innerHTML = `
                        <td><a href="http://localhost/Boo2/bookSummary.html?book_id=${book.book_id}"><img src="${book.image_url}" alt="${book.title}" class="myBooks-table__cover--img" /></a></td>
                        <td>${book.title}</td>
                        <td>${book.author}</td>
                        <td>${book.genre}</td>
                        <td>${book.year}</td>
                        <td>${book.publisher}</td>
                        <td>
                            <div class="status-section">
                                <div class="status-section__row">
                                    <input type="radio" name="status${book.user_book_id}" id="read${book.user_book_id}" ${book.status === 'read' ? 'checked' : ''} />
                                    <label for="read${book.user_book_id}">Read</label>
                                </div>
                                <div class="status-section__row">
                                    <input type="radio" name="status${book.user_book_id}" id="reading${book.user_book_id}" ${book.status === 'reading' ? 'checked' : ''} />
                                    <label for="reading${book.user_book_id}">Reading</label>
                                </div>
                                <div class="status-section__row">
                                    <input type="radio" name="status${book.user_book_id}" id="want${book.user_book_id}" ${book.status === 'want_to_read' ? 'checked' : ''} />
                                    <label for="want${book.user_book_id}">Want to Read</label>
                                </div>
                            </div>
                        </td>
                    `;
                    tableBody.appendChild(row);

                    document.getElementById(`read${book.user_book_id}`).addEventListener('change', () => updateStatus(book.user_book_id, 'read'));
                    document.getElementById(`reading${book.user_book_id}`).addEventListener('change', () => updateStatus(book.user_book_id, 'reading'));
                    document.getElementById(`want${book.user_book_id}`).addEventListener('change', () => updateStatus(book.user_book_id, 'want_to_read'));
                });
            } else {
                console.error('Error loading books:', data.message);
            }
        })
        .catch(error => console.error('Error fetching books:', error));

    const addBooksButton = document.getElementById('addBooksButton');
    if (addBooksButton) {
        addBooksButton.addEventListener('click', handleAddBook);
    }

    populateUserStatistics();

    setupExportButtons();
});

function handleAddBook(event) {
    event.preventDefault();
    window.location.href = 'browseBooks.html';
}

function updateStatus(user_book_id, status) {
    fetch('http://localhost/Boo2/php/updateBookProgress.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ user_book_id: user_book_id, status: status })
    })
    .then(response => {
        console.log(response);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.text();
    })
    .then(text => {
        console.log('Raw response:', text);
        try {
            const data = JSON.parse(text);
            console.log(data); 
            if (!data.success) {
                alert('Nu s-a putut actualiza statusul: ' + data.message);
            } else {
                alert('Statusul a fost actualizat cu succes');
            }
        } catch (e) {
            console.error('Error parsing JSON:', e);
            alert('Failed to parse response: ' + text);
        }
    })
    .catch(error => {
        console.error('Error updating status:', error);
    });
}

// ------------------------------------------------------------------------------------------

async function getUserStatistics() {
    try {
        const response = await fetch(`http://localhost/Boo2/php/get_stats.php`);
        if (!response.ok) {
            throw new Error('Network response was not ok.');
        }
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching user statistics:', error);
        return null;
    }
  }
  
  async function populateUserStatistics() {
    const statistics = await getUserStatistics();
    if (statistics) {
        document.getElementById('totalBooksValue').textContent = statistics.total_books;
        document.getElementById('totalProgressValue').textContent = statistics.total_progress;
    }
  }
  
  function setupExportButtons() {
    document.getElementById('exportCsvButton').addEventListener('click', function() {
        window.location.href = `http://localhost/Boo2/php/export_csv.php`;
    });
  
    document.getElementById('exportDocBookButton').addEventListener('click', function() {
        window.location.href = `http://localhost/Boo2/php/export_docbook.php`;
    });
  }
