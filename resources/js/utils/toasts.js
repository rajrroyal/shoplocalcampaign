import Timer from './timer';

const toasts = () => {
    return {
        toasts: [],
        init(toastElem) {
            // echo server listener
            window.Echo.private(`user.update.${user_id}`)
                .listen('.toast', (e) => {
                    this.addToast(e.toast);
                });

            // add new toast listener
            window.addToast = (toastInfo) => {
                return this.addToast(toastInfo);
            };

            // observer to add gestures to toast once added to DOM
            const observer = new MutationObserver((mutationsList, observer) => {
                mutationsList.forEach(mutation => {
                    mutation.addedNodes.forEach(node => {
                        if(node.nodeType == 1) {
                            const toast = node;

                            this.addGestures(toast);
                        }
                    })
                });
            });
            
            observer.observe(toastElem, {childList: true, });
        },
        addToast(toastInfo) {
            const toast_id = this.toasts.length;
        
            // handle simple toast messages
            if(typeof toastInfo == 'string') {
                toastInfo = {
                    message: toastInfo
                };
            };
            
            removeEmpty(toastInfo);
        
            const toast = Object.assign({
                id: toast_id,
                open: true,
                icon: 'info',
                title: null,
                message: null,
                url: null,
                closeButton: true,
                timeout: 5000
            }, toastInfo);
            
            // add timer to pause on mouseover
            toast.timer = new Timer(() => {
                this.removeToast(toast_id);
            }, toast.timeout);
        
            this.toasts.push(toast);
        
            return toast_id;
        },
        removeToast(toast_id) {
            this.toasts[toast_id].open = false;
        },
        addGestures(toast) {
            const toast_id = toast.dataset.toastId;
        
            const gesture = new TinyGesture(toast, {mouseSupport: true});
        
            gesture.on('panmove', event => {
                event.stopPropagation();
        
                this.toasts[toast_id].timer.pause();
        
                if(gesture.touchMoveX > 0) {
                    toast.style.transform = `translateX(${gesture.touchMoveX}px)`;
                    toast.style.opacity = (toast.offsetWidth - gesture.touchMoveX) / toast.offsetWidth;
                };
            });
        
            gesture.on('panend', event => {
                event.stopPropagation();
                const isSwipe = (gesture.swipingDirection === 'horizontal' && gesture.touchMoveX > 0);
        
                if(!isSwipe) {
                    this.toasts[toast_id].timer.resume();
        
                    toast.style.transform = null;
                    toast.style.opacity = null;
                };
            });
        
            gesture.on('swiperight', event => {
                event.stopPropagation();
                this.removeToast(toast_id);
            });
        }
    }
}

// remove null values from object
const removeEmpty = (obj) => {
    Object.keys(obj).forEach(key => obj[key] == null && delete obj[key]);
};

export default toasts;