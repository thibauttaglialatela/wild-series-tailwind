/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';
import 'tw-elements';
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');

document.querySelector('#watchlist').addEventListener('click', addToWatchlist);

function addToWatchlist(event) {
    event.preventDefault();
    const watchlistLink = event.currentTarget;
    const link = watchlistLink.href;
    const fillHeart = "fa-solid fa-heart fa-2x".split(' ');
    const emptyHeart = "fa-regular fa-heart fa-2x".split(' ');
    try {
        fetch(link)
            .then(res => res.json())
            .then(data => {
                const watchlistIcon = watchlistLink.firstElementChild;
                if (data.isInWatchlist) {
                    watchlistIcon.classList.remove(...emptyHeart);
                    watchlistIcon.classList.add(...fillHeart);
                } else {
                    watchlistIcon.classList.remove(...fillHeart);
                    watchlistIcon.classList.add(...emptyHeart);
                }
            })
    } catch (err) {
        console.error(err);
    }
}

