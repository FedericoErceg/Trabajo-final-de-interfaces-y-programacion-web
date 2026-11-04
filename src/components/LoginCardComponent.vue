<template>
    <div class="card-login">
        <div class="card-login-header">
            <img src="/img/logo_hotel_web.png" alt="Logo Hotel Web" class="login-logo">
            <h2 class="card-login-title">Iniciar sesión</h2>
            <p class="card-login-desc">Ingresá tu email y contraseña para continuar.</p>
        </div>
        <form @submit.prevent="$emit('login')">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" :value="email" @input="$emit('update:email', $event.target.value)" placeholder="admin@hotel.com" required>
                <div class="error-msg">{{ errorEmail }}</div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" :value="password" @input="$emit('update:password', $event.target.value)" placeholder="******" required>
                <div class="error-msg">{{ errorPassword }}</div>
            </div>
            <div class="mb-3">
                <div ref="recaptcha"></div>
                <div class="error-msg">{{ errorCaptcha }}</div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Iniciar sesión</button>
        </form>
    </div>
</template>

<script>
export default {
    props: ['email', 'password', 'errorEmail', 'errorPassword', 'errorCaptcha'],
    emits: ['login', 'update:email', 'update:password'],
    mounted() {
        grecaptcha.render(this.$refs.recaptcha, {
            sitekey: '6LfDjMQtAAAAAHkB5Dh897Ih3Hq3u3yULREF0TOU'
        })
    }
}
</script>

<style scoped>
.card-login {
    background: #ffffff;
    border: 1px solid #e5e5e5;
    border-radius: 12px;
    padding: 32px;
    width: 100%;
    max-width: 400px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
}

.card-login-header {
    text-align: center;
    margin-bottom: 24px;
}

.login-logo {
    max-width: 180px;
    margin-bottom: 16px;
}

.card-login-title {
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 4px;
    color: #1a1a1a;
}

.card-login-desc {
    font-size: 14px;
    color: #737373;
    margin-bottom: 0;
}

.error-msg {
    font-size: 13px;
    color: #dc3545;
    margin-top: 4px;
}

.form-control:focus {
    border-color: #8b7355;
    box-shadow: 0 0 0 2px rgba(139, 115, 85, 0.15);
}
</style>