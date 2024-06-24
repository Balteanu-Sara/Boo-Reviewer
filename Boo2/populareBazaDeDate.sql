DROP TABLE IF EXISTS user_groups;
DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS notification_likes;
DROP TABLE IF EXISTS notification_friend_reviews;
DROP TABLE IF EXISTS notification_group_posts;
DROP TABLE IF EXISTS friend_requests;
DROP TABLE IF EXISTS friends;
DROP TABLE IF EXISTS user_books;
DROP TABLE IF EXISTS user_genres;
DROP TABLE IF EXISTS notifications;
DROP TABLE IF EXISTS books;
DROP TABLE IF EXISTS book_clubs;
DROP TABLE IF EXISTS genres;
DROP TABLE IF EXISTS users;


CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE genres (
    genre_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) UNIQUE NOT NULL
);

CREATE TABLE books (
    book_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    publisher VARCHAR(255),
    year INT,
    genre_id INT,
    image_url VARCHAR(255),
    summary varchar(1000),
    published_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (genre_id) REFERENCES genres(genre_id)
);

CREATE TABLE user_books (
    user_book_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    book_id INT,
    status ENUM('read', 'want_to_read', 'reading') DEFAULT 'want_to_read',
    rating INT,
    review TEXT,
    progress INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (book_id) REFERENCES books(book_id)
);

CREATE TABLE book_clubs (
    group_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    related_id INT,
    type ENUM('comment', 'like', 'friend_accept', 'friend_review', 'group_post', 'friend_request'),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (related_id) REFERENCES users(user_id)
);

CREATE TABLE notification_likes (
    id INT PRIMARY KEY,
    notification_id INT,
    review_id INT,
    like_id INT,
    FOREIGN KEY (notification_id) REFERENCES notifications(notification_id),
    FOREIGN KEY (review_id) REFERENCES user_books(user_book_id)
);

CREATE TABLE notification_friend_reviews (
    id INT PRIMARY KEY,
    notification_id INT,
    friend_id INT,
    review_id INT,
    FOREIGN KEY (notification_id) REFERENCES notifications(notification_id),
    FOREIGN KEY (friend_id) REFERENCES users(user_id),
    FOREIGN KEY (review_id) REFERENCES user_books(user_book_id)
);

CREATE TABLE notification_group_posts (
    id INT PRIMARY KEY,
    notification_id INT,
    group_id INT,
    post_id INT,
    FOREIGN KEY (notification_id) REFERENCES notifications(notification_id),
    FOREIGN KEY (group_id) REFERENCES book_clubs(group_id)
);

CREATE TABLE friend_requests (
    request_id INT AUTO_INCREMENT PRIMARY KEY,
    notification_id INT,
    sender_id INT,
    receiver_id INT,
    status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (notification_id) REFERENCES notifications(notification_id),
    FOREIGN KEY (sender_id) REFERENCES users(user_id),
    FOREIGN KEY (receiver_id) REFERENCES users(user_id)
);

CREATE TABLE friends (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user1_id INT NOT NULL,
    user2_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user1_id) REFERENCES users(user_id),
    FOREIGN KEY (user2_id) REFERENCES users(user_id),
    UNIQUE KEY unique_friendship (user1_id, user2_id)
);


CREATE TABLE user_groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    group_id INT,
    join_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (group_id) REFERENCES book_clubs(group_id)
);

CREATE TABLE user_genres (
    user_genre_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    genre_id INT,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (genre_id) REFERENCES genres(genre_id)
);

CREATE TABLE reviews (
    review_id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT,
    user_id INT,
    review TEXT NOT NULL,
    likes INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (book_id) REFERENCES books(book_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- populare tabela genres
INSERT INTO genres (name) VALUES ('Art');
INSERT INTO genres (name) VALUES ('Biography');
INSERT INTO genres (name) VALUES ('Business');
INSERT INTO genres (name) VALUES ('Children\'s');
INSERT INTO genres (name) VALUES ('Comics');
INSERT INTO genres (name) VALUES ('Crime');
INSERT INTO genres (name) VALUES ('Fantasy');
INSERT INTO genres (name) VALUES ('Fiction');
INSERT INTO genres (name) VALUES ('History');
INSERT INTO genres (name) VALUES ('Horror');
INSERT INTO genres (name) VALUES ('Humor & Comedy');
INSERT INTO genres (name) VALUES ('Music');
INSERT INTO genres (name) VALUES ('Mystery');
INSERT INTO genres (name) VALUES ('Nonfiction');
INSERT INTO genres (name) VALUES ('Philosophy');
INSERT INTO genres (name) VALUES ('Poetry');
INSERT INTO genres (name) VALUES ('Psychology');
INSERT INTO genres (name) VALUES ('Religion');
INSERT INTO genres (name) VALUES ('Romance');
INSERT INTO genres (name) VALUES ('Science');
INSERT INTO genres (name) VALUES ('Science Fiction');
INSERT INTO genres (name) VALUES ('Spirituality');
INSERT INTO genres (name) VALUES ('Sports');
INSERT INTO genres (name) VALUES ('Thriller');
INSERT INTO genres (name) VALUES ('Travel');

INSERT INTO books (title , author , publisher , year , genre_id, image_url) VALUES
('Housemates', 'Emma Copley Eisenberg', 'Hogarth', 2024,  1, 'http://localhost/Boo2/styles/images/books/art1.jpg'),
('Blue Ruin', 'Hari Kunzru', 'Hardcover', 2024, 1, 'http://localhost/Boo2/styles/images/books/art2.jpg'),
('The Lady Waiting', 'Magdalena Zyzak', 'Knopf', 2024,  1, 'http://localhost/Boo2/styles/images/books/art3.jpg'),
('The Mother Act', 'Heidi Reimer', 'Dutton', 2024,  1, 'http://localhost/Boo2/styles/images/books/art4.jpg'),
('The Beauties', 'Lauren Chater', 'Simon & Schuster Australia', 2023,  1, 'http://localhost/Boo2/styles/images/books/art5.jpg'),
('The Hypocrite', 'Jo Hamya', 'Weidenfeld & Nicolson', 2024,  1, 'http://localhost/Boo2/styles/images/books/art6.jpg'),
('And Then? And Then? What Else?', 'Daniel Handler, Lemony Snicket', 'Liveright', 2024, 2 , 'http://localhost/Boo2/styles/images/books/biography1.jpg'),
('The Loves of Theodore Roosevelt: The Women Who Created a President', 'Edward F. O\'Keefe', 'Simon & Schuster', 2024,  2, 'http://localhost/Boo2/styles/images/books/biography2.jpg'),
('I Will Show You How It Was: The Story of Wartime Kyiv', 'Illia Ponomarenko', 'Bloomsbury Publishing', 2024,  2, 'http://localhost/Boo2/styles/images/books/biography3.jpg'),
('Thorns, Lust and Glory: The betrayal of Anne Boleyn', 'Estelle Paranque', 'Ebury Digital', 2024,  2, 'http://localhost/Boo2/styles/images/books/biography4.jpg'),
('Co-Intelligence: Living and Working with AI', 'Ethan Mollick', 'Virgin Digital', 2024,  3, 'http://localhost/Boo2/styles/images/books/business1.jpg'),
('The Creative Act: A Way of Being', 'Rick Rubin', 'Penguin Press', 2023,  3, 'http://localhost/Boo2/styles/images/books/business2.jpg'),
('Invisible Women: Data Bias in a World Designed for Men', 'Caroline Criado Pérez', 'Abrams Press', 2019,  3, 'http://localhost/Boo2/styles/images/books/business3.jpg'),
('Wonka', 'Sibéal Pounder', 'Puffin', 2023,  4, 'http://localhost/Boo2/styles/images/books/childrens1.jpg'),
('The Ickabog', 'J.K. Rowling', 'Little, Brown Books for Young Readers', 2020,  4, 'http://localhost/Boo2/styles/images/books/childrens2.jpg'),
('Jävla karlar', 'Andrev Walden', 'Polaris förlag', 2023,  4, 'http://localhost/Boo2/styles/images/books/childrens3.jpg'),
('River', 'Erin Hunter', 'HarperCollins', 2022,  4, 'http://localhost/Boo2/styles/images/books/childrens4.jpg'),
('Iceberg', 'Jennifer A. Nielsen', 'Scholastic Press', 2023,  4, 'http://localhost/Boo2/styles/images/books/childrens5.jpg'),
('The Beatryce Prophecy', 'Kate DiCamillo', 'Candlewick Press', 2021,  4, 'http://localhost/Boo2/styles/images/books/childrens6.jpg'),
('Hooky', 'Míriam Bonastre Tur', 'Clarion Books', 2021,  5, 'http://localhost/Boo2/styles/images/books/comics1.jpg'),
('Diary of an Awesome Friendly Kid: Rowley Jefferson\'s Journal', 'Jeff Kinney', 'Amulet Books', 2019,  5, 'http://localhost/Boo2/styles/images/books/comics2.jpg'),
('The Sad Ghost Club', 'Lize Meddings', 'Hodder Children\'s Books', 2021,  5, 'http://localhost/Boo2/styles/images/books/comics3.jpg'),
('The Perfect Marriage', 'Jeneva Rose', 'Bloodhound Books', 2020,  6, 'http://localhost/Boo2/styles/images/books/crime1.jpg'),
('Local Woman Missing', 'Mary Kubica', 'Park Row', 2021,  6, 'http://localhost/Boo2/styles/images/books/crime2.jpg'),
('Ward D', 'Freida McFadden', 'Hollywood Upstairs Press', 2023,  6, 'http://localhost/Boo2/styles/images/books/crime3.jpg'),
('Hidden Pictures', 'Jason Rekula', 'Flatiron Books', 2022,  6, 'http://localhost/Boo2/styles/images/books/crime4.jpg'),
('Mad Honey', 'Jodi Picoul', 'Ballantine', 2022,  6, 'http://localhost/Boo2/styles/images/books/crime5.jpg'),
('A Talent for Murder', 'Peter Swanson', 'William Morrow', 2024,  6, 'http://localhost/Boo2/styles/images/books/crime6.jpg'),
('Tress of the Emerald Sea', 'Brandon Sanderson', 'Dragonsteel Entertainment', 2023,  7, 'http://localhost/Boo2/styles/images/books/fantasy1.jpg'),
('This is How You Lose the Time War', 'Amal El-Mohtar, Max Gladstone', 'Saga Press', 2019,  7, 'http://localhost/Boo2/styles/images/books/fantasy2.jpg'),
('Lightlark', 'Alex Aster', 'Harry N. Abrams', 2022,  7, 'http://localhost/Boo2/styles/images/books/fantasy3.jpg'),
('The Midnight Library', 'Matt Haig', 'Viking', 2020,  7, 'http://localhost/Boo2/styles/images/books/fantasy4.jpg'),
('Happy Place', 'Emily Henry', 'Berkley', 2023,  8, 'http://localhost/Boo2/styles/images/books/fiction1.jpg'),
('Yellowface', 'R.F. Kuang', 'William Morrow', 2023,  8, 'http://localhost/Boo2/styles/images/books/fiction2.jpg'),
('The Guest List', 'Lucy Fole', 'William Morrow', 2020,  8, 'http://localhost/Boo2/styles/images/books/fiction3.jpg'),
('Cunning Folk: Life in the Era of Practical Magic', 'Tabitha Stanmore', 'Bloomsbury Publishing', 2024,  9, 'http://localhost/Boo2/styles/images/books/history1.jpg'),
('The Diamond Eye', 'Kate Quinn', 'William Morrow', 2022,  9, 'http://localhost/Boo2/styles/images/books/history2.jpg'),
('The Marriage Portrait', 'Maggie O\'Farrell', 'Knopf Publishing Group', 2022,  9, 'http://localhost/Boo2/styles/images/books/history3.jpg'),
('One by One', 'Freida McFadden', 'Hollywood Upstairs Press', 2020,  10, 'http://localhost/Boo2/styles/images/books/horror1.jpg'),
('The Locked Door', 'Freida McFadden', 'Hollywood Upstairs Press', 2021,  10, 'http://localhost/Boo2/styles/images/books/horror2.jpg'),
('Never Lie', 'Freida McFadden', 'Hollywood Upstairs Press', 2022,  10, 'http://localhost/Boo2/styles/images/books/horror3.jpg'),
('Me Talk Pretty One Day', 'David Sedaris', 'Little, Brown and Company', 2001,  11, 'http://localhost/Boo2/styles/images/books/humor&comedy1.jpg'),
('A Man Called Ove', 'Fredrik Backman', 'Atria Books', 2014,  11, 'http://localhost/Boo2/styles/images/books/humor&comedy2.jpg'),
('The Princess Bride', 'William Goldman', 'Ballantine Books', 2003,  11, 'http://localhost/Boo2/styles/images/books/humor&comedy3.jpg'),
('Ella', 'Diane Richards', 'Amistad', 2024,  12, 'http://localhost/Boo2/styles/images/books/music1.jpg'),
('The Ballad of Darcy and Russell', 'Morgan Matson', 'Simon & Schuster Books for Young Readers', 2024,  12, 'http://localhost/Boo2/styles/images/books/music2.jpg'),
('Stay True', 'Hua Hsu', 'Doubleday', 2022,  12, 'http://localhost/Boo2/styles/images/books/music3.jpg'),
('The Maid', 'Nita Prose', 'Ballantine Books', 2022,  13, 'http://localhost/Boo2/styles/images/books/mystery1.jpg'),
('The Paris Apartment', 'Lucy Foley', 'William Morrow', 2022,  13, 'http://localhost/Boo2/styles/images/books/mystery2.jpg'),
('The Silent Patient', 'Alex Michaelides', 'Celadon Books', 2019,  13, 'http://localhost/Boo2/styles/images/books/mystery3.jpg'),
('How to Keep House While Drowning', 'K.C. Davis', 'S&S/Simon Element', 2022,  14, 'http://localhost/Boo2/styles/images/books/nonfiction1.jpg'),
('Caste: The Origins of Our Discontents', 'Isabel Wilkerson', 'Random House', 2020,  14, 'http://localhost/Boo2/styles/images/books/nonfiction2.jpg'),
('Don\'t Believe Everything You Think', 'Joseph Nguyen', 'No Publisher', 2022,  14, 'http://localhost/Boo2/styles/images/books/nonfiction3.jpg'),
('Humankind: A Hopeful History', 'Rutger Bregman', 'Brown and Company', 2020,  15, 'http://localhost/Boo2/styles/images/books/philosophy1.jpg'),
('Greenlights', 'Matthew McConaughey', 'Crown', 2020,  15, 'http://localhost/Boo2/styles/images/books/philosophy2.jpg'),
('Four Thousand Weeks: Time Management for Mortals', 'Oliver Burkeman', 'Farrar, Straus and Giroux', 2021,  15, 'http://localhost/Boo2/styles/images/books/philosophy3.jpg'),
('Home Body', 'Rupi Kaur', 'Simon & Schuster', 2020,  16, 'http://localhost/Boo2/styles/images/books/poetry1.jpg'),
('The Stationery Shop', 'Marjan Kamali', 'Gallery Books', 2019,  16, 'http://localhost/Boo2/styles/images/books/poetry2.jpg'),
('The Wren, the Wren', 'Anne Enright', 'W. W. Norton & Company', 2023,  16, 'http://localhost/Boo2/styles/images/books/poetry3.jpg'),
('The Perfect Child', 'Lucinda Berry', 'Thomas & Mercer', 2019,  17, 'http://localhost/Boo2/styles/images/books/psychology1.jpg'),
('The Psychology of Money', 'Morgan Housel', 'Harriman House', 2020,  17, 'http://localhost/Boo2/styles/images/books/psychology2.jpg'),
('Sociopath', 'Patric Gagne', 'Simon & Schuster', 2024,  17, 'http://localhost/Boo2/styles/images/books/psychology3.jpg'),
('The Rabbit Hutch', 'Tess Gunty', 'Knopf', 2022,  18, 'http://localhost/Boo2/styles/images/books/religion1.jpg'),
('The Funeral Ladies of Ellerie County', 'Claire Swinarski', 'Avon', 2024,  18, 'http://localhost/Boo2/styles/images/books/religion2.jpg'),
('The Stranger in the Lifeboat', 'Mitch Albom', 'Harper', 2021,  18, 'http://localhost/Boo2/styles/images/books/religion3.jpg'),
('The Paradise Problem', 'Christina Lauren', 'Gallery Books', 2024,  19, 'http://localhost/Boo2/styles/images/books/romance1.jpg'),
('Love at First Book', 'Jenn McKinlay', 'Berkley', 2024,  19, 'http://localhost/Boo2/styles/images/books/romance2.jpg'),
('The Honey Witch', 'Sydney J. Shields', 'Redhook', 2024,  19, 'http://localhost/Boo2/styles/images/books/romance3.jpg'),
('Bad Therapy: Why the Kids Aren\'t Growing Up', 'Abigail Shrier', 'Swift Press', 2024,  20, 'http://localhost/Boo2/styles/images/books/science1.jpg'),
('Burnout: The Secret to Unlocking the Stress Cycle', 'Emily Nagoski, Amelia Nagoski', 'Ballantine Books', 2019,  20, 'http://localhost/Boo2/styles/images/books/science2.jpg'),
('The Backyard Bird Chronicles', 'Amy Tan', ' Knopf', 2024,  20, 'http://localhost/Boo2/styles/images/books/science3.jpg'),
('Lost Ark Dreaming', 'Suyi Davies Okungbowa', 'Tordotcom', 2024,  21, 'http://localhost/Boo2/styles/images/books/scienceFiction1.jpg'),
('The Z Word', 'Lindsay King-Miller', 'Quirk Books', 2024,  21, 'http://localhost/Boo2/styles/images/books/scienceFiction2.jpg'),
('Lunar Boy', 'Jacinta Wibowo, Jessica Wibowo', 'HarperAlley', 2024,  21, 'http://localhost/Boo2/styles/images/books/scienceFiction3.jpg'),
('Breath: The New Science of a Lost Art', 'James Nestor', 'Riverhead Books', 2020,  22, 'http://localhost/Boo2/styles/images/books/spirituality1.jpg'),
('Somehow: Thoughts on Love', 'Anne Lamott', 'Riverhead Books', 2024,  22, 'http://localhost/Boo2/styles/images/books/spirituality2.jpg'),
('When You\'re Ready, This Is How You Heal', 'Brianna Wiest', 'Thought Catalog Book', 2022,  22, 'http://localhost/Boo2/styles/images/books/spirituality3.jpg'),
('Check & Mate', 'Ali Hazelwood', 'Young Readers', 2023,  23, 'http://localhost/Boo2/styles/images/books/sports1.jpg'),
('The Long Game', 'Elena Armas', 'Atria Books', 2023,  23, 'http://localhost/Boo2/styles/images/books/sports2.jpg'),
('Cleat Cute', 'Meryl Wilsner', 'Griffin', 2023,  23, 'http://localhost/Boo2/styles/images/books/sports3.jpg'),
('Wrong Place Wrong Time', 'Gillian McAllister', 'William Morrow', 2022,  24, 'http://localhost/Boo2/styles/images/books/thriller1.jpg'),
('Everyone Here Is Lying', 'Shari Lapena', 'Pamela Dorman Books', 2023,  24, 'http://localhost/Boo2/styles/images/books/thriller2.jpg'),
('Apples Never Fall', 'Liane Moriarty', 'Henry Holt and Co', 2021,  24, 'http://localhost/Boo2/styles/images/books/thriller3.jpg'),
('People We Meet on Vacation', 'Emily Henry', 'Berkley', 2021,  25, 'http://localhost/Boo2/styles/images/books/travel1.jpg'),
('The Paris Novel', 'Ruth Reichl', 'Random House', 2024,  25, 'http://localhost/Boo2/styles/images/books/travel2.jpg'),
('The Lincoln Highway', 'Amor Towles', 'Viking', 2021,  25, 'http://localhost/Boo2/styles/images/books/travel3.jpg');


UPDATE books
SET summary = '„Housemates” este o poveste captivantă despre viața împărtășită între colegi de apartament și dinamica complexă dintre ei. Într-un oraș aglomerat, cinci persoane total diferite decid să locuiască împreună, fiecare cu propriile vise, secrete și provocări. Romanul explorează cum se dezvoltă relațiile în timp, cum prietenia și tensiunea coabitează și cum fiecare dintre personaje învață lecții importante despre viață și toleranță. Oferă o privire profundă asupra legăturilor umane și a compromisurilor necesare pentru a trăi în armonie.'
WHERE title = 'Housemates' AND author = 'Emma Copley Eisenberg';

UPDATE books
SET summary = '„Blue Ruin” de Hari Kunzru este o explorare fascinantă a destrămării sociale și a misterelor ascunse sub suprafața cotidianului. Romanul urmărește un protagonist în timp ce navighează printr-o lume în schimbare rapidă, confruntându-se cu secrete din trecut și pericole din prezent. Cu o narațiune densă și personaje bine conturate, cartea examinează teme de identitate, răzbunare și redempție. „Blue Ruin” este un thriller psihologic care te ține în suspans până la ultima pagină, oferind totodată o reflecție profundă asupra naturii umane.'
WHERE title = 'Blue Ruin' AND author = 'Hari Kunzru';

UPDATE books
SET summary = '„The Lady Waiting” de Magdalena Zyzak este un roman istoric captivant care aduce la viață curtea regală și intrigile sale. Povestea urmărește viața unei doamne de onoare în timpul unei perioade tumultuoase din istoria Angliei. Prin ochii ei, cititorii sunt martori la secretele și luptele pentru putere care definesc viața de la curte. Personajele sunt complex dezvoltate, iar detaliile istorice sunt meticulos cercetate, oferind o lectură bogată și educativă. „The Lady Waiting” este o cronică a sacrificiului personal și a supraviețuirii în vremuri grele.'
WHERE title = 'The Lady Waiting' AND author = 'Magdalena Zyzak';

UPDATE books
SET summary = '„The Mother Act” de Heidi Reimer este un roman puternic despre maternitate, sacrificiu și rezistență. Urmărește povestea a două femei, fiecare confruntându-se cu provocările unice ale maternității într-un context modern. Prin luptele și succesele lor, cititorii sunt invitați să reflecteze asupra importanței familiei, prieteniei și auto-acceptării. Cartea este o explorare profundă a legăturilor care ne unesc și a forței interioare necesare pentru a naviga prin încercările vieții.'
WHERE title = 'The Mother Act' AND author = 'Heidi Reimer';

UPDATE books
SET summary = '„The Beauties” de Lauren Chater este o poveste emoționantă despre dragoste, pierdere și rezistență în fața adversităților vieții. Romanul explorează viața unei femei într-o perioadă turbulentă din istoria Australiei, aducând în prim-plan viziunea sa asupra lumii și alegerile pe care le face în fața provocărilor. Cu o narativă captivantă și personaje bine conturate, „The Beauties” oferă o perspectivă profundă asupra luptei pentru autonomie și identitate într-o societate rigidă și conservatoare.'
WHERE title = 'The Beauties' AND author = 'Lauren Chater';

UPDATE books
SET summary = '„The Hypocrite” de Jo Hamya este un roman contemporan provocator care explorează dilemele morale și identitatea într-o lume modernă. Povestea urmărește viața unui tânăr profesor, care se confruntă cu propria conștiință și cu presiunile sociale contradictorii. Prin prisma personajului principal, cartea abordează teme precum ipocrizia, loialitatea și complexitatea relațiilor umane. „The Hypocrite” oferă o introspecție asupra conflictelor interioare și a consecințelor alegerilor noastre.'
WHERE title = 'The Hypocrite' AND author = 'Jo Hamya';

UPDATE books
SET summary = '„And Then? And Then? What Else?” de Daniel Handler, cunoscut și sub pseudonimul Lemony Snicket, este o colecție captivantă de eseuri și povestiri care explorează diverse teme și experiențe umane. De la observații amuzante la reflecții filosofice profunde, autorul oferă cititorilor săi o incursiune fascinantă în lumea sa literară distinctivă. „And Then? And Then? What Else?” este o lectură care provoacă la gândire și oferă multiple perspective asupra vieții și artei narative.'
WHERE title = 'And Then? And Then? What Else?' AND author = 'Daniel Handler, Lemony Snicket';

UPDATE books
SET summary = '„The Loves of Theodore Roosevelt: The Women Who Created a President” de Edward F. O\'Keefe este o biografie captivantă care explorează impactul vital pe care femeile l-au avut asupra vieții și carierei politice a lui Theodore Roosevelt. Cartea aduce în prim-plan poveștile unor femei puternice și influente din viața acestui președinte emblematic, arătând cum relațiile personale au modelat deciziile sale publice și moștenirea sa istorică.'
WHERE title = 'The Loves of Theodore Roosevelt: The Women Who Created a President' AND author = 'Edward F. O\'Keefe';

UPDATE books
SET summary = '„I Will Show You How It Was: The Story of Wartime Kyiv” de Illia Ponomarenko este o cronică fascinantă a orașului Kyiv în timpul celui de-al Doilea Război Mondial. Cartea aduce în lumină experiențele oamenilor obișnuiți în fața terorii și incertitudinii războiului, oferind o perspectivă emoționantă și detaliată asupra vieții de zi cu zi într-o perioadă extrem de tumultuoasă.'
WHERE title = 'I Will Show You How It Was: The Story of Wartime Kyiv' AND author = 'Illia Ponomarenko';

UPDATE books
SET summary = '„Thorns, Lust and Glory: The Betrayal of Anne Boleyn” de Estelle Paranque este o explorare profundă a vieții fascinante și a morții tragice a Annei Boleyn, una dintre cele mai enigmatice figuri din istoria britanică. Cartea aduce în prim-plan aspecte mai puțin cunoscute ale vieții sale, inclusiv politică, religie și trădare. Printr-o combinație de cercetare meticuloasă și narativ captivant, „Thorns, Lust and Glory” oferă o perspectivă nouă și provocatoare asupra unei figuri istorice complexe.'
WHERE title = 'Thorns, Lust and Glory: The Betrayal of Anne Boleyn' AND author = 'Estelle Paranque';

UPDATE books
SET summary = '„Co-Intelligence: Living and Working with AI” de Ethan Mollick este o explorare esențială a modului în care inteligența artificială influențează viața noastră profesională și personală. Cartea examinează impactul AI asupra locurilor de muncă, economiei și societății în ansamblu, oferind perspective critice și soluții pentru a naviga într-o lume tot mai interconectată cu tehnologia.'
WHERE title = 'Co-Intelligence: Living and Working with AI' AND author = 'Ethan Mollick';

UPDATE books
SET summary = '„The Creative Act: A Way of Being” de Rick Rubin este o meditație profundă asupra creativității și impactului acesteia asupra vieții noastre. Cartea explorează procesele creative ale unor figuri emblematice din diverse domenii, oferind lecții și perspective care încurajează cititorii să își exploreze propria creativitate și să își dezvolte potențialul.'
WHERE title = 'The Creative Act: A Way of Being' AND author = 'Rick Rubin';

UPDATE books
SET summary = '„Invisible Women: Data Bias in a World Designed for Men” de Caroline Criado Pérez este o analiză revelatoare a modului în care datele și sistemele noastre sociale sunt construite pe baza unui model masculin, ignorând adesea perspectivele și nevoile femeilor. Cartea subliniază impactul profund al acestui bias asupra vieții cotidiene a femeilor și argumentează pentru o schimbare urgentă în modul în care colectăm, procesăm și utilizăm datele.'
WHERE title = 'Invisible Women: Data Bias in a World Designed for Men' AND author = 'Caroline Criado Pérez';

UPDATE books
SET summary = '„Wonka” de Sibéal Pounder este o aventură magică plină de surprize și mister. Inspirată de lumea îndrăgită creată de Roald Dahl, cartea urmărește povestea fascinantă a lui Willy Wonka și originea sa neobișnuită. Cu personaje extravagante și o poveste plină de farmec, „Wonka” captivă cititorii de toate vârstele.'
WHERE title = 'Wonka' AND author = 'Sibéal Pounder';

UPDATE books
SET summary = '„The Ickabog” de J.K. Rowling este o poveste captivantă despre curaj, prietenie și aventură, potrivită pentru cititorii tineri și adulți deopotrivă. Într-o lume plină de magie și mister, povestea urmărește aventurile unui copil curajos și a creaturilor fantastice pe care le descoperă în căutarea adevărului și a dreptății.'
WHERE title = 'The Ickabog' AND author = 'J.K. Rowling';

UPDATE books
SET summary = '„Jävla karlar” de Andrev Walden este o analiză sinceră și provocatoare a masculinității toxice și a impactului acesteia asupra indivizilor și societății. Cartea oferă o perspectivă critică asupra normelor de gen și invită la o reflecție profundă asupra modului în care putem să ne schimbăm percepțiile și comportamentele pentru a crea o lume mai echitabilă și mai inclusivă.'
WHERE title = 'Jävla karlar' AND author = 'Andrev Walden';

UPDATE books
SET summary = '„River” de Erin Hunter este o poveste emoționantă despre aventură și supraviețuire în sălbăticia naturii. Cartea urmărește călătoria unui tânăr râu care navighează prin provocările și pericolele pe care le întâlnește în drumul său spre maturitate. Cu personaje memorabile și o poveste captivantă, „River” este o lectură incitantă pentru iubitorii de ficțiune pentru tineret.'
WHERE title = 'River' AND author = 'Erin Hunter';

UPDATE books
SET summary = '„Iceberg” de Jennifer A. Nielsen este o aventură plină de suspans și mister într-o lume subacvatică fascinantă. Cartea explorează povestea unui grup de exploratori curajoși care descoperă secrete îngropate adânc sub ghețurile groenlandeze. Cu intrigi, trădări și răsturnări neașteptate, „Iceberg” este o lectură captivantă pentru toți iubitorii de aventură.'
WHERE title = 'Iceberg' AND author = 'Jennifer A. Nielsen';

UPDATE books
SET summary = '„The Beatryce Prophecy” de Kate DiCamillo este o poveste magică despre curaj, prietenie și descoperirea adevărului. Cartea urmărește călătoria unei fetițe neobișnuite și a unui călugăr în căutarea adevărului și a unor răspunsuri care pot salva lumea lor. Cu personaje pline de farmec și o poveste emoționantă, „The Beatryce Prophecy” va captiva cititorii de toate vârstele.'
WHERE title = 'The Beatryce Prophecy' AND author = 'Kate DiCamillo';

UPDATE books
SET summary = '„Hooky” de Míriam Bonastre Tur este o poveste fermecătoare despre magie, aventură și prietenie. Cartea urmărește viața unor gemeni magici care se aventura într-o lume plină de creaturi fantastice și pericole neașteptate. Cu ilustrații vibrante și o poveste captivantă, „Hooky” este o lectură perfectă pentru fanii genului fantasy.'
WHERE title = 'Hooky' AND author = 'Míriam Bonastre Tur';

UPDATE books
SET summary = '„The Sad Ghost Club” de Lize Meddings este o poveste grafică emoționantă despre singurătate, prietenie și acceptarea sinelui. Cartea urmărește călătoria unui grup de personaje diverse care se adună pentru a împărtăși experiențele lor și a descoperi că nu sunt singure în luptele lor interioare. Cu ilustrații captivante și o poveste profundă, „The Sad Ghost Club” este o lectură memorabilă pentru toți cei care se simt uneori marginalizați sau singuri.'
WHERE title = 'The Sad Ghost Club' AND author = 'Lize Meddings';

UPDATE books
SET summary = '„The Perfect Marriage” de Jeneva Rose este un thriller psihologic tensionat despre secretele întunecate din spatele unei căsnicii aparent perfecte. Cartea explorează complexitatea relațiilor umane și limitele la care oamenii pot merge pentru a-și apăra propria viață. Cu răsturnări de situație neașteptate și un final exploziv, „The Perfect Marriage” va ține cititorii cu sufletul la gură până la ultima pagină.'
WHERE title = 'The Perfect Marriage' AND author = 'Jeneva Rose';

UPDATE books
SET summary = '„Local Woman Missing” de Mary Kubica este un thriller psihologic captivant despre disparițiile misterioase a mai multor femei într-o mică comunitate. Cartea explorează impactul devastator asupra familiilor și prietenilor celor dispăruți și secretelor întunecate care ies la iveală în timpul investigației. Cu o atmosferă tensionată și răsturnări de situație surprinzătoare, „Local Woman Missing” este o lectură ideală pentru fanii genului thriller.'
WHERE title = 'Local Woman Missing' AND author = 'Mary Kubica';

UPDATE books
SET summary = '„Ward D” de Freida McFadden este un thriller medical intens despre viața într-o secție de psihiatrie unde nimic nu este ceea ce pare. Cartea urmărește experiențele tulburătoare ale pacienților și personalului dintr-o unitate psihiatrică și dezvăluie secretele întunecate care bântuie pe coridoarele spitalului. Cu suspans bine construit și personaje complexe, „Ward D” oferă o privire captivantă în lumea psihiatriei contemporane.'
WHERE title = 'Ward D' AND author = 'Freida McFadden';

UPDATE books
SET summary = '„Hidden Pictures” de Jason Rekula este un thriller psihologic plin de suspans despre o familie care se confruntă cu consecințele unei pierderi tragice. Cartea explorează lupta personajelor principale pentru a dezlega misterul din trecutul lor și pentru a face față amenințărilor actuale care le pun în pericol viața. Cu o intrigă bine construită și o tensiune constantă, „Hidden Pictures” este o lectură captivantă pentru iubitorii de thrillere psihologice.'
WHERE title = 'Hidden Pictures' AND author = 'Jason Rekula';

UPDATE books
SET summary = '„Mad Honey” de Jodi Picoult este un roman intens despre iubire, vinovăție și consecințele deciziilor neașteptate. Cartea explorează povestea unei familii care se confruntă cu tragedia și cu modul în care fiecare membru își navighează propriile emoții și responsabilități în fața provocărilor neașteptate. Cu personaje bine conturate și o poveste emoționantă, „Mad Honey” va captiva cititorii din prima pagină până în ultima.'
WHERE title = 'Mad Honey' AND author = 'Jodi Picoult';

UPDATE books
SET summary = '„A Talent for Murder” de Peter Swanson este un thriller psihologic ingenios despre o serie de crime misterioase legate de unul dintre cei mai cunoscuți autori ai vremurilor moderne. Cartea îmbină suspansul clasic al romanului polițist cu o analiză profundă a psihologiei umane și a obsesiilor care pot conduce la crime neașteptate. Cu răsturnări de situație surprinzătoare și un ritm alert, „A Talent for Murder” este o lectură perfectă pentru fanii genului.'
WHERE title = 'A Talent for Murder' AND author = 'Peter Swanson';

UPDATE books
SET summary = '„Tress of the Emerald Sea” de Brandon Sanderson este o aventură epică într-o lume fantastică plină de magie și pericole. Cartea urmărește călătoria unei tineri îndrăznețe care descoperă secretele adânci ale oceanului și își îndeplinește destinul într-o luptă pentru supraviețuire și libertate. Cu o poveste captivantă și un univers detaliat, „Tress of the Emerald Sea” este o lectură obligatorie pentru fanii literaturii fantastice.'
WHERE title = 'Tress of the Emerald Sea' AND author = 'Brandon Sanderson';

UPDATE books
SET summary = '„This is How You Lose the Time War” de Amal El-Mohtar și Max Gladstone este o poveste poetică și fascinantă despre războiul temporal dintre două agenți din viitor. Cartea explorează nu doar duelul lor strategic, ci și legăturile emoționale care se dezvoltă în timpul confruntării lor. Cu o prosă elegantă și idei filozofice profunde, „This is How You Lose the Time War” este o lectură impresionantă pentru iubitorii de science fiction.'
WHERE title = 'This is How You Lose the Time War' AND author = 'Amal El-Mohtar, Max Gladstone';

UPDATE books
SET summary = '„Lightlark” de Alex Aster este o poveste fermecătoare despre magie, prietenie și descoperirea propriei identități. Cartea urmărește aventurile unei tinere vrăjitoare care învață să-și accepte puterile magice și să navigheze prin intrigi palatului regal. Cu personaje carismatice și o poveste plină de aventură, „Lightlark” este o lectură perfectă pentru cititorii tineri și adolescenți.'
WHERE title = 'Lightlark' AND author = 'Alex Aster';

UPDATE books
SET summary = '„The Midnight Library” de Matt Haig este o poveste captivantă despre regrete, posibilități și căutarea sensului în viață. Cartea urmărește călătoria unei femei care descoperă o bibliotecă misterioasă unde fiecare carte reprezintă o realitate alternativă a vieții sale. Cu o prosă sensibilă și o poveste profundă, „The Midnight Library” va provoca cititorii să reflecteze asupra alegerilor și direcțiilor pe care le-au luat în viață.'
WHERE title = 'The Midnight Library' AND author = 'Matt Haig';

UPDATE books
SET summary = '„Happy Place” de Emily Henry este o poveste delicată despre iubire, vindecare și reînnoirea speranței. Cartea urmărește doi străini care se întâlnesc întâmplător într-un loc special și își descoperă împreună calea spre fericire și înțelegere. Cu personaje carismatice și o poveste plină de farmec, „Happy Place” este o lectură reconfortantă pentru toți cei care cred în puterea vindecătoare a iubirii.'
WHERE title = 'Happy Place' AND author = 'Emily Henry';

UPDATE books
SET summary = '„Yellowface” de R.F. Kuang este o explorare profundă și provocatoare a identității, rasismului și luptei pentru autenticitate într-o lume dominată de stereotipuri și prejudecăți rasiale. Romanul urmărește povestea unei tinere chinezo-americane care se confruntă cu întrebări dificile despre identitatea ei culturală și drumul spre acceptarea de sine. Cu o narativă puternică și personaje complexe, „Yellowface” provoacă cititorii să reflecteze profund asupra problemelor de identitate și stereotipurilor răspândite în societatea contemporană.'
WHERE title = 'Yellowface' AND author = 'R.F. Kuang';

-- The Guest List by Lucy Fole
UPDATE books
SET summary = 'Intriga din această carte se desfășoară într-o insulă izolată, unde un grup de invitați se adună pentru o nuntă de vis, dar lucrurile iau o întorsătură neașteptată când descoperiri tulburătoare ies la iveală.'
WHERE title = 'The Guest List' AND author = 'Lucy Fole';

-- Cunning Folk: Life in the Era of Practical Magic by Tabitha Stanmore
UPDATE books
SET summary = 'Această carte explorează viața în perioada magiei practice, detaliind practici și credințe ale oamenilor obișnuiți care au trăit într-o lume plină de mister și magie.'
WHERE title = 'Cunning Folk: Life in the Era of Practical Magic' AND author = 'Tabitha Stanmore';

-- The Diamond Eye by Kate Quinn
UPDATE books
SET summary = 'Povestea se învârte în jurul unui diamant foarte valoros și a oamenilor care îl urmăresc, dezvăluind secrete și trădări în drumul lor către posesie.'
WHERE title = 'The Diamond Eye' AND author = 'Kate Quinn';

-- The Marriage Portrait by Maggie O'Farrell
UPDATE books
SET summary = 'O poveste emoționantă despre iubire, sacrificiu și arta de a naviga prin complicațiile relațiilor umane, spusă printr-un portret de familie incitant.'
WHERE title = 'The Marriage Portrait' AND author = 'Maggie O\'Farrell';

-- One by One by Freida McFadden
UPDATE books
SET summary = 'Într-o cabană izolată în Alpi, membrii unui grup de prieteni sunt uciși unul câte unul, dezvăluind secrete întunecate și motive ascunse.'
WHERE title = 'One by One' AND author = 'Freida McFadden';

-- The Locked Door by Freida McFadden
UPDATE books
SET summary = 'O tânără descoperă o ușă secretă în apartamentul ei nou, dezvăluind un trecut întunecat și periculos care o urmărește în prezent.'
WHERE title = 'The Locked Door' AND author = 'Freida McFadden';

-- Never Lie by Freida McFadden
UPDATE books
SET summary = 'Un detectiv încrezut se confruntă cu o crimă în aparență simplă, dar descoperă că nimic nu este ceea ce pare într-un oraș plin de minciuni și trădări.'
WHERE title = 'Never Lie' AND author = 'Freida McFadden';

-- Me Talk Pretty One Day by David Sedaris
UPDATE books
SET summary = 'O colecție de eseuri umoristice care explorează experiențele umane comice și ciudate ale autorului, de la lecții de franceză până la peripeții de zi cu zi.'
WHERE title = 'Me Talk Pretty One Day' AND author = 'David Sedaris';

-- A Man Called Ove by Fredrik Backman
UPDATE books
SET summary = 'Povestea amuzantă și emoționantă a lui Ove, un bărbat bătrân și încăpățânat, care redescoperă bucuria vieții și a relațiilor cu vecinii săi excentrici.'
WHERE title = 'A Man Called Ove' AND author = 'Fredrik Backman';

-- The Princess Bride by William Goldman
UPDATE books
SET summary = 'O poveste clasică de aventură și iubire, unde un fermecător tânăr încearcă să-și salveze iubita de la un prinț malefic, cu ajutorul unor prieteni neașteptați.'
WHERE title = 'The Princess Bride' AND author = 'William Goldman';

-- Ella by Diane Richards
UPDATE books
SET summary = 'O biografie fascinantă despre viața și cariera legendarului artist de muzică pop, Ella, care a inspirat generații cu vocea ei puternică și prezența scenică.'
WHERE title = 'Ella' AND author = 'Diane Richards';

-- The Ballad of Darcy and Russell by Morgan Matson
UPDATE books
SET summary = 'Două suflete pereche se întâlnesc într-o vară magică, navigând prin provocările vieții și iubirii într-o poveste care va topi inimile cititorilor.'
WHERE title = 'The Ballad of Darcy and Russell' AND author = 'Morgan Matson';

-- Stay True by Hua Hsu
UPDATE books
SET summary = 'O colecție de eseuri care explorează identitatea culturală, muzica și impactul acesteia asupra societății, aducând perspective proaspete și provocatoare.'
WHERE title = 'Stay True' AND author = 'Hua Hsu';

-- The Maid by Nita Prose
UPDATE books
SET summary = 'Într-un hotel de lux, o menajeră observatoare descoperă un secret sinistru, forțând-o să navigheze prin minciuni și pericole pentru a salva oamenii din jurul ei.'
WHERE title = 'The Maid' AND author = 'Nita Prose';

-- The Paris Apartment by Lucy Foley
UPDATE books
SET summary = 'O poveste de mister și trădare într-un apartament parizian elegant, unde un grup de locatari ascunde secrete întunecate, dezvăluind adevăruri tulburătoare.'
WHERE title = 'The Paris Apartment' AND author = 'Lucy Foley';

-- The Silent Patient by Alex Michaelides
UPDATE books
SET summary = 'O terapeută obsedată de un caz aparent imposibil: o femeie care a împușcat soțul ei și apoi nu a mai vorbit niciodată, dezvăluind o poveste șocantă de traume și răzbunare.'
WHERE title = 'The Silent Patient' AND author = 'Alex Michaelides';

-- How to Keep House While Drowning by K.C. Davis
UPDATE books
SET summary = 'Un ghid sincer și provocator despre gestionarea vieții în haosul modern, oferind sfaturi practice și perspective revelatoare asupra auto-descoperirii și împlinirii.'
WHERE title = 'How to Keep House While Drowning' AND author = 'K.C. Davis';

-- Caste: The Origins of Our Discontents by Isabel Wilkerson
UPDATE books
SET summary = 'O explorare profundă a rasei și castei în America, dezvăluind istoria profundă și impactul sistemelor de caste asupra vieții cotidiene și a comunității.'
WHERE title = 'Caste: The Origins of Our Discontents' AND author = 'Isabel Wilkerson';

-- Don't Believe Everything You Think by Joseph Nguyen
UPDATE books
SET summary = 'Un ghid practic și provocator despre cum să navigăm prin gânduri și percepții greșite, oferind strategii pentru dezvoltare personală și eliberare de gânduri toxice.'
WHERE title = 'Don\'t Believe Everything You Think' AND author = 'Joseph Nguyen';

-- Humankind: A Hopeful History by Rutger Bregman
UPDATE books
SET summary = 'O privire provocatoare asupra istoriei umane, argumentând că, în esență, oamenii sunt buni și că optimismul este cheia pentru construirea unui viitor mai bun.'
WHERE title = 'Humankind: A Hopeful History' AND author = 'Rutger Bregman';

-- Greenlights by Matthew McConaughey
UPDATE books
SET summary = 'Memorii fascinante și filozofie de viață de la Matthew McConaughey, explorând aventurile, învățăturile și peripețiile care au conturat călătoria sa.'
WHERE title = 'Greenlights' AND author = 'Matthew McConaughey';

-- Four Thousand Weeks: Time Management for Mortals by Oliver Burkeman
UPDATE books
SET summary = 'O carte care explorează conceptul de gestionare a timpului într-o perspectivă umană și realistă, subliniind fragilitatea vieții și importanța prioritizării activităților care aduc satisfacție și semnificație personală.'
WHERE title = 'Four Thousand Weeks: Time Management for Mortals' AND author = 'Oliver Burkeman';

-- Home Body by Rupi Kaur
UPDATE books
SET summary = 'O colecție de poezii care explorează teme profunde precum iubirea, durerea, vindecarea și autodescoperirea, oferind o incursiune intimă și emoționantă în lumea interioară a autoarei.'
WHERE title = 'Home Body' AND author = 'Rupi Kaur';

-- The Stationery Shop by Marjan Kamali
UPDATE books
SET summary = 'În Iranul anilor 1950, o iubire de tinerețe este întreruptă brutal de politică, iar decenii mai târziu, destinul aduce îndrăgostiții din nou împreună, punând la încercare loialitățile și pasiunile lor.'
WHERE title = 'The Stationery Shop' AND author = 'Marjan Kamali';

-- The Wren, the Wren by Anne Enright
UPDATE books
SET summary = 'O colecție de povestiri provocatoare și profunde, care explorează teme precum familie, iubire, pierdere și identitate, oferind cititorilor o privire captivantă în lumea complexă a relațiilor umane.'
WHERE title = 'The Wren, the Wren' AND author = 'Anne Enright';

-- The Perfect Child by Lucinda Berry
UPDATE books
SET summary = 'Un thriller psihologic care explorează limitele maternității și ale iubirii părintești, într-o poveste tulburătoare despre un copil adoptat cu nevoi speciale și familia care luptă să îl protejeze.'
WHERE title = 'The Perfect Child' AND author = 'Lucinda Berry';

-- The Psychology of Money by Morgan Housel
UPDATE books
SET summary = 'O explorare captivantă a modului în care emoțiile, comportamentele și percepțiile noastre influențează deciziile financiare, oferind perspective valoroase pentru gestionarea banilor într-un mod mai conștient și eficient.'
WHERE title = 'The Psychology of Money' AND author = 'Morgan Housel';

-- Sociopath by Patric Gagne
UPDATE books
SET summary = 'Un studiu profund al comportamentului sociopat, dezvăluind trăsături distinctive, impactul asupra societății și modalități de a naviga și de a interacționa cu persoanele care manifestă astfel de caracteristici.'
WHERE title = 'Sociopath' AND author = 'Patric Gagne';

-- The Rabbit Hutch by Tess Gunty
UPDATE books
SET summary = 'O explorare a semnificației spiritualității și a conexiunii umane, printr-o poveste captivantă despre un grup de oameni care își găsesc liniștea interioară în mijlocul haosului lumii moderne.'
WHERE title = 'The Rabbit Hutch' AND author = 'Tess Gunty';

-- The Funeral Ladies of Ellerie County by Claire Swinarski
UPDATE books
SET summary = 'O comedie inimioară despre un mic oraș în care o echipă de femei puternice și excentrice gestionează înmormântările, aducând umor, viață și comunitate într-un moment inevitabil al vieții.'
WHERE title = 'The Funeral Ladies of Ellerie County' AND author = 'Claire Swinarski';

-- The Stranger in the Lifeboat by Mitch Albom
UPDATE books
SET summary = 'O poveste mistică despre un om care supraviețuiește unui naufragiu și devine un mister pentru ceilalți supraviețuitori, provocând întrebări profunde despre viață, credință și sensul existenței umane.'
WHERE title = 'The Stranger in the Lifeboat' AND author = 'Mitch Albom';

-- The Paradise Problem by Christina Lauren
UPDATE books
SET summary = 'Într-o insulă idilică, un profesor de matematică devine implicat într-o provocare inepuizabilă, punând la încercare inteligența sa și rezolvând enigmele unei vieți încâlcite.'
WHERE title = 'The Paradise Problem' AND author = 'Christina Lauren';

-- Love at First Book by Jenn McKinlay
UPDATE books
SET summary = 'O poveste romantică despre o femeie pasionată de cărți și de căutarea iubirii adevărate, explorând aventurile, răsturnările de situație și finalurile fericite pe care le aduce dragostea.'
WHERE title = 'Love at First Book' AND author = 'Jenn McKinlay';

-- The Honey Witch by Sydney J. Shields
UPDATE books
SET summary = 'Într-un oraș mic cu secrete întunecate, o tânără descoperă că posedă puteri magice, devenind o figură centrală într-un conflict veșnic între lumină și întuneric.'
WHERE title = 'The Honey Witch' AND author = 'Sydney J. Shields';

-- Bad Therapy: Why the Kids Aren't Growing Up by Abigail Shrier
UPDATE books
SET summary = 'O explorare provocatoare a terapiilor moderne și a impactului acestora asupra copiilor și adolescenților, aducând în discuție provocări și dileme contemporane ale dezvoltării umane.'
WHERE title = 'Bad Therapy: Why the Kids Aren\'t Growing Up' AND author = 'Abigail Shrier';

-- Burnout: The Secret to Unlocking the Stress Cycle by Emily Nagoski, Amelia Nagoski
UPDATE books
SET summary = 'O analiză profundă a fenomenului de burnout și a ciclului său de stres, oferind strategii practice și perspective științifice pentru gestionarea și prevenirea epuizării fizice și emoționale.'
WHERE title = 'Burnout: The Secret to Unlocking the Stress Cycle' AND author = 'Emily Nagoski, Amelia Nagoski';

-- The Backyard Bird Chronicles by Amy Tan
UPDATE books
SET summary = 'O poveste captivantă despre pasiunea pentru observarea păsărilor și conexiunile umane profunde care se formează prin această activitate, aducând liniște și înțelegere într-o lume agitată.'
WHERE title = 'The Backyard Bird Chronicles' AND author = 'Amy Tan';

-- Lost Ark Dreaming by Suyi Davies Okungbowa
UPDATE books
SET summary = 'Într-o lume futuristă și periculoasă, un tânăr se lansează într-o căutare epică pentru a descoperi secretul unui artefact antic, aducând lumină într-o istorie îngropată în uitare.'
WHERE title = 'Lost Ark Dreaming' AND author = 'Suyi Davies Okungbowa';

-- The Z Word by Lindsay King-Miller
UPDATE books
SET summary = 'Într-o lume post-apocaliptică, o tânără înfruntă zombi, mistere și propria identitate, navigând prin haosul lumii pentru a descoperi adevărul despre ce a dus la colapsul civilizației.'
WHERE title = 'The Z Word' AND author = 'Lindsay King-Miller';

-- Lunar Boy by Jacinta Wibowo, Jessica Wibowo
UPDATE books
SET summary = 'Un roman grafic captivant despre un băiat care descoperă secretele ascunse ale lumii sale, navigând printre pericole și revelații pentru a-și proteja familia și comunitatea.'
WHERE title = 'Lunar Boy' AND author = 'Jacinta Wibowo, Jessica Wibowo';

-- Breath: The New Science of a Lost Art by James Nestor
UPDATE books
SET summary = 'O investigație profundă a respirației și a impactului acesteia asupra sănătății noastre fizice și mentale, explorând metodele străvechi și științifice pentru a redescoperi puterea respirației corecte.'
WHERE title = 'Breath: The New Science of a Lost Art' AND author = 'James Nestor';

-- Somehow: Thoughts on Love by Anne Lamott
UPDATE books
SET summary = 'O colecție de eseuri provocatoare și emoționante despre natura iubirii și a relațiilor umane, oferind perspective profunde și personale asupra unei teme universale.'
WHERE title = 'Somehow: Thoughts on Love' AND author = 'Anne Lamott';

-- When You're Ready, This Is How You Heal by Brianna Wiest
UPDATE books
SET summary = 'Un ghid profund și îmbogățitor despre vindecarea emoțională și spirituală, oferind instrumente practice și înțelepciune pentru cei care trec prin procesul de reconstrucție a sinelui.'
WHERE title = 'When You\'re Ready, This Is How You Heal' AND author = 'Brianna Wiest';

-- Check & Mate by Ali Hazelwood
UPDATE books
SET summary = 'O poveste captivantă despre dragoste, șah și găsirea curajului de a depăși obstacolele în calea propriului fericire, într-un mediu plin de competiție și pasiune.'
WHERE title = 'Check & Mate' AND author = 'Ali Hazelwood';

-- The Long Game by Elena Armas
UPDATE books
SET summary = 'O poveste romantică despre ambiții, compromisuri și descoperirea echilibrului în viață și în dragoste, într-o lume în care fiecare decizie contează în drumul spre fericire.'
WHERE title = 'The Long Game' AND author = 'Elena Armas';

-- Cleat Cute by Meryl Wilsner
UPDATE books
SET summary = 'Într-o lume a sportului și a iubirii, două femei se descoperă pe sine și dragostea adevărată, navigând prin provocări și emoții pentru a găsi echilibrul în viețile lor complicate.'
WHERE title = 'Cleat Cute' AND author = 'Meryl Wilsner';

-- Wrong Place Wrong Time by Gillian McAllister
UPDATE books
SET summary = 'Un thriller tensionat despre un accident nefericit care schimbă vieți și aduce la lumină secrete întunecate, punând la încercare loialitățile și adevărurile personale.'
WHERE title = 'Wrong Place Wrong Time' AND author = 'Gillian McAllister';

-- Everyone Here Is Lying by Shari Lapena
UPDATE books
SET summary = 'Într-un mic oraș, o crimă neașteptată dezvăluie secretele întunecate ale locuitorilor, punând sub semnul întrebării loialitățile și relațiile personale într-o comunitate aparent idilică.'
WHERE title = 'Everyone Here Is Lying' AND author = 'Shari Lapena';

-- Apples Never Fall by Liane Moriarty
UPDATE books
SET summary = 'O dramă familială captivantă despre dispariția unei mame și impactul asupra celor patru copii adulți, dezvăluind secrete, minciuni și complexități ale vieții de familie.'
WHERE title = 'Apples Never Fall' AND author = 'Liane Moriarty';

-- People We Meet on Vacation by Emily Henry
UPDATE books
SET summary = 'O poveste romantică despre doi prieteni apropiați care se reconectează într-o vacanță anuală, navigând prin trecut, prezent și viitor pentru a descoperi dacă dragostea lor de lungă durată are un viitor.'
WHERE title = 'People We Meet on Vacation' AND author = 'Emily Henry';

-- The Paris Novel by Ruth Reichl
UPDATE books
SET summary = 'O poveste magică despre bucuria descoperirii de sine într-un oraș străin, aducând la viață farmecul Parisului și povestea unei femei care își redefinește propriul drum în viață.'
WHERE title = 'The Paris Novel' AND author = 'Ruth Reichl';

-- The Lincoln Highway by Amor Towles
UPDATE books
SET summary = 'O aventură epică prin America anilor \'20, în care doi tineri fugari descoperă adevărul despre prietenie, loialitate și libertate într-o călătorie plină de pericole și revelații.'
WHERE title = 'The Lincoln Highway' AND author = 'Amor Towles';

-- Diary of an Awesome Friendly Kid: Rowley Jefferson's Journal
UPDATE books
SET summary = 'O privire amuzantă și plină de umor asupra lumii lui Greg Heffley, relatată din perspectiva lui Rowley Jefferson, prietenul său cel mai bun. Cartea explorează aventurile și necazurile sale în încercarea de a-și găsi locul în lumea școlii și a prieteniei.'
WHERE title = 'Diary of an Awesome Friendly Kid: Rowley Jefferson\'s Journal';

-- Mad Honey
UPDATE books
SET summary = 'Un thriller psihologic captivant despre o descoperire sinistră într-un mic sat izolat, unde mierea locală ascunde secrete întunecate și aduce la suprafață obsesii, mistere și conflicte profunde între locuitori.'
WHERE title = 'Mad Honey';


insert into book_clubs (name) values ('Junior Readers Club');
insert into book_clubs (name) values ('Teen Book Club');
insert into book_clubs (name) values ('University Readers Club');

