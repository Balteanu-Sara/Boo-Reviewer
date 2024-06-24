document.addEventListener("DOMContentLoaded", function() {
    fetch("http://localhost/Boo2/php/checkSession.php")
      .then(response => response.json())
      .then(data => {
        if (data.isLoggedIn) {
          document.getElementById('sidebar').innerHTML = `
            <div class="sideBar">
              <div class="sideBar__image">
                <img src="styles/images/logouri/logo-color.png" alt="Boo Logo" class="sideBar__image--mod" />
              </div>
              <ul class="sideBar__list">
                <li>
                  <i class="fa fa-home fa-lg" aria-hidden="true">
                    <a href="loggedPage.html" class="simple-text">Home</a>
                  </i>
                </li>
                <li>
                  <i class="fa fa-book fa-lg" aria-hidden="true">
                    <a href="myBooks.html" class="simple-text">My books</a>
                  </i>
                </li>
                <li>
                  <i class="fa fa-users fa-lg" aria-hidden="true">
                    <a href="communityPage.html" class="simple-text">Community</a>
                  </i>
                </li>
                <li>
                  <i class="fa fa-pie-chart fa-lg" aria-hidden="true">
                    <a href="newsPage.html" class="simple-text">News</a>
                  </i>
                </li>
              </ul>
              <ul class="sideBar__special">
                <li>
                  <i class="fa fa-sign-out"
							><a href="php/logout.php" class="simple-text">Sign Out</a></i
						>
                </li>
              </ul>
            </div>
          `;

          document.getElementById('wrapper__header').innerHTML = `
          <div class="wrapper__header">
            <div class="wrapper__header--mod1">
              <button
							type="button"
							onclick="window.location.href='browseBooks.html'"
						>
							<i class="fa-solid fa-magnifying-glass fa-lg"> Browse Books</i>
						</button>
            </div>
            <div class="wrapper__header--mod2">
              <div class="button">
                <i class="fa fa-user fa-lg"></i>
                <a href="profilePage.html">Profile</a>
              </div>
              <div class="button">
                <i class="fa fa-envelope fa-lg"></i>
                <a href="notificationsPage.html">Notifications</a>
              </div>
              <div class="button">
                <i class="fa fa-heart fa-lg"></i>
                <a href="friendsPage.html">Friends</a>
              </div>
            </div>
             </div>
          `;
        } else {
          document.getElementById('sidebar').innerHTML = `
            <div class="sideBar">
              <div class="sideBar__image">
                <img src="styles/images/logouri/logo-color.png" class="sideBar__image--mod" alt="Boo Logo" />
              </div>
              <ul class="sideBar__list">
                <li>
                  <i class="fa fa-home fa-lg" aria-hidden="true">
                    <a href="sign-in.html" class="simple-text">Home</a>
                  </i>
                </li>
                <li>
                  <i class="fa fa-book fa-lg" aria-hidden="true">
                    <a href="sign-in.html" class="simple-text">My books</a>
                  </i>
                </li>
                <li>
                  <i class="fa fa-users fa-lg" aria-hidden="true">
                    <a href="sign-in.html" class="simple-text">Community</a>
                  </i>
                </li>
                <li>
                  <i class="fa fa-pie-chart fa-lg" aria-hidden="true">
                    <a href="sign-in.html" class="simple-text">News</a>
                  </i>
                </li>
              </ul>
            </div>
          `;

          document.getElementById('wrapper__header').innerHTML = `
           
             <div class="wrapper__headerNotLogged">
    <div class="button"><a href="sign-in.html">Sign in</a></div>
    <div class="button">
      <a href="createAccount.html">Sign up</a>
    </div>
  </div>
          `;

        }
      })
      .catch(error => console.error("Error checking session:", error));
  });