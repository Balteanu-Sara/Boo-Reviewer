document.addEventListener("DOMContentLoaded", function () {
    function removeImages(htmlString) {
      const parser = new DOMParser();
      const doc = parser.parseFromString(htmlString, "text/html");
      const images = doc.querySelectorAll("img");
      images.forEach((img) => img.remove());
      return doc.body.innerHTML;
    }
  
    fetch("http://localhost/Boo2/php/fetchGenres.php")
      .then((response) => response.json())
      .then((data) => {
        if (data.success) {
          const categoriesList = document.getElementById("categories-list");
          const descriptions = data.descriptions;
          for (const [genre, description] of Object.entries(descriptions)) {
            const listItem = document.createElement("li");
            listItem.innerHTML = `<span>${capitalizeFirstLetter(
              genre
            )}:</span> ${description}`;
            categoriesList.appendChild(listItem);
          }
        } else {
          console.error("Error fetching genre descriptions:", data.message);
        }
      })
      .catch((error) =>
        console.error("Error fetching genre descriptions:", error)
      );
 
    fetch("http://localhost/Boo2/php/fetchRssNews.php")
      .then((response) => response.json())
      .then((data) => {
        const items = data.channel.item;
        let html = "";
        items.forEach((el) => {
          let title = el.title;
          let link = el.link;
          let description = el.description;
          let pubDate = new Date(el.pubDate).toLocaleDateString();
          html += `
            <div class="news-item" style="margin-bottom: 20px;">
              <h3>${title}</h3>
              <p><em>${pubDate}</em></p>
              <p>${description}</p>
              <a href="${link}" target="_blank">Read more</a>
            </div>
          `;
        });
        document.getElementById("news-list").innerHTML = html;
      })
      .catch((err) => console.error("Failed to fetch RSS feed:", err));
  

    fetch("http://localhost/Boo2/php/fetchRssRecommendations.php")
      .then((response) => response.json())
      .then((data) => {
        const items = data.channel.item;
        let html = "";
        items.forEach((el) => {
          let title = el.title;
          let link = el.link;
          let description = removeImages(el.description);
          let pubDate = new Date(el.pubDate).toLocaleDateString();
          html += `
          <div class="recommendation-item" style="margin-bottom: 20px;">
            <h3>${title}</h3>
            <p><em>${pubDate}</em></p>
            <p>${description}</p>
          </div>
        `;
        });
        document.getElementById("recommendations-list").innerHTML = html;
      })
      .catch((err) => console.error("Failed to fetch RSS feed:", err));
  });
  
  function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }