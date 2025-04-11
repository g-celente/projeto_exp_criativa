<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoneyTrack</title>
    <link rel="stylesheet" href="../../assets/css/main.css">
</head>
<body>
    <?php include "../../assets/templates/sideBar/BaseSideBar.php"; ?>
    <!-- Main Content -->
    <div class="main-content">
        <!-- Área de Cartões no Topo -->
        <div class="cards-container" id="cardsContainer">
            <div class="cards-slider" id="cardsSlider">
                <!-- Cartão 1 -->
                <div class="card">
                    <div class="card-header">
                        <span class="card-type">Business</span>
                        <span class="card-logo">VISA</span>
                    </div>
                    <div class="card-balance">$ 1,260.00</div>
                    <div class="card-number">•••• •••• •••• 4562</div>
                    <div class="card-footer">
                        <span>08/25</span>
                        <span>Gabriel Moribe</span>
                    </div>
                </div>
                
                <!-- Cartão 2 -->
                <div class="card" style="background: linear-gradient(135deg, #10b981, #34d399);">
                    <div class="card-header">
                        <span class="card-type">Savings</span>
                        <span class="card-logo">VISA</span>
                    </div>
                    <div class="card-balance">$ 550.00</div>
                    <div class="card-number">•••• •••• •••• 7821</div>
                    <div class="card-footer">
                        <span>05/26</span>
                        <span>Gabriel Moribe</span>
                    </div>
                </div>
                
                <!-- Cartão 3 -->
                <div class="card" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                    <div class="card-header">
                        <span class="card-type">Personal</span>
                        <span class="card-logo">MASTERCARD</span>
                    </div>
                    <div class="card-balance">$ 2,450.00</div>
                    <div class="card-number">•••• •••• •••• 1234</div>
                    <div class="card-footer">
                        <span>12/24</span>
                        <span>Gabriel Moribe</span>
                    </div>
                </div>
                
                <!-- Cartão 4 -->
                <div class="card" style="background: linear-gradient(135deg, #8b5cf6, #a78bfa);">
                    <div class="card-header">
                        <span class="card-type">Travel</span>
                        <span class="card-logo">AMEX</span>
                    </div>
                    <div class="card-balance">$ 3,780.00</div>
                    <div class="card-number">•••• •••• •••• 5678</div>
                    <div class="card-footer">
                        <span>10/25</span>
                        <span>Gabriel Moribe</span>
                    </div>
                </div>

                <!-- Cartão 5 -->
                <div class="card" style="background: linear-gradient(135deg,rgb(200, 246, 92),rgb(118, 141, 79));">
                    <div class="card-header">
                        <span class="card-type">Travel</span>
                        <span class="card-logo">AMEX</span>
                    </div>
                    <div class="card-balance">$ 1,080.00</div>
                    <div class="card-number">•••• •••• •••• 5678</div>
                    <div class="card-footer">
                        <span>10/25</span>
                        <span>Gabriel Moribe</span>
                    </div>
                </div>
                
            </div>
            
            <div class="user-avatar-container">
                <div class="user-avatar">JD</div>
            </div>
            
        </div>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-title">Business</div>
                <div class="stat-value">$ 1260.00</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Income</div>
                <div class="stat-value">$ 1240.60</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Outcome</div>
                <div class="stat-value">$ 240.35</div>
            </div>
            <div class="stat-card">
                <div class="stat-title">Savings</div>
                <div class="stat-value">$ 550.00</div>
            </div>
        </div>

        <!-- Card History -->
        <div class="history-section">
            <h2 class="section-title">Card History</h2>
            <ul class="transaction-list">
                <li class="transaction-item">
                    <div class="transaction-details">
                        <div class="transaction-merchant">Statbacks</div>
                        <div class="transaction-time">07:36 AM 07:07 2019</div>
                    </div>
                    <div class="transaction-amount">$18.75</div>
                </li>
                <li class="transaction-item">
                    <div class="transaction-details">
                        <div class="transaction-merchant">H&M</div>
                        <div class="transaction-time">05:50 PM 07:07 2019</div>
                    </div>
                    <div class="transaction-amount">$549.85</div>
                </li>
                <li class="transaction-item">
                    <div class="transaction-details">
                        <div class="transaction-merchant">Apple Store</div>
                        <div class="transaction-time">03:20 PM 07:07 2019</div>
                    </div>
                    <div class="transaction-amount">$2.00</div>
                </li>
                <li class="transaction-item">
                    <div class="transaction-details">
                        <div class="transaction-merchant">McDonald's</div>
                        <div class="transaction-time">04:30 PM 07:07 2019</div>
                    </div>
                    <div class="transaction-amount">$36.90</div>
                </li>
                <li class="transaction-item">
                    <div class="transaction-details">
                        <div class="transaction-merchant">Grocery</div>
                        <div class="transaction-time">05:38 PM 07:07 2019</div>
                    </div>
                    <div class="transaction-amount">$18.45</div>
                </li>
            </ul>
        </div>
    </div>


</body>
</html>
<style>
        
        /* Área de Cartões no Topo */
        .cards-container {
            display: flex;
            gap: 20px;
            padding: 20px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }
        
        .cards-slider {
            display: flex;
            gap: 20px;
            transition: transform 0.3s ease-out;
            cursor: grab;
            user-select: none;
        }
        
        .cards-slider.dragging {
            cursor: grabbing;
            transition: none;
        }
        
        /* Estilo dos Cartões */
        .card {
            background: linear-gradient(135deg, var(--primary-color), #6366f1);
            color: white;
            border-radius: 12px;
            padding: 20px;
            width: 280px;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
            position: relative;
            overflow: hidden;
            height: 160px;
            flex-shrink: 0;
            transition: opacity 0.3s ease;
        }
        
        .card::before {
            content: "";
            position: absolute;
            top: -50px;
            right: -50px;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
        }
        
        .card::after {
            content: "";
            position: absolute;
            bottom: -80px;
            right: -30px;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .card-type {
            font-weight: 500;
            font-size: 14px;
        }
        
        .card-logo {
            font-weight: bold;
            font-size: 18px;
        }
        
        .card-balance {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 15px;
        }
        
        .card-number {
            font-family: 'Courier New', monospace;
            letter-spacing: 1px;
            margin-bottom: 5px;
            font-size: 14px;
        }
        
        .card-footer {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            margin-top: 10px;
        }

        
        /* Card Stats */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .stat-title {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 10px;
        }
        
        .stat-value {
            font-size: 22px;
            font-weight: 600;
        }
        
        /* Card History */
        .history-section {
            background-color: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .section-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .transaction-list {
            list-style: none;
        }
        
        .transaction-item {
            display: flex;
            justify-content: space-between;
            padding: 16px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .transaction-item:last-child {
            border-bottom: none;
        }
        
        .transaction-details {
            flex: 1;
        }
        
        .transaction-merchant {
            font-weight: 500;
            margin-bottom: 4px;
        }
        
        .transaction-time {
            font-size: 12px;
            color: #64748b;
        }
        
        .transaction-amount {
            font-weight: 600;
        }
        
        /* Card Actions */
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 20px;
        }
        
        .action-button {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background-color: var(--primary-color);
            color: white;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .action-button:hover {
            background-color: #2563eb;
        }
        
        .action-button.service {
            background-color: #e2e8f0;
            color: #334155;
        }
        
        /* User Avatar Container */
        .user-avatar-container {
            position: absolute;
            width: 450px;
            height: 120px;
            right: 20px;
            top: 1px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            background-color: transparent;
            border-bottom-left-radius: 50px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            z-index: 2;
        }

        .user-avatar-container::before {
            content: "";
            position: absolute;
            width: 50%;
            height: 100%;
            background-color: var(--primary-color);
            top: 0;
            left: 0;
            z-index: -1;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-left: auto;
            margin-bottom: 20px;
        }
        
        /* Botões de navegação do carrossel */
        .carousel-nav {
            position: absolute;
            right: 500px;
            bottom: 0px;
            display: flex;
            gap: 10px;
            z-index: 3;
        }
        
        
        /* Efeito de opacidade quando perto do avatar */
        .card.near-avatar {
            opacity: 0;
        }
    </style>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            const slider = document.getElementById('cardsSlider');
            const container = document.getElementById('cardsContainer');
            const cards = document.querySelectorAll('.card');
            const avatarContainer = document.querySelector('.user-avatar-container');
            
            let isDragging = false;
            let startX, startScrollLeft, velocity, lastX, animationFrame;
            // (posicao inicial do cursor, posicao inicial do carrossel, velocidade, ultima posicao do cursor, frame de animacao)
            const cardWidth = cards[0].offsetWidth + 20; // Largura do card + gap entre eles
            let currentPosition = 0;
            const maxPosition = -(cardWidth * (cards.length - 3)); // Mostra 3 cards por vez
            
            // Inicia o arraste
            const startDrag = (e) => {
                isDragging = true;
                slider.classList.add('dragging');
                
                // Posição inicial do cursor
                startX = e.pageX;
                lastX = startX;
                
                // Posição inicial do scroll
                startScrollLeft = currentPosition;
                
                // Cancela qualquer animação em andamento
                // cancelAnimationFrame(animationFrame);
                
                // Reseta a velocidade
                velocity = 0;
            };
            
            // Durante o arraste
            const duringDrag = (e) => {
                if (!isDragging) return;
                e.preventDefault(); // ???
                
                // Posição atual do cursor
                const x = e.pageX;
                
                // Calcula a velocidade (para efeito de inércia)
                velocity = x - lastX;
                lastX = x;
                
                // Calcula o deslocamento
                const walk = x - startX;
                
                // Atualiza a posição
                currentPosition = startScrollLeft + walk;
                
                // Limites do carrossel
                currentPosition = Math.min(0, Math.max(maxPosition, currentPosition));
                
                // Aplica o movimento
                slider.style.transform = `translateX(${currentPosition}px)`;
                
                // Verifica cartões próximos ao avatar
                checkCardsNearAvatar();
            };
            
            // Finaliza o arraste
            const endDrag = () => {
                if (!isDragging) return;
                isDragging = false;
                slider.classList.remove('dragging');
                
                // Aplica efeito de inércia
                applyInertia();
            };
            
            // Efeito de inércia
            const applyInertia = () => {
                // Reduz a velocidade gradualmente
                velocity *= 0.92;
                
                // Atualiza a posição com a velocidade
                currentPosition += velocity;
                
                // Limites do carrossel
                currentPosition = Math.min(0, Math.max(maxPosition, currentPosition));
                
                // Aplica o movimento
                slider.style.transform = `translateX(${currentPosition}px)`;
                
                // Verifica cartões próximos ao avatar
                checkCardsNearAvatar();
                
                // Continua a animação se ainda houver velocidade
                if (Math.abs(velocity) > 0.5) {
                    animationFrame = requestAnimationFrame(applyInertia);
                } else {
                    // Alinha ao cartão mais próximo quando parar
                    snapToCard();
                }
            };
            
            
            // Função para verificar cartões próximos ao avatar
            // function checkCardsNearAvatar() {
            //     const avatarRect = avatarContainer.getBoundingClientRect();
            //     const avatarLeft = avatarRect.left;
                
            //     cards.forEach(card => {
            //         const cardRect = card.getBoundingClientRect();
            //         const cardRight = cardRect.right;
                    
            //         // Se o cartão estiver a menos de 100px do avatar, aplica o efeito
            //         if (cardRight > avatarLeft - 50 && cardRight < avatarLeft + 3000) {
            //             card.classList.add('near-avatar');
            //         } else {
            //             card.classList.remove('near-avatar');
            //         }
            //     });
            // }
            function checkCardsNearAvatar() {
    const avatarRect = avatarContainer.getBoundingClientRect();
    const avatarLeft = avatarRect.left;
    const avatarRight = avatarRect.right;

    cards.forEach(card => {
        const cardRect = card.getBoundingClientRect();
        const cardCenter = (cardRect.left + cardRect.right) / 2;

        // Calcula a distância do centro do cartão ao centro do avatar
        const avatarCenter = (avatarLeft + avatarRight) / 2;
        const distance = Math.abs(cardCenter - avatarCenter);

        // Define o limite para começar a reduzir a opacidade
        const maxDistance = 200; // Ajuste conforme necessário
        const opacity = Math.max(0, 1 - distance / maxDistance); // Opacidade mínima de 0.3

        // Aplica a opacidade ao cartão
        card.style.opacity = opacity;
    });
}
            
            // Event listeners para mouse
            slider.addEventListener('mousedown', startDrag);
            document.addEventListener('mousemove', duringDrag);
            document.addEventListener('mouseup', endDrag);
            
            // Event listeners para touch
            slider.addEventListener('touchstart', startDrag, { passive: false });
            slider.addEventListener('touchmove', (e) => {
                e.preventDefault();
                duringDrag(e);
            }, { passive: false });
            slider.addEventListener('touchend', endDrag);
                        
            // Inicializa a posição
            slider.style.transform = 'translateX(0px)';
            checkCardsNearAvatar();
        });
    </script>