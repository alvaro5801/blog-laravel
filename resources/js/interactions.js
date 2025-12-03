document.addEventListener('DOMContentLoaded', function () {
            
    function updateButtonStyle(button, isActive, activeColorClass) {
        if (!button) return;

        // Removemos todas as classes de borda e fundo antigas para limpar o visual
        button.classList.remove('border', 'bg-gray-100', 'bg-green-50', 'bg-red-50', 'px-2', 'rounded');

        if (isActive) {
            // ATIVO: Apenas Cor Viva e Negrito
            button.classList.remove('text-gray-500');
            button.classList.add(activeColorClass, 'font-bold');
        } else {
            // INATIVO: Cinza e Fonte Normal
            button.classList.add('text-gray-500');
            button.classList.remove(activeColorClass, 'font-bold');
        }
    }

    const forms = document.querySelectorAll('.like-form, .dislike-form');

    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const url = this.action;
            const container = this.closest('div.flex'); 

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // 1. Atualiza Números
                    const likeCount = container.querySelector('.like-form .count-display');
                    const dislikeCount = container.querySelector('.dislike-form .count-display');
                    
                    if(likeCount) likeCount.innerText = data.likes_count;
                    if(dislikeCount) dislikeCount.innerText = data.dislikes_count;

                    // 2. Atualiza Botões
                    const likeBtn = container.querySelector('.like-form button');
                    const dislikeBtn = container.querySelector('.dislike-form button');

                    updateButtonStyle(likeBtn, data.liked, 'text-green-600');
                    updateButtonStyle(dislikeBtn, data.disliked, 'text-red-500');
                }
            })
            .catch(error => console.error('Erro:', error));
        });
    });
});