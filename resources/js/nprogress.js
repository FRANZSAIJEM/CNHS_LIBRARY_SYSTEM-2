import NProgress from 'nprogress';
import 'nprogress/nprogress.css';

// Initialize NProgress
NProgress.configure({ showSpinner: false });

// Add event listeners to start and stop NProgress
window.addEventListener('beforeunload', () => {
  NProgress.start();
});

window.addEventListener('load', () => {
  NProgress.done();
});
