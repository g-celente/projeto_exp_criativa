<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
<section id="content">
    <nav class="navBar">
        <input type="checkbox" id="switch-mode" hidden>
        <label for="switch-mode" class="switch-mode"></label>
        <a href="#" class="notification">
            <i class='bx bxs-bell'></i>
            <span class="num">8</span>
        </a>
        <a href="#" class="profile">
            <img src="img/people.png">
        </a>
    </nav>
</section>


<style>
    :root {
        --poppins: 'Poppins', sans-serif;
        --lato: 'Lato', sans-serif;

        --light: #fff;
        --blue: #3C91E6;
        --light-blue: #CFE8FF;
        --grey: #eee;
        --dark-grey: #AAAAAA;
        --dark: #342E37;
        --red: #DB504A;
        --yellow: #FFCE26;
        --light-yellow: #FFF2C6;
        --orange: #FD7238;
        --light-orange: #FFE0D3;
    }

    #content {
        position: relative;
        width: calc(100% - 131px);
        left: 175px;
        transition: .3s ease;
        justify-content: flex-end; 
        background-color: white;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
    }

    .navBar {
        height: 56px;
        background: var(--light);
        padding: 0 24px;
        display: flex;
        justify-content: flex-end; 
        align-items: center;
        grid-gap: 24px;
        font-family: var(--lato);
        position: sticky;
        top: 0;
        left: 0;
        z-index: 1000;
    }

    .navBar::before {
        content: '';
        position: absolute;
        width: 40px;
        height: 40px;
        bottom: -40px;
        left: 0;
        border-radius: 50%;
        box-shadow: -20px -20px 0 var(--light);
    }

    .navBar a {
        color: var(--dark);
    }

    .navBar .bx.bx-menu {
        cursor: pointer;
        color: var(--dark);
    }

    .navBar .nav-link {
        font-size: 16px;
        transition: .3s ease;
    }

    .navBar .nav-link:hover {
        color: var(--blue);
    }

    .navBar form {
        max-width: 400px;
        width: 100%;
        margin-right: auto;
    }

    .navBar form .form-input {
        display: flex;
        align-items: center;
        height: 36px;
    }

    .navBar form .form-input input {
        flex-grow: 1;
        padding: 0 16px;
        height: 100%;
        border: none;
        background: var(--grey);
        border-radius: 36px 0 0 36px;
        outline: none;
        width: 100%;
        color: var(--dark);
    }

    .navBar form .form-input button {
        width: 36px;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        background: var(--blue);
        color: var(--light);
        font-size: 18px;
        border: none;
        outline: none;
        border-radius: 0 36px 36px 0;
        cursor: pointer;
    }

    .navBar .notification {
        font-size: 20px;
        position: relative;
    }

    .navBar .notification .num {
        position: absolute;
        top: -6px;
        right: -6px;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        border: 2px solid var(--light);
        background: var(--red);
        color: var(--light);
        font-weight: 700;
        font-size: 12px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .navBar .profile img {
        width: 36px;
        height: 36px;
        object-fit: cover;
        border-radius: 50%;
    }

    .navBar .switch-mode {
        display: block;
        min-width: 50px;
        height: 25px;
        border-radius: 25px;
        background: var(--grey);
        cursor: pointer;
        position: relative;
    }

    .navBar .switch-mode::before {
        content: '';
        position: absolute;
        top: 2px;
        left: 2px;
        bottom: 2px;
        width: calc(25px - 4px);
        background: var(--blue);
        border-radius: 50%;
        transition: all .3s ease;
    }

    .navBar #switch-mode:checked+.switch-mode::before {
        left: calc(100% - (25px - 4px) - 2px);
    }
</style>