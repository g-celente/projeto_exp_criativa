<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="spinner-container">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Carregando...</span>
        </div>
        <p class="mt-2">Carregando...</p>
    </div>
</div>

<style>
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.9);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    backdrop-filter: blur(5px);
}

.loading-overlay.active {
    display: flex;
}

.spinner-container {
    text-align: center;
}

.spinner-border {
    width: 3rem;
    height: 3rem;
}
</style>

<script>
const loadingOverlay = {
    show: function() {
        document.getElementById('loadingOverlay').classList.add('active');
    },
    hide: function() {
        document.getElementById('loadingOverlay').classList.remove('active');
    }
};

// Mostrar loading em todas as requisições AJAX
document.addEventListener('DOMContentLoaded', function() {
    let originalFetch = window.fetch;
    window.fetch = function() {
        loadingOverlay.show();
        return originalFetch.apply(this, arguments)
            .finally(() => {
                loadingOverlay.hide();
            });
    }
});
</script>
