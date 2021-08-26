window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.dayjs = require('dayjs');
window.dayjs.extend(require('dayjs/plugin/relativeTime'));

import TinyGesture from 'tinygesture';
window.TinyGesture = TinyGesture;

import images from './utils/images';
window.addEventListener('load', images);

import 'focus-visible';