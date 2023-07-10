import store from "./vuex";

export async function guest(to, from, next) {
    try {
        const cookieExists = await store.dispatch('getCookie', 'ait');

        if (cookieExists) {
            next('/');
            return;
        }

    } catch (error) {
        console.error(error);
    }
    next();
}

export async function auth(to, from, next) {
    try {
        const cookieExists = await store.dispatch('getCookie', 'ait');

        if (!cookieExists) {
            next('/login');
            return;
        }

    } catch (error) {
        console.error(error);
        next(error);
    }
    next();
}
