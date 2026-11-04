<template>
    <main id="main-login" class="d-flex justify-content-center align-items-center">
        <LoginCardComponent
            v-model:email="email"
            v-model:password="password"
            :errorEmail="errorEmail"
            :errorPassword="errorPassword"
            :errorCaptcha="errorCaptcha"
            @login="login"
        />
    </main>

    <footer class="login-footer text-center">
        <p>© 2026 Hotel Web - Todos los derechos reservados</p>
        <p>Diseñado por Federico Erceg - Trabajo de Interfaces y Programación Web</p>
    </footer>
</template>

<script>
    import LoginCardComponent from '@/components/LoginCardComponent.vue';
    import { auth } from '../firebase'
    import { signInWithEmailAndPassword } from 'firebase/auth'

    export default {
        components: {
            LoginCardComponent
        },


        data(){
            return{
                email: '',
                password: '',
                errorEmail: '',
                errorPassword: '',
                errorCaptcha: ''
            }
        },

        methods:{
            async login(){
                this.errorEmail = ''
                this.errorPassword = ''
                this.errorCaptcha = ''

                if (this.email === '') {
                    this.errorEmail = 'Ingresá un email válido.'
                    return
                }
                if (this.password === '') {
                    this.errorPassword = 'Ingresá tu contraseña.'
                    return
                }

                const captchaResponse = grecaptcha.getResponse()
                if (!captchaResponse) {
                    this.errorCaptcha = 'Completá el captcha.'
                    return
                }

                try {
                    await signInWithEmailAndPassword(auth, this.email, this.password)
                    this.$router.push('/dashboard')
                } catch (error) {
                    this.errorPassword = 'Email o contraseña incorrectos.'
                }
            }
        }
    }
</script>

<style scoped>
    #main-login {
        min-height: 80vh;
    }

    .login-footer {
        padding: 20px;
        font-size: 14px;
        color: #737373;
    }

    .login-footer p {
        margin-bottom: 4px;
    }
</style>