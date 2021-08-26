const body = document.body;
const gesture = new TinyGesture(body);

gesture.on('swiperight', (e) => {
    const event = new Event('swiperight');
    body.dispatchEvent(event);
});

gesture.on('swipeleft', (e) => {
    const event = new Event('swipeleft');
    body.dispatchEvent(event);
});