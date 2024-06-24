document.addEventListener("DOMContentLoaded", function () {
    fetch("http://localhost/Boo2/php/getNotifications.php")
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          const notificationsList = document.querySelector(
            ".wrapper__notifs__list"
          );
          data.notifications.forEach((notification) => {
            const listItem = document.createElement("li");
            listItem.classList.add("wrapper__notifs__list__element");
  
            listItem.dataset.notificationId = notification.notification_id;

            let notificationContent = "";
            switch (notification.type) {
              case "friend_request":
                notificationContent = `
                    <i class="fa-regular fa-user fa-lg"></i>
                    <p>${notification.sender} sent you a friend request!</p>
                    <div class="wrapper__notifs__list__element__pair">
                      <button class="accept" data-id="${notification.notification_id}">
                        <i class="fa-solid fa-check fa-lg"></i>
                        Accept
                      </button>
                      <button class="remove" data-id="${notification.notification_id}">
                        <i class="fa-solid fa-trash fa-lg"></i>
                        Remove
                      </button>
                    </div>
                  `;
                break;
              case "friend_accept":
                notificationContent = `
                    <i class="fa-regular fa-user fa-lg"></i>
                    <p>${notification.sender} accepted your friend request. Now you are friends!</p>
                  `;
                break;
              default:
                notificationContent = `
                    <i class="fa-regular fa-bell fa-lg"></i>
                    <p>New notification</p>
                  `;
            }
  
            listItem.innerHTML = notificationContent;
            notificationsList.appendChild(listItem);
          });
  
          addNotificationEventListeners();
        } else {
          console.error("Error fetching notifications:", data.message);
        }
      })
      .catch((error) => console.error("Error fetching notifications:", error));
  });
  
  function addNotificationEventListeners() {
    document.querySelectorAll(".accept").forEach(function (button) {
      button.addEventListener("click", function () {
        let notificationId = this.dataset.id;
        handleFriendRequest(notificationId, "accept");
      });
    });
  
    document.querySelectorAll(".remove").forEach(function (button) {
      button.addEventListener("click", function () {
        let notificationId = this.dataset.id;
        handleFriendRequest(notificationId, "remove");
      });
    });
  }
  
  function handleFriendRequest(notificationId, action) {
    fetch("http://localhost/Boo2/php/handleFriendRequest.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ notificationId: notificationId, action: action }),
    })
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          alert(data.message);
          const notificationElement = document.querySelector(
            `.wrapper__notifs__list__element[data-notification-id="${notificationId}"]`
          );
          if (notificationElement) {
            notificationElement.remove();
          }
        } else {
          alert("Failed to process friend request: " + data.message);
        }
      })
      .catch((error) => console.error("Error processing friend request:", error));
  }