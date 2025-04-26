document.addEventListener('DOMContentLoaded', () => {
    // Ouvrir le modal d'ajout de post
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

    // Gérer l'ouverture/fermeture du menu contextuel
    document.querySelectorAll('.Actuality_options').forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault();
            const menu = this.nextElementSibling;
            const isVisible = menu.style.display === 'block';
            document.querySelectorAll('.dropdown-menu').forEach(m => m.style.display = 'none');
            menu.style.display = isVisible ? 'none' : 'block';
        });
    });

    // Fermer les menus contextuels si on clique ailleurs
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.Actuality_menu')) {
            document.querySelectorAll('.dropdown-menu').forEach(m => m.style.display = 'none');
        }
    });


});
document.querySelectorAll('.delete-post').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        const postId = this.getAttribute('data-id');

        if (confirm('Êtes-vous sûr de vouloir supprimer ce post ?')) {
            fetch(`/post/${postId}/delete`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const postElement = link.closest('.Actuality_container_text');
                        if (postElement) {
                            postElement.remove(); // Supprimer l'élément post du DOM
                        }
                    } else {
                        alert('Erreur lors de la suppression');
                    }
                })
                .catch(err => {
                    alert('Une erreur est survenue');
                    console.error(err);
                });
        }
    });
});

// ouvrir le modal pour ajouter
document.querySelector('.add-post-btn-link')?.addEventListener('click', function (e) {
    e.preventDefault();
    document.getElementById('modal-title').textContent = 'Add post';
    document.getElementById('modal-submit').textContent = 'Publier';
    document.getElementById('addPostForm').action = "{{ path('post_add', { id: ue.id }) }}";

    // vider les champs
    document.getElementById('postTitle').value = '';
    document.getElementById('postContent').value = '';

    document.getElementById('addPostModal').style.display = 'block';
});

// ouvrir le modal pour modifier
document.querySelectorAll('.edit-post').forEach(button => {
    button.addEventListener('click', function (e) {
        e.preventDefault();

        const postContainer = this.closest('.Actuality_container_text');
        const title = postContainer.querySelector('.Actuality_title')?.textContent.trim();
        const content = postContainer.querySelector('.text_paragraph')?.textContent.trim();

        document.getElementById('modal-title').textContent = 'Edit post';
        document.getElementById('modal-submit').textContent = 'Update';

        // remplir les champs
        document.getElementById('postTitle').value = title || '';
        document.getElementById('postContent').value = content || '';


        document.getElementById('addPostModal').style.display = 'block';
    });
});

// fermer le modal
function closePostModal() {
    document.getElementById('addPostModal').style.display = 'none';
}
