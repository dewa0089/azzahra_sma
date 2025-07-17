// public/firebase-messaging-sw.js
importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.0.0/firebase-messaging-compat.js');

firebase.initializeApp({
    apiKey: "AIzaSyB_gRGTKhf0kXwyGu-U3fhcSmR026oABeQ",
    authDomain: "inventarissekolah-c84fc.firebaseapp.com",
    projectId: "inventarissekolah-c84fc",
    storageBucket: "inventarissekolah-c84fc.firebasestorage.app",
    messagingSenderId: "791710839955",
    appId: "1:791710839955:web:ce816debc8f7b9ec224966",
});

const messaging = firebase.messaging();

// messaging.onBackgroundMessage(function(payload) {
//     console.log('[firebase-messaging-sw.js] Message received: ', payload);

//     const notificationTitle = payload.notification.title;
//     const notificationOptions = {
//         body: payload.notification.body,
//         icon: '/images/logo.jpeg' // sesuaikan dengan icon Anda
//     };

//     self.registration.showNotification(notificationTitle, notificationOptions);
// });
