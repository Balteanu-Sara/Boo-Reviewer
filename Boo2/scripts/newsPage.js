function displayRSSItems(category, rssUrl, containerId) {
    fetch(rssUrl, {
        method: "GET",
        headers: {
            "Content-Type": "application/rss+xml",
        },
    })
        .then((response) => response.text())
        .then((data) => {
            const parser = new DOMParser();
            const xml = parser.parseFromString(data, "text/xml");
            const items = xml.querySelectorAll("item");

            const rssItemsElement = document.getElementById(containerId);
            rssItemsElement.innerHTML = ""; 

            const noReviewsItem =
                items.length === 1 &&
                items[0].querySelector("title").textContent ===
                    "Nu exista recenzii momentan";

            if (noReviewsItem) {
                const noItemsMessage = document.createElement("li");
                noItemsMessage.textContent =
                    items[0].querySelector("description").textContent;
                rssItemsElement.appendChild(noItemsMessage);
            } else {
                items.forEach((item) => {
                    const title = item.querySelector("title").textContent;
                    const link = item.querySelector("link").textContent;
                    const description =
                        item.querySelector("description").textContent;
                    const pubDate = new Date(
                        item.querySelector("pubDate").textContent
                    );

                    const listItem = document.createElement("li");

                    const linkElement = document.createElement("a");
                    linkElement.setAttribute("href", link);
                    linkElement.textContent = title;
                    listItem.appendChild(linkElement);

                    const descriptionElement = document.createElement("p");
                    descriptionElement.textContent = description;
                    listItem.appendChild(descriptionElement);

                    const pubDateElement = document.createElement("p");
                    const formattedDate = `${pubDate.toUTCString().slice(0, -4)}`; 
                    pubDateElement.textContent = formattedDate;
                    listItem.appendChild(pubDateElement);

                    rssItemsElement.appendChild(listItem);
                });
            }
        })
        .catch((error) =>
            console.error(
                `Error fetching ${category.toLowerCase()} RSS feed:`,
                error
            )
        );
}

function fetchAndDisplayBooks() {
    const rssUrl = "http://localhost/Boo2/php/rss_topBooks.php"; 

    fetch(rssUrl)
        .then((response) => response.text())
        .then((xml) => {
            const parser = new DOMParser();
            const xmlDoc = parser.parseFromString(xml, "text/xml");

            const items = xmlDoc.querySelectorAll("channel > item");

            const rssTopBooksContainer = document.getElementById("rssTopBooks");

            rssTopBooksContainer.innerHTML = "";

            const noTopBooks =
                items.length === 1 &&
                items[0].querySelector("title").textContent ===
                    "Nu exista carti recenzate momentan";

            if (noTopBooks) {
                const noItemsMessage = document.createElement("li");
                noItemsMessage.textContent =
                    items[0].querySelector("description").textContent;
                rssTopBooksContainer.appendChild(noItemsMessage);
            } else {
                items.forEach((item) => {
                    const title = item.querySelector("title").textContent;
                    const link = item.querySelector("link").textContent;
                    const description =
                        item.querySelector("description").textContent;
                    const pubDate = item.querySelector("pubDate").textContent;

                    const li = document.createElement("li");
                    const h3 = document.createElement("h3");
                    const pDescription = document.createElement("p");
                    const pPubDate = document.createElement("p");
                    const a = document.createElement("a");

                    h3.textContent = title;
                    const spanIcon = document.createElement("span");
                    spanIcon.className = "fa fa-arrow-right";
                    spanIcon.setAttribute("aria-hidden", "true");

                    const spanText = document.createElement("span");
                    spanText.textContent = "Read more";

                    a.appendChild(spanIcon);
                    a.appendChild(spanText);

                    a.href = link;
                    pDescription.textContent = description;
                    pPubDate.textContent = `Published on: ${pubDate}`;

                    h3.appendChild(a);
                    li.appendChild(h3);
                    li.appendChild(pDescription);
                    li.appendChild(pPubDate);

                    rssTopBooksContainer.appendChild(li);
                });
            }
        })
        .catch((error) => {
            console.error("Error fetching RSS feed:", error);
        });
}

displayRSSItems(
    "Recenzii noi",
    "http://localhost/Boo2/php/rss_reviews.php",
    "rssReviews"
);

displayRSSItems(
    "Volume noi de interes",
    "http://localhost/Boo2/php/rss_books.php",
    "rssNewBooks"
);

document.addEventListener("DOMContentLoaded", fetchAndDisplayBooks);