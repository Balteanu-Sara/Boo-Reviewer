function filterBooks() {
  var input,
    filter,
    table,
    rows,
    title,
    author,
    genre,
    year,
    publisher,
    i,
    txtValue,
    noMatch;

  input = document.getElementById("searchInput");
  filter = input.value.toLowerCase();
  table = document.getElementById("booksTable");
  rows = table.getElementsByClassName("myBooks-table__row");
  noMatch = true;

  for (i = 0; i < rows.length; i++) {
    var cells = rows[i].getElementsByTagName("td");

    title = cells[1];
    author = cells[2];
    genre = cells[3];
    year = cells[4];
    publisher = cells[5];

    if (title || author || genre || year || publisher) {
      txtValue = title.textContent || title.innerText;
      txtValue += " " + (author.textContent || author.innerText);
      txtValue += " " + (genre.textContent || genre.innerText);
      txtValue += " " + (year.textContent || year.innerText);
      txtValue += " " + (publisher.textContent || publisher.innerText);
      if (txtValue.toLowerCase().indexOf(filter) > -1) {
        rows[i].style.display = "";
        noMatch = false;
      } else {
        rows[i].style.display = "none";
      }
    }
  }
}