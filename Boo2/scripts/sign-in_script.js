function loginUser(event) {
    event.preventDefault();

    const form = document.getElementById('loginForm');
    const formData = new FormData(form);

    fetch('http://localhost/Boo2/php/login.php', {
        method: 'POST',
        body: JSON.stringify(Object.fromEntries(formData.entries())),
        headers: {
            'Content-Type': 'application/json',
        },
    })
    .then((response) => response.json())
    .then((data) => {
        if (data.user_id) {
            alert('Autentificare reusită!');
            if (data.redirect) {
                window.location.href = data.redirect;
            } else {
                console.error('URL de redirectionare lipsa în raspunsul JSON');
            }
        } else {
            alert(data.message);
        }
    })
    .catch((error) => {
        console.error('Eroare:', error);
        alert('A aparut o eroare la autentificare!');
    });
}
