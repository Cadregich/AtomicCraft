<template>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="d-flex flex-wrap w-100 px-1 px-lg-4 px-md-2">
            <!-- Always visible left menu -->
            <ul class="always-visible-left-menu navbar-nav d-flex flex-wrap">
                <router-link to="/" class="navbar-brand order-0">AtomicCraft</router-link>
                <!-- Содержимое always-visible-left-menu -->
            </ul>

            <!-- /.Always visible left menu -->
            <!-- Left menu -->
            <div class="left-menu collapse navbar-collapse order-2" id="navbarSupportedContent">
                <ul class="navbar-nav mb-lg-0">
                    <li class="nav-item">
                        <router-link :to="{ name: 'test' }" class="nav-link active" aria-current="page">
                            Сервера
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="{ name: 'shop' }" class="nav-link active" aria-current="page">
                            Магазин
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="{ name: 'test' }" class="nav-link active" aria-current="page">
                            Новости
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="{ name: 'test' }" class="nav-link active" aria-current="page">
                            Дискорд
                        </router-link>
                    </li>
                    <li class="nav-item" v-if="auth">
                        <router-link :to="{ name: 'user-data' }" class="nav-link active" aria-current="page">
                            User data
                        </router-link>
                    </li>
                </ul>

                <ul class="navbar-nav mb-2 mb-lg-0" id="navbar-auth-block">
                    <li class="nav-item" v-if="!auth">
                        <router-link :to="{ name: 'registration' }" class="nav-link active" aria-current="page">
                            Зарегистрироваться
                        </router-link>
                    </li>
                    <li class="nav-item" v-if="!auth">
                        <router-link :to="{ name: 'login' }" class="nav-link active" aria-current="page">
                            Войти
                        </router-link>
                    </li>
                </ul>
            </div>
            <!-- /.Left menu -->
            <!-- Right menu -->
            <div class="right-menu d-flex align-items-center order-lg-3 order-1 mb-auto ml-auto">
                <div class="nav-item dropdown">
                    <ul class="navbar-nav mb-lg-0">
                        <li class="nav-item" v-if="auth">
                            <div class="nav-link dropdown-toggle" href="#">
                                Cadregich
                            </div>
                        </li>
                    </ul>
                    <div class="d-flex flex-column align-items-start" v-if="auth">
                        <ul class="dropdown-menu dropdown-menu-end flex-column">
                            <li>
                                <router-link to="/cabinet" class="text-decoration-none">
                                    <button class="dropdown-item text-center">Кабинет</button>
                                </router-link>
                            </li>
                            <li>
                                <router-link :to="{ name: 'logout' }" class="text-decoration-none">
                                    <button class="dropdown-item text-center">Выйти</button>
                                </router-link>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Burger -->
                <button
                    class="navbar-toggler ml-auto"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!-- /.Burger -->
            </div>
            <!-- /.Right menu -->
        </div>
    </nav>
    <div style="margin-bottom: 70px"></div>
</template>

<script>
import {Dropdown} from "bootstrap";

export default {
    name: "Navbar",
    data() {
        return {
            isRightLinkInLeftMenu: false,
        };
    },
    computed: {
        auth() {
            return this.$store.getters.Auth;
        }
    },
    methods: {
        replaceItemsDOM(size) {
            if (size < 400) {
                const destination = document.getElementsByClassName("always-visible-left-menu")[0];
                const block = document.getElementsByClassName("dropdown")[0];
                destination.appendChild(block);
                this.isRightLinkInLeftMenu = true;
            } else {
                const destination = document.getElementsByClassName("right-menu")[0];
                const block = document.getElementsByClassName("dropdown")[0];
                const firstChild = destination.firstChild;
                destination.insertBefore(block, firstChild);
            }
        },
    },
    mounted() {
        window.addEventListener("resize", () => {
            this.replaceItemsDOM(window.innerWidth);
        });
        this.replaceItemsDOM(window.innerWidth);
    },
    updated() {
        if (this.auth) {
            const element = document.getElementsByClassName("dropdown")[0];
            const elementMenu = document.getElementsByClassName("dropdown-menu")[0];
            const dropdown = new Dropdown(element);

            function handleClick() {
                dropdown.show();
                elementMenu.style.display = "flex";
                element.removeEventListener("click", handleClick);

                function resetClick() {
                    dropdown.hide();
                    elementMenu.style.display = "none";
                    element.addEventListener("click", handleClick);
                }

                function handleClickOutside(event) {
                    if (!element.contains(event.target)) {
                        resetClick();
                    }
                }

                element.addEventListener("click", resetClick);
                document.addEventListener("click", handleClickOutside);
            }

            element.addEventListener("click", handleClick);

            element.addEventListener("mouseenter", function () {
                dropdown.show();
                elementMenu.style.display = "flex";
            });
            element.addEventListener("mouseleave", function () {
                dropdown.hide();
                elementMenu.style.display = "none";
            });
            element.addEventListener("mouseleave", function () {
                dropdown.hide();
                elementMenu.style.display = "none";
            });
        }

    }
};
</script>

<style scoped>
* {
    color: white !important;
}

.navbar {
    border-bottom: solid 2px hotpink;
    background: url('/images/backgrounds/header_backgroun9d.png');
}

@media (min-width: 922px) {
    .navbar {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1030;
    }
}

.navbar-brand {
    font-size: 21px;
    font-weight: 600;
}

.nav-item {
    font-size: 19px;
}

.right-menu {
    margin-left: auto;
}

@media (min-width: 992px) {
    .right-menu {
        height: 100%;
    }
}

.right-menu > * {
    margin-left: 0.8rem;
}

.dropdown-toggle:hover {
    color: black;
    transition: 0.2s;
}

.dropdown-menu {
    margin: 0;
    left: auto;
}

.dropdown-menu > * > * > * {
    color: black !important;
}

#navbar-auth-block {
    margin-left: auto;
}

@media (max-width: 500px) {
    .left-menu,
    #navbar-auth-block {
        justify-content: center;
        text-align: center;
    }

    .left-menu {
        margin: 0.5rem 0;
    }
}
</style>
