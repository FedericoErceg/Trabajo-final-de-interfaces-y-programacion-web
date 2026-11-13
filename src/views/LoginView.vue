<template>
    <!-- Contenedor principal centrado vertical y horizontalmente con flexbox -->
    <main id="main-login" class="d-flex justify-content-center align-items-center">
        <!-- Componente de la tarjeta de login -->
        <!-- v-model:email y v-model:password hacen binding bidireccional con el componente hijo -->
        <!-- :errorEmail, :errorPassword, :errorCaptcha pasan los mensajes de error como props -->
        <!-- @login escucha el evento emitido cuando se envía el formulario -->
        <LoginCardComponent
            v-model:email="email"
            v-model:password="password"
            :errorEmail="errorEmail"
            :errorPassword="errorPassword"
            :errorCaptcha="errorCaptcha"
            @login="login"
        />
    </main>

    <!-- Footer fijo del login -->
    <footer class="login-footer text-center">
        <p>© 2026 Hotel Web - Todos los derechos reservados</p>
        <p>Diseñado por Federico Erceg - Trabajo de Interfaces y Programación Web</p>
    </footer>
</template>

<script>
    import LoginCardComponent from '@/components/LoginCardComponent.vue'
    import { auth } from '../firebase'
    import { signInWithEmailAndPassword } from 'firebase/auth'

    export default {

        // Registro del componente usado en el template
        components: {
            LoginCardComponent
        },

        // Estado reactivo: variables vinculadas a los inputs y mensajes de error
        data(){
            return{
                email: '',   // Vinculado al input de email con v-model
                password: '',    // Vinculado al input de contraseña con v-model
                errorEmail: '',     // Mensaje de error mostrado debajo del campo email
                errorPassword: '',    // Mensaje de error mostrado debajo del campo contraseña
                errorCaptcha: ''  // Mensaje de error mostrado debajo del captcha
            }
        },

        methods:{
            // Método principal de autenticación, se ejecuta al enviar el formulario
            async login(){

                // Limpia los mensajes de error anteriores
                this.errorEmail = ''
                this.errorPassword = ''
                this.errorCaptcha = ''

                // Validación del campo email
                if (this.email === '') {
                    this.errorEmail = 'Ingresá un email válido.'
                    return      // Corta la ejecución si falla la validación
                }
                // Validación del campo contraseña
                if (this.password === '') {
                    this.errorPassword = 'Ingresá tu contraseña.'
                    return
                }

                // Verificación del reCAPTCHA de Google
                // grecaptcha es una variable global que carga el script de Google en index.html
                // getResponse() devuelve un token si el usuario completó el captcha, o vacío si no
                const captchaResponse = grecaptcha.getResponse()
                if (!captchaResponse) {
                    this.errorCaptcha = 'Completá el captcha.'
                    return
                }

                // Autenticación con Firebase
                // try/catch para manejar errores de credenciales incorrectas
                try {
                    // signInWithEmailAndPassword verifica el email y contraseña contra Firebase Auth
                    await signInWithEmailAndPassword(auth, this.email, this.password)
                    // Si la autenticación es exitosa, redirige al dashboard usando Vue Router
                    this.$router.push('/dashboard')
                } catch (error) {
                    // Si Firebase rechaza las credenciales, muestra el error
                    this.errorPassword = 'Email o contraseña incorrectos.'
                }
            }
        }
    }
</script>

<!-- scoped: los estilos aplican solo a este componente -->
<style scoped>
    /* Altura mínima para centrar verticalmente la tarjeta */
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