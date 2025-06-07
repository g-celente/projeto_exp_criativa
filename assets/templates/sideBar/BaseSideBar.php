<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
<script src="https://kit.fontawesome.com/83d17aa143.js" crossorigin="anonymous"></script>

<?php
// Get current page URL
$current_page = basename($_SERVER['PHP_SELF']);
?>

<div class="nav-sidebar">
    <div class="logo">MoneyTrack</div>

    <ul class="nav-menu">
        <li class="nav-item <?php echo ($current_page == 'Dashboard.php') ? 'active' : ''; ?>">
            <a href="../dashboard/Dashboard.php"><i class="fas fa-chart-line"></i>Dashboard</a>
        </li>
        <li class="nav-item <?php echo ($current_page == 'EntriesView.php') ? 'active' : ''; ?>">
            <a href="../entries/EntriesView.php"><i class="fas fa-exchange-alt"></i>Entradas</a>
        </li>
        <li class="nav-item <?php echo ($current_page == 'ExpensesView.php') ? 'active' : ''; ?>">
            <a href="../expenses/ExpensesView.php"><i class="fas fa-history"></i>Saídas</a>
        </li>
        <li class="nav-item <?php echo ($current_page == 'BankAccountsView.php') ? 'active' : ''; ?>">
            <a href="../bank-accounts/BankAccountsView.php"><i class="fa-solid fa-building-columns"></i>Conta Bancária</a>
            <li class="nav-item <?php echo ($current_page == 'RemindersView.php') ? 'active' : ''; ?>">
                <a href="../reminders/RemindersView_copy.php"><i class="fa fa-calendar-o"></i>Lembretes</a>
            </li>
        </li>
        <li class="nav-item <?php echo ($current_page == 'NotificationView.php') ? 'active' : ''; ?>">
            <a href="../notification-settings/NotificationSettingsView.php"><i class="fa-solid fa-bell"></i>Notificações</a>
        </li>
        <li class="nav-item <?php echo ($current_page == 'ProfileView.php') ? 'active' : ''; ?>">
            <a href="../profile/ProfileView.php"><i class="fa-solid fa-user"></i>Perfil</a>
        </li>
        <!-- <li class="nav-item">
            <a href="../cards/CardsView.php"><i class="fas fa-wallet"></i>Cartões</a>
        </li>
        <li class="nav-item">
            <a href=""><i class="fas fa-credit-card"></i>Credits</a>
        </li> -->
    </ul>

    <div class="logout">
        <a href="../../app/helpers/LogoutHelper.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>