// account dashboard stores list
window.dashboardStores = (stores) => {
    return {
        stores: stores.data,
        init() {
            window.Echo.private(`user.update.${user_id}`)
                .listen('.update.progress', (e) => {
                    this.updateProgress(e);
                })
                .listen('.update.complete', (e) => {
                    this.updateProgress(e);
                });
        },
        updateStore(store) {
            store.updating = true;

            axios.get(store.update_url).then(response => {
                console.log(response)
            })
        },
        updateProgress(e) {
            const store = e.store;
            const storeElem = document.querySelector(`[data-store-id="${store.id}"]`);

            this.stores.map((store, index) => {
                if(store.id === e.store.id) {
                    this.stores[index] = e.store;
                }
            });

            const percent = e.percent || 0;

            const progressText = storeElem.querySelector(`[data-progress-text]`);
            progressText.innerHTML = percent.toFixed(0) + '%';

            const progressBar = storeElem.querySelector(`[data-progress-bar]`);
            progressBar.style.transform = percent ? `scaleX(${percent / 100})` : null;
            
        }
    }
}

window.connectStore = () => {
    return {
        connectShopify(event) {
            const form = new FormData(event.target);

            const shop = form.get('shop');
            const client_id = form.get('client_id');
            const scope = form.get('scope');
            const redirect_uri = form.get('redirect_uri');
            const state = form.get('state');

            const authUrl = `https://${shop}.myshopify.com/admin/oauth/authorize?client_id=${client_id}&scope=${scope}&redirect_uri=${redirect_uri}&state=${state}`;

            const authWindow = window.open(authUrl, '_blank');

            this.callbackListener(authWindow);
        },
        connectPowerShop(authUrl) {
            const authWindow = window.open(authUrl, '_blank');

            this.callbackListener(authWindow);
        },
        callbackListener(authWindow) {
            window.addEventListener('message', function(e) {
                const hostname = (new URL(e.origin)).hostname;
                if(hostname !== window.location.hostname) {
                    return;
                }

                const message = e.data;
                
                if(message.event == 'store_authorized') {
                    authWindow.close();

                    window.location.href = message.redirect_url;
                }
            });
        },
        authCallback(redirect_url) {
            const message = {
                event: 'store_authorized',
                redirect_url: redirect_url
            };

            window.opener.postMessage(message, '*');
        }
    }
};