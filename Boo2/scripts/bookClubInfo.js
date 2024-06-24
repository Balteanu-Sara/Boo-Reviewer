document.addEventListener("DOMContentLoaded", function() {
    const urlParams = new URLSearchParams(window.location.search);
    const clubId = urlParams.get('club_id');

    if (clubId) {
        fetch(`http://localhost/Boo2/php/getClubInfo.php?club_id=${clubId}`)
            .then(response => response.json())
            .then(data => {
                const currentUserId = Number(data.currentUserId);
                console.log(currentUserId);
                document.getElementById('club-name').innerHTML = data.club_name;
                document.getElementById('member-count').textContent = data.member_count;
                document.getElementById('review-count').textContent = data.review_count;
                document.getElementById('books-read-count').textContent = data.books_read_count;

                const topBooksList = document.getElementById('top-books-list');
                topBooksList.innerHTML = '';

                data.top_books.forEach((book, index) => {
                    if (index < 3) {
                        const listItem = document.createElement('li');
                        const link = document.createElement('a');
                        link.href = `http://localhost/Boo2/bookSummary.html?book_id=${book.book_id}`;
                        link.textContent = `${book.title}`;
                        link.target = '_blank';
                        listItem.appendChild(link);
                        topBooksList.appendChild(listItem);
                    }
                });

                const activityTableBody = document.querySelector('.card-body tbody');
                activityTableBody.innerHTML = '';

                data.activity.forEach(activity => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${activity.username}</td>
                        <td><a href="http://localhost/Boo2/bookSummary.html?book_id=${activity.book_id}" target="_blank">${activity.title}</a></td>
                        <td><span class="status ${getStatusClass(activity.status)}"></span> ${activity.status.replace(/_/g, ' ')}</td>
                    `;
                    activityTableBody.appendChild(row);
                });

                const membersContainer = document.querySelector('.members .card-body');
                membersContainer.innerHTML = '';

                fetch("http://localhost/Boo2/php/getFriends.php")
                    .then(response => response.json())
                    .then(friendsData => {
                        fetch("http://localhost/Boo2/php/getPendingFriendRequests.php")
                            .then(response => response.json())
                            .then(pendingRequestsData => {
                                data.members.forEach(member => {
                                    const memberDiv = document.createElement('div');
                                    memberDiv.classList.add('member');

                                    const username = member.username ? member.username : 'Unknown User';
                                    const memberId = member.user_id;

                                    const isCurrentUser = memberId === currentUserId;

                                    console.log(username);
                                    console.log(memberId);
                                    console.log(currentUserId);
                                    console.log(isCurrentUser);

                                    const isFriend = friendsData.friends.some(friend => friend.username === username);

                                    const isRequestPending = pendingRequestsData.requests.some(request => request.to_user === username);

                                    if (isCurrentUser) {
                                        memberDiv.innerHTML = `
                                            <img src="styles/images/user-pic.jpg" alt="user image" />
                                            <div class="add-friend">
                                                <h4>${username}</h4>
                                                <div class="no-button">
                                                    <span>(You)</span>
                                                </div>
                                            </div>
                                            <br>
                                        `;
                                    } else {
                                        memberDiv.innerHTML = `
                                            <img src="styles/images/user-pic.jpg" alt="user image" />
                                            <div class="add-friend">
                                                <h4>${username}</h4>
                                                <div class="${isFriend ? 'remove-button' : (isRequestPending ? 'request-sent' : 'addFriend-button')}">
                                                    <button onclick="${isFriend ? `removeFriend('${username}')` : (isRequestPending ? `alert('Request already sent to ${username}')` : `sendFriendRequest('${username}')`)}">
                                                        <i class="fa-solid fa-${isFriend ? 'trash' : (!isRequestPending ? 'plus' : '')} fa-lg"></i>
                                                        ${isFriend ? 'Remove' : (isRequestPending ? 'Request sent' : 'Add')}
                                                    </button>
                                                </div>
                                            </div>
                                            <br>
                                        `;
                                    }
                                    membersContainer.appendChild(memberDiv);
                                });
                            })
                            .catch(error => console.error('Error fetching pending friend requests:', error));
                    })
                    .catch(error => console.error('Error fetching friends:', error));
            })
            .catch(error => console.error('Error fetching club info:', error));
    }
});

function getStatusClass(status) {
    switch (status) {
        case 'read':
            return 'purple';
        case 'want_to_read':
            return 'pink';
        case 'reading':
            return 'blue';
        default:
            return '';
    }
}

function sendFriendRequest(username) {
    fetch("http://localhost/Boo2/php/sendFriendRequest.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ username: username }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Friend request sent to " + username);
            location.reload();
        } else {
            alert("Failed to send friend request: " + data.message);
        }
    })
    .catch(error => console.error("Error sending friend request:", error));
}

function removeFriend(username) {
    fetch("http://localhost/Boo2/php/removeFriend.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ username: username }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Friend removed: " + username);
            location.reload();
        } else {
            alert("Failed to remove friend: " + data.message);
        }
    })
    .catch(error => console.error("Error removing friend:", error));
}
