function seeMore(bookId) {
  window.location.href = "seeReview.html?bookId=" + bookId;
}

window.onload = function() {
  const urlParams = new URLSearchParams(window.location.search);
  const bookId = urlParams.get('bookId');
  if (bookId) {
      fetchBookReview(bookId);
  }
};

function fetchBookReview(bookId) {
  fetch(`http://localhost/Boo2/php/fetchReview.php?bookId=${bookId}`)
      .then(response => response.json())
      .then(data => {
          const userReviewContainer = document.getElementById('userReviewContainer');
          if (data.userHasReview) {
              userReviewContainer.innerHTML = `
                  <div class="wrapper__containersReview">
                      <div class="wrapper__containersReview__quote">
                          <h2>Your review</h2>
                          <blockquote>${data.userReview}</blockquote>
                      </div>
                      <h3 class="wrapper__containersReview__likes"><i class="fa fa-regular fa-heart"></i> Liked by ${data.likes} readers</h3>
                  </div>
              `;
          } else {
              userReviewContainer.innerHTML = `
                  <div class="wrapper__containers">
                      <h2>Write your review!</h2>
                      <textarea name="review" id="review" placeholder="Type in your review..."></textarea>
                      <button onclick="postReview(${bookId})">Post</button>
                  </div>
              `;
          }
          loadComments(data.comments);
      })
      .catch(error => console.error('Error:', error));
}

function postReview(bookId) {
  const reviewContent = document.getElementById('review').value;
  fetch(`postReview.php`, {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json',
      },
      body: JSON.stringify({ bookId: bookId, review: reviewContent }),
  })
  .then(response => response.json())
  .then(data => {
      if (data.success) {
          location.reload();
      } else {
          console.error('Error posting review:', data.message);
      }
  })
  .catch(error => console.error('Error:', error));
}

function loadComments(comments) {
  const commentsList = document.getElementById('commentsList');
  comments.forEach(comment => {
      const commentElement = document.createElement('li');
      commentElement.classList.add('wrapper__containersReview__comments__list__element');
      commentElement.innerHTML = `
          <p class="wrapper__containersReview__comments__list__element--mod1">${comment.user}</p>
          <p class="wrapper__containersReview__comments__list__element--mod2">${comment.text}</p>
      `;
      commentsList.appendChild(commentElement);
  });
}
