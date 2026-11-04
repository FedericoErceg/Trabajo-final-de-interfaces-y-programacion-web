import { initializeApp } from 'firebase/app'
import { getAuth } from 'firebase/auth'

const firebaseConfig = {
    apiKey: "AIzaSyCKSVlXYWC-ZVdV_crMhfE_D1K5rgqZFGc",
    authDomain: "parcial-f4e37.firebaseapp.com",
    projectId: "parcial-f4e37",
    storageBucket: "parcial-f4e37.firebasestorage.app",
    messagingSenderId: "140745516905",
    appId: "1:140745516905:web:b7903497ba2e5cb46afff8"
}

const app = initializeApp(firebaseConfig)
const auth = getAuth(app)

export { auth }