function subscribeUser() {
    navigator.serviceWorker.ready
        .then((registration) => {
            const subscribeOptions = {
                userVisibleOnly: true,
                applicationServerKey: urlBase64ToUint8Array(
                    'BIuqizAJHGjChC0fOPrwA2b_U6r5dJ5M8Ut9qhnFrvThl9rPJen7M3L4EWKuJjAtrCNw8XUx22juKC2mI5fEGyw'
                )
            };

            return registration.pushManager.subscribe(subscribeOptions);
        })
        .then((pushSubscription) => {
            console.log('Received PushSubscription: ', JSON.stringify(pushSubscription));
            storePushSubscription(pushSubscription);
        });
}