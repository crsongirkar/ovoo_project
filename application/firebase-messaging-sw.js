// public/firebase-messaging-sw.js
importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js');

firebase.initializeApp({
    apiKey: "AIzaSyBuzEj6ofpMAwKodnrMIDJvnyymL0JYyP4",
    authDomain: "ovoo-9f385.firebaseapp.com",
    projectId: "ovoo-9f385",
    storageBucket: "ovoo-9f385.firebasestorage.app",
    messagingSenderId: "793043441860",
    appId: "1:793043441860:web:5bc82c275a27c39fb15191",
    measurementId: "G-SM2BZEBVTK"
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage((payload) => {
    console.log('[SW] Received background message', payload);
    const notificationTitle = payload.notification.title;
    const notificationOptions = {
        body: payload.notification.body,
        icon: '/assets/img/logo.png'
    };

    return self.registration.showNotification(notificationTitle, notificationOptions);
});