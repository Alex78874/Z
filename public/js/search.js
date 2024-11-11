document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('search-form');
    const searchInput = searchForm.querySelector('input[name="q"]');
    const searchResults = document.getElementById('search-results');

    searchInput.addEventListener('input', function() {
        const query = searchInput.value;
        if (query.length >= 3) {
            fetch('/search?q=' + encodeURIComponent(query), {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    searchResults.innerHTML = '';
                    data.posts.forEach(post => {
                        const postElement = document.createElement('div');
                        postElement.classList.add('search-result');
                        postElement.innerHTML = `
                            <a href="/post/${post.id}">
                                <strong>${post.username}</strong>: ${post.content}
                            </a>
                        `;
                        searchResults.appendChild(postElement);
                    });
                }
            });
        } else {
            searchResults.innerHTML = '';
        }
    });
});