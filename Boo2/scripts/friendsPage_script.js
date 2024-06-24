document.addEventListener("DOMContentLoaded", function () {
    fetch("http://localhost/Boo2/php/getSuggestedPersons.php")
      .then((response) => response.text()) 
      .then((text) => {
        console.log("Raw response:", text); 
        try {
          const data = JSON.parse(text); 
          if (data.success) {
            const suggestedList = document.querySelector(
              ".wrapper__suggested ul"
            );
            data.suggestedPersons.forEach((username) => {
              const listItem = document.createElement("li");
              listItem.innerHTML = `
                              <i class="fa-regular fa-user fa-lg"></i>
                              <p>${username}</p>
                              <button onclick="sendFriendRequest('${username}')">
                                  <i class="fa-solid fa-plus fa-lg"></i>
                                  Add
                              </button>
                          `;
              suggestedList.appendChild(listItem);
            });
          } else {
            console.error("Error fetching suggested persons:", data.message);
          }
        } catch (e) {
          console.error("Invalid JSON response:", e);
        }
      })
      .catch((error) =>
        console.error("Error fetching suggested persons:", error)
      );
  
    fetch("http://localhost/Boo2/php/getFriends.php")
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          const friendsList = document.querySelector(".wrapper__friendList ul");
          data.friends.forEach((friend) => {
            const listItem = document.createElement("li");
            listItem.innerHTML = `
            <i class="fa-regular fa-user fa-lg"></i>
            <p>${friend.username}</p>
            <button onclick="removeFriend('${friend.username}')">
              <i class="fa-solid fa-trash fa-lg"></i>
              Remove
            </button>
          `;
            friendsList.appendChild(listItem);
          });
        } else {
          console.error("Error fetching friends:", data.message);
        }
      })
      .catch((error) => console.error("Error fetching friends:", error));
  
    document
      .getElementById("addFriendButton")
      .addEventListener("click", function () {
        const username = document.getElementById("searchInput").value.trim();
        if (username) {
          sendFriendRequest(username);
        } else {
          alert("Please enter a username.");
        }
      });
  });
  
  function sendFriendRequest(username) {
    fetch("http://localhost/Boo2/php/sendFriendRequest.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ username: username }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Friend request sent to " + username);
          location.reload();
        } else {
          alert("Failed to send friend request: " + data.message);
        }
      })
      .catch((error) => console.error("Error sending friend request:", error));
  }
  
  function removeFriend(username) {
    fetch("http://localhost/Boo2/php/removeFriend.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ username: username }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert("Friend removed: " + username);
          location.reload();
        } else {
          alert("Failed to remove friend: " + data.message);
        }
      })
      .catch((error) => console.error("Error removing friend:", error));
  }