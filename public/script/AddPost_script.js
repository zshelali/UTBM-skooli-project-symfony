document.addEventListener('DOMContentLoaded', () => {
    const addPostBtn = document.querySelector('.add-post-btn-link');
    const addPostModal = document.getElementById('addPostModal');
    const closeModalBtn = document.querySelector('.close-post');

    if (addPostBtn && addPostModal && closeModalBtn) {
        addPostBtn.addEventListener('click', (event) => {
            event.preventDefault();
            addPostModal.style.display = 'block';
        });

        closeModalBtn.addEventListener('click', () => {
            addPostModal.style.display = 'none';
        });

        window.addEventListener('click', (event) => {
            if (event.target === addPostModal) {
                addPostModal.style.display = 'none';
            }
        });
    } else {
        console.error("One or more modal elements are missing in the DOM.");
    }
});
